<?php

namespace App\Managers;

use App\Models\User;
use App\Services\AuthService;

class AuthManager
{
    public function __construct(private readonly AuthService $service) {}

    public function register(array $data): User
    {
        return $this->service->register($data);
    }
    public function login(array $data, string $ip): array
    {
        return $this->service->login($data, $ip);
    }
    public function verifyLoginOtp(string $id, string $otp): array
    {
        return $this->service->verifyLoginOtp($id, $otp);
    }
    public function resendLoginOtp(string $id): void
    {
        $this->service->resendLoginOtp($id);
    }
    public function logout(): void
    {
        $this->service->logout();
    }
    public function verifyEmail(string $email, string $otp): void
    {
        $this->service->verifyEmail($email, $otp);
    }
    public function resendVerification(string $email): void
    {
        $this->service->resendVerification($email);
    }
    public function forgotPassword(string $email): void
    {
        $this->service->forgotPassword($email);
    }
    public function resetPassword(array $data): void
    {
        $this->service->resetPassword($data);
    }
    public function changePassword(User $user, array $data): void
    {
        $this->service->changePassword($user, $data);
    }
    public function view(User $user): User
    {
        return $this->service->view($user);
    }
}
