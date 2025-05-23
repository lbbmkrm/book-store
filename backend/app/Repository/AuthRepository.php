<?php

namespace App\Repository;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\MassAssignmentException;

class AuthRepository
{
    private User $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getUserById(int $id): ?User
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('User not found.', 404);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve user.', 500);
        }
    }

    public function getUserByEmail(string $email): ?User
    {
        try {
            return $this->model->where('email', $email)->first();
        } catch (QueryException $e) {
            throw new Exception('Failed to retrieve user by email due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve user by email.', 500);
        }
    }

    public function register(array $credentials): User
    {
        try {
            return $this->model->create($credentials);
        } catch (MassAssignmentException $e) {
            throw new Exception('Invalid data provided for registration.', 422);
        } catch (QueryException $e) {
            throw new Exception('Failed to register user due to a database error.', 422);
        } catch (Exception $e) {
            throw new Exception('Failed to register user.', 500);
        }
    }

    public function login(array $credentials): bool
    {
        try {
            return Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ]);
        } catch (Exception $e) {
            throw new Exception('Failed to login.', 500);
        }
    }
}
