<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    private function cache(): CacheRepository
    {
        return Cache::store('redis');
    }

    private function otp(): string
    {
        return (string) random_int(100000, 999999);
    }

    private function key(string $type, string|int $id): string
    {
        return 'oneictms:auth:' . $type . ':' . $id;
    }

    private function sendOtp(string $email, string $code, string $purpose): void
    {
        $view = $purpose === 'login' ? 'emails.auth.login-otp' : 'emails.auth.verify-email';
        $subject = $purpose === 'login' ? 'Your login verification code' : 'Verify your email address';
        Mail::send($view, ['otp' => $code], function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

    private function throttle(string $key, int $max, int $seconds): void
    {
        if (RateLimiter::tooManyAttempts($key, $max)) {
            throw ValidationException::withMessages(['otp' => 'Too many attempts. Try again later.']);
        }
        RateLimiter::hit($key, $seconds);
    }

    public function register(array $data): User
    {
        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            $user->details()->create([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'ext_name' => $data['ext_name'] ?? null,
                'sex' => $data['sex'] ?? null,
                'position_id' => $data['position_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'division_id' => $data['division_id'] ?? null,
            ]);
            return $user;
        });
        $this->issueEmailCode($user);
        return $user->load('details');
    }

    private function issueEmailCode(User $user): void
    {
        $code = $this->otp();
        $this->cache()->put($this->key('verify-email', $user->id), Hash::make($code), now()->addMinutes(5));
        $this->sendOtp($user->email, $code, 'email');
    }

    public function login(array $data, string $ip): array
    {
        $login = $data['login'];
        $this->throttle('auth:login:' . hash('sha256', Str::lower($login) . '|' . $ip), 5, 60);
        $column = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($column, $login)->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages(['login' => 'Invalid credentials.']);
        }
        if (! $user->hasVerifiedEmail()) {
            throw ValidationException::withMessages(['email' => 'Verify your email before logging in.']);
        }

        $challenge = (string) Str::uuid();
        $code = $this->otp();
        $this->cache()->put($this->key('login', $challenge), [
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
        ], now()->addMinutes(5));
        $this->sendOtp($user->email, $code, 'login');

        return ['challenge_id' => $challenge, 'expires_in' => 300];
    }

    public function verifyLoginOtp(string $challenge, string $otp): array
    {
        $key = $this->key('login', $challenge);
        $this->throttle('auth:otp:' . $challenge, 5, 300);
        $stored = $this->cache()->get($key);
        if (! $stored || ! Hash::check($otp, $stored['code_hash'])) {
            throw ValidationException::withMessages(['otp' => 'Invalid or expired OTP.']);
        }
        // A Redis lock prevents two concurrent verifications from using one challenge.
        return $this->cache()->lock('auth:lock:' . $challenge, 10)->block(2, function () use ($key, $stored, $challenge) {
            $current = $this->cache()->pull($key);
            if (! $current || $current !== $stored) {
                throw ValidationException::withMessages(['otp' => 'OTP has already been used.']);
            }
            $user = User::findOrFail($stored['user_id']);
            if (! $user->hasVerifiedEmail()) {
                throw ValidationException::withMessages(['email' => 'Email is not verified.']);
            }
            $token = auth('api')->login($user);
            $ttlSeconds = (int) config('jwt.ttl', 60) * 60;
            RateLimiter::clear('auth:otp:' . $challenge);
            return [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => $ttlSeconds,
                'user' => $user->load('details'),
            ];
        });
    }

    public function resendLoginOtp(string $challenge): void
    {
        $key = $this->key('login', $challenge);
        $stored = $this->cache()->get($key);
        if (! $stored) {
            throw ValidationException::withMessages(['challenge_id' => 'Invalid or expired challenge.']);
        }
        $this->throttle('auth:resend-login:' . $challenge, 3, 300);
        $user = User::findOrFail($stored['user_id']);
        $code = $this->otp();
        $this->cache()->put($key, ['user_id' => $user->id, 'code_hash' => Hash::make($code)], now()->addMinutes(5));
        $this->sendOtp($user->email, $code, 'login');
    }

    public function verifyEmail(string $email, string $otp): void
    {
        $user = User::where('email', $email)->first();
        $this->throttle('auth:verify-email:' . hash('sha256', Str::lower($email)), 5, 300);
        $key = $user ? $this->key('verify-email', $user->id) : '';
        $hash = $user ? $this->cache()->get($key) : null;
        if (! $user || ! $hash || ! Hash::check($otp, $hash)) {
            throw ValidationException::withMessages(['otp' => 'Invalid or expired verification code.']);
        }
        $user->markEmailAsVerified();
        event(new Verified($user));
        $this->cache()->forget($key);
    }

    public function resendVerification(string $email): void
    {
        $this->throttle('auth:resend-email:' . hash('sha256', Str::lower($email)), 3, 3600);
        $user = User::where('email', $email)->first();
        if ($user && ! $user->hasVerifiedEmail()) {
            $this->issueEmailCode($user);
        }
    }

    public function forgotPassword(string $email): void
    {
        $this->throttle('auth:forgot:' . hash('sha256', Str::lower($email)), 3, 3600);
        $user = User::where('email', $email)->first();
        if (! $user) {
            return;
        }
        $token = Password::broker()->createToken($user);
        $link = rtrim(config('app.frontend_url'), '/') . '/reset-password?email=' . rawurlencode($email) . '&token=' . rawurlencode($token);
        Mail::send('emails.auth.reset-password', ['url' => $link], function ($message) use ($email) {
            $message->to($email)->subject('Reset your password');
        });
    }

    public function resetPassword(array $data): void
    {
        $status = Password::broker()->reset($data, function (User $user, string $password) {
            DB::transaction(function () use ($user, $password) {
                $user->forceFill(['password' => $password])->save();
                $user->increment('token_version');
            });
        });
        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['token' => __($status)]);
        }
    }

    public function changePassword(User $user, array $data): void
    {
        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages(['current_password' => 'Current password is incorrect.']);
        }
        DB::transaction(function () use ($user, $data) {
            $user->forceFill(['password' => $data['password']])->save();
            $user->increment('token_version');
        });
        auth('api')->logout();
    }

    public function logout(): void
    {
        auth('api')->logout();
    }

    public function view(User $user): User
    {
        return $user->load([
            'details.position',
            'details.department',
            'details.division',
        ]);
    }
}
