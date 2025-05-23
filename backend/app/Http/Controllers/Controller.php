<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    use AuthorizesRequests;
    public function successResponse(string $message,  $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,

        ], $code);
    }

    public function failedResponse(Exception $exception): JsonResponse
    {
        $code = $exception->getCode();

        if (!is_int($code) || $code < 100 || $code >= 600) {
            $code = 500;
        }

        return response()->json([
            'message' => $exception->getMessage(),
        ], $code);
    }

    public function getCurrentUser(): ?User
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            throw new Exception('unauthentiacted', 401);
        }
        return $user;
    }

    public function isAuthorized(string $ability, array|string $arguments)
    {
        try {
            $this->authorize($ability, $arguments);
        } catch (AuthorizationException $e) {
            throw new Exception('unauthorized!', 403);
        }
    }
}
