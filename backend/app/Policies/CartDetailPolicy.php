<?php

namespace App\Policies;

use App\Models\CartDetail;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartDetailPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CartDetail $cartDetail): bool
    {
        return $user->id === $cartDetail->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CartDetail $cartDetail): bool
    {
        return $user->id === $cartDetail->user_id;;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CartDetail $cartDetail): bool
    {
        return $user->id === $cartDetail->user_id;;
    }
}
