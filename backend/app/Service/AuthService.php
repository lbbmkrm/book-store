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
}
