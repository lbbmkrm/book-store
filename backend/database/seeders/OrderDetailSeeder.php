<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Book;

class OrderDetailSeeder extends Seeder
{
    public function run()
    {
        $order = Order::first();
        $book = Book::where('title', 'Novel Aksi')->first();

        OrderDetail::insert([
            [
                'order_id' => $order->id,
                'book_id' => $book->id,
                'quantity' => 2,
                'price' => $book->price,
                'updated_at' => now(),
            ]
        ]);
    }
}
