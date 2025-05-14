<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;

class CartSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'budi.santoso@example.com')->first();

        Cart::insert([
            [
                'user_id' => $user->id,
                'created_at' => now(),
            ]
        ]);
    }
}
