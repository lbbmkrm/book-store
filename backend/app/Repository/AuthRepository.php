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

    public function getUserById(int $id): ?User
    {
        return $this->model->findOrFail($id);
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
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

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
