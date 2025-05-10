<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    private $model;
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function me(): User
    {
        return Auth::user();
    }

    public function register(array $credentials): User
    {
        return $this->model->create($credentials);
    }

    public function login(array $credentials): bool
    {
        return Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ]);
    }

    public function logout(): void
    {
        $user = $this->me();
        $user->tokens()->delete();
    }
}
