<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Service\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function successResponse(string $message, $data = null, $token = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'token' => $token,
            'data' => new UserResource($data)
        ], $code);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedReq = $request->validated();
        try {
            $data = $this->authService->register($validatedReq);
            return $this->successResponse(
                'success register',
                new UserResource($data['user']),
                $data['token'],
                201
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $data = $this->authService->login($validated);
            return $this->successResponse(
                'success login',
                new UserResource($data['user']),
                $data['token']
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $this->getCurrentUser();
            $this->authService->logout($user);
            return $this->successResponse('success logout');
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}
