<?php

namespace   App\Helpers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

function getCurrentUser(): ?User
{
    $user =  Auth::guard('sanctum')->user();
    if (!$user) {
        throw new Exception('unauthenticated!', 401);
    }
    return $user;
}
