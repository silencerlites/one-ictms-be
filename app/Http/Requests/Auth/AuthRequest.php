<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return match ($this->route()?->getActionMethod()) {
            'register' => [
                'username' => ['required', 'string', 'alpha_dash', 'min:3', 'max:50', Rule::unique('users', 'username')],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
                'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
                'first_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['nullable', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'ext_name' => ['nullable', 'string', 'max:10'],
                'sex' => ['nullable', Rule::in(['Male', 'Female'])],
                'position_id' => ['nullable', 'integer', Rule::exists('positions', 'id')],
                'department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')],
                'division_id' => ['nullable', 'integer', Rule::exists('divisions', 'id')],
            ],
            'login' => [
                'login' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string'],
            ],
            'verifyLoginOtp', 'resendLoginOtp' => array_merge(['challenge_id' => ['required', 'uuid']], $this->route()?->getActionMethod() === 'verifyLoginOtp' ? ['otp' => ['required', 'digits:6']] : []),
            'verifyEmail' => ['email' => ['required', 'email'], 'otp' => ['required', 'digits:6']],
            'resendVerification', 'forgotPassword' => ['email' => ['required', 'email']],
            'resetPassword' => [
                'email' => ['required', 'email'], 'token' => ['required', 'string'],
                'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            ],
            'changePassword' => [
                'current_password' => ['required', 'string'],
                'password' => ['required', 'confirmed', 'different:current_password', Password::min(8)->mixedCase()->numbers()],
            ],
            default => [],
        };
    }
}
