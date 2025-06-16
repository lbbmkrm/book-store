<?php

namespace App\Http\Controllers;

use Exception;
use App\Service\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\LoginResource;
use App\Http\Requests\Auth\RegisterRequest;

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
                'success' => true,
                'message' => 'Register success.',
                'token' => $data['token'],
                'data' => new UserResource($data['user'])
            ], 201);
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $data = $this->authService->login($validated);
            return response()->json([
                'success' => true,
                'message' => 'Success login.',
                'token' => $data['token'],
                'data' => new UserResource($data['user'])
            ]);
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $user = $this->getCurrentUser();
            $this->authService->logout($user);
            return $this->successResponse('Success logout');
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function authenticated(): JsonResponse
    {
        try {
            $user = $this->getCurrentUser();
            return $this->successResponse('Authenticated', new UserResource($user));
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}
