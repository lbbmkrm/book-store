<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;

class CartSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please seed the users table first.');
            return;
        }

        foreach ($users as $user) {
            $cartData = [
                'user_id' => $user->id,
                'created_at' => now(),
            ];

            Cart::updateOrCreate(
                ['user_id' => $user->id],
                $cartData
            );

            $this->command->info("Cart created for user ID: {$user->id}");
        }
    }
}
