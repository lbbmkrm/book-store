<?php

namespace App\Policies;

use App\Models\CartDetail;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartDetailPolicy
{

    /**
     * Determine whether the user can update the cart detail.
     */
    public function update(User $user, CartDetail $cartDetail)
    {
        return $user->id === $cartDetail->cart->user_id;
    }

    /**
     * Determine whether the user can delete the cart detail.
     */
    public function delete(User $user, CartDetail $cartDetail)
    {
        return $user->id === $cartDetail->cart->user_id;
    }
}
