<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password123'),
                'img' => null,
                'address' => 'Jl. Admin No.1, Medan',
                'phone' => '081234567890',
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => bcrypt('password123'),
                'img' => null,
                'address' => 'Jl. Raya No.15, Medan',
                'phone' => '081234567891',
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Doni Wijaya',
                'email' => 'doniwijaya@example.com',
                'password' => bcrypt('password123'),
                'img' => null,
                'address' => 'Jl. Gatot Subroto No.11, Medan',
                'phone' => '081234567891',
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Aisyah',
                'email' => 'siti.aisyah@example.com',
                'password' => bcrypt('password123'),
                'img' => null,
                'address' => 'Jl. Sukarno No.10, Medan',
                'phone' => '081234567892',
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
