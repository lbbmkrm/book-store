<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Service\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedReq = $request->validated();
        try {
            $data = $this->authService->register($validatedReq);
            return response()->json([
                'message' => 'success register',
                'token' => $data['token'],
                'user' => new UserResource($data['user'])
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $data = $this->authService->login($validated);
            return response()->json([
                'message' => 'success login',
                'token' => $data['token'],
                'user' => new UserResource($data['user'])
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function logout(int $id)
    {
        try {
            $this->authService->logout($id);
            return response()->json([
                'message' => 'success logout',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
