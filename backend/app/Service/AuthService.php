<?php

namespace App\Service;

use Exception;
use App\Repository\AuthRepository;
use Illuminate\Support\Facades\DB;

class AuthService
{
    protected $authRepo;
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepo = $authRepository;
    }

    public function register(array $requestData): ?array
    {
        $data = [
            'name' => $requestData['name'],
            'email' => $requestData['email'],
            'password' => bcrypt($requestData['password'])
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
            throw new Exception('failed register', 500);
        }
    }

    public function login(array $credentials): ?array
    {
        try {
            if (!$this->authRepo->login($credentials)) {
                throw new Exception('invalid credentials', 401);
            };
            $user = $this->authRepo->getUserByEmail($credentials['email']);
            $token = $user->createToken('authentication_token')->plainTextToken;
            return [
                'token' => $token,
                'user' => $user
            ];
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'failed to login',
                $e->getCode() ?: 500
            );
        }
    }

    public function logout(int $userId)
    {
        try {
            $user = $this->authRepo->getUserById($userId);
            if (!$user) {
                throw new Exception('user not found', 404);
            }
            $this->authRepo->logout($user);
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'failed logout',
                $e->getCode() ?: 500
            );
        }
    }
}
