<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\CartDetail;

class CartSeeder extends Seeder
{
    public function run()
    {
        $cart = Cart::create(['user_id' => 2]); // John Doe

        CartDetail::create([
            'cart_id' => $cart->id,
            'book_id' => 1,
            'quantity' => 2
        ]);
    }
}
