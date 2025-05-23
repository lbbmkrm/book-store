<?php

namespace App\Service;

use App\Models\User;
use Exception;
use App\Repository\AuthRepository;
use Illuminate\Support\Facades\DB;

class AuthService
{
    protected AuthRepository $authRepo;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepo = $authRepository;
    }

    public function register(array $requestData): ?array
    {
        $data = [
            'name' => $requestData['name'],
            'email' => $requestData['email'],
            'password' => bcrypt($requestData['password']),
            'address' => $requestData['address'] ?? null,
            'phone' => $requestData['phone'] ?? null,
            'img' => $requestData['img'] ?? null,
        ];

        try {
            DB::beginTransaction();
            $newUser = $this->authRepo->register($data);
            DB::commit();

            $token = $newUser->createToken('authentication_token')->plainTextToken;

            return [
                'token' => $token,
                'user' => $newUser
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'Failed to register.',
                $e->getCode() ?: 500
            );
        }
    }

    public function login(array $credentials): ?array
    {
        try {
            if (!$this->authRepo->login($credentials)) {
                throw new Exception('Invalid credentials.', 401);
            }

            $user = $this->authRepo->getUserByEmail($credentials['email']);

            if (!$user) {
                throw new Exception('User not found after successful login.', 404);
            }

            $token = $user->createToken('authentication_token')->plainTextToken;

            return [
                'token' => $token,
                'user' => $user
            ];
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Failed to login.',
                $e->getCode() ?: 500
            );
        }
    }

    public function logout(User $user): void
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        if (!$token) {
            throw new Exception('No active token found.', 400);
        }

        try {
            $token->delete();
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Failed to logout.',
                $e->getCode() ?: 500
            );
        }
    }
}
