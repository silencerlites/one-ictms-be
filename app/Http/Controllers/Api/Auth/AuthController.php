<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Managers\AuthManager;
use Illuminate\Http\JsonResponse;
use App\Support\Auth\AuthCookie;

class AuthController extends Controller
{
    public function __construct(private readonly AuthManager $manager) {}

    private function ok(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $body = ['success' => true, 'message' => $message];
        if ($data !== null) { $body['data'] = $data; }
        return response()->json($body, $status);
    }

    public function register(AuthRequest $request): JsonResponse
    {
        $user = $this->manager->register($request->validated());
        return $this->ok('Account created. Check your email for the verification code.', (new AuthResource($user))->resolve($request), 201);
    }

    public function login(AuthRequest $request): JsonResponse
    {
        return $this->ok('Login OTP sent to your email.', $this->manager->login($request->validated(), $request->ip() ?? 'unknown'));
    }

    public function verifyLoginOtp(AuthRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->manager->verifyLoginOtp($data['challenge_id'], $data['otp']);
        $token = $result['access_token'];
        $expiresIn = $result['expires_in'];

        unset($result['access_token'], $result['token_type']);
        return response()->json(['success' => true, 'message' => 'Login verified successfully.', 'data' => $result])->withCookie(AuthCookie::create($token, $expiresIn));
    }


    public function resendLoginOtp(AuthRequest $request): JsonResponse
    {
        $this->manager->resendLoginOtp($request->validated('challenge_id'));
        return $this->ok('A new login code was sent.');
    }

    public function logout(): JsonResponse
    {
        $this->manager->logout();
        return response()->json(['success' => true, 'message' => 'Logged out successfully.'])->withCookie(AuthCookie::forget());
    }

    public function verifyEmail(AuthRequest $request): JsonResponse
    {
        $this->manager->verifyEmail($request->validated('email'), $request->validated('otp'));
        return $this->ok('Email verified successfully.');
    }

    public function resendVerification(AuthRequest $request): JsonResponse
    {
        $this->manager->resendVerification($request->validated('email'));
        return $this->ok('If the account needs verification, a code has been sent.');
    }

    public function forgotPassword(AuthRequest $request): JsonResponse
    {
        $this->manager->forgotPassword($request->validated('email'));
        return $this->ok('If that email is registered, a reset link has been sent.');
    }

    public function resetPassword(AuthRequest $request): JsonResponse
    {
        $this->manager->resetPassword($request->validated());
        return $this->ok('Password reset successfully. Please log in again.');
    }

    public function changePassword(AuthRequest $request): JsonResponse
    {
        $this->manager->changePassword(auth('api')->user(), $request->validated());
        return $this->ok('Password changed. Please log in again.');
    }

    public function view(): JsonResponse
    {
        $user = $this->manager->view(auth('api')->user());
        return response()->json(['success' => true, 'message' => 'User retrieved successfully.', 'data' => new \App\Http\Resources\Auth\AuthResource($user)]);
    }
}
