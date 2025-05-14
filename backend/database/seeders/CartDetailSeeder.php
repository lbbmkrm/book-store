<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CartDetail;
use App\Models\Cart;
use App\Models\Book;

class CartDetailSeeder extends Seeder
{
    public function run()
    {
        $cart = Cart::first();  // Ambil keranjang pertama
        $book = Book::where('title', 'Novel Aksi')->first();  // Ambil buku pertama

        CartDetail::insert([
            [
                'cart_id' => $cart->id,
                'book_id' => $book->id,
                'quantity' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
