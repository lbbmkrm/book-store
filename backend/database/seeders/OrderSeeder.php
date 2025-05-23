<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'budi.santoso@example.com')->first();

        Order::insert([
            [
                'user_id' => $user->id,
                'total_price' => 300000.00,
                'status' => 'processing',
                'shipping_address' => 'Jl. Raya No.15, Medan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
