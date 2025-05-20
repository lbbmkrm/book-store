<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Cart;
use App\Models\User;
use App\Models\CartDetail;
use Illuminate\Database\Seeder;

class CartDetailSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please seed the users table first.');
            return;
        }

        $books = Book::all();
        if ($books->isEmpty()) {
            $this->command->info('No books found. Please seed the books table first.');
            return;
        }

        foreach ($users as $user) {
            $cartData = [
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $cart = Cart::updateOrCreate(
                ['user_id' => $user->id],
                $cartData
            );

            $cartDetailData = [
                [
                    'cart_id' => $cart->id,
                    'book_id' => $books->random()->id,
                    'quantity' => rand(1, 5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'cart_id' => $cart->id,
                    'book_id' => $books->random()->id,
                    'quantity' => rand(1, 5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            foreach ($cartDetailData as $detail) {
                CartDetail::create($detail);
            }

            $this->command->info("Cart and items created for user ID: {$user->id}");
        }
    }
}
