<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('name', 'John Doe')->first();
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 300000
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'book_id' => 1,
            'quantity' => 2,
            'price' => 150000
        ]);
    }
}
