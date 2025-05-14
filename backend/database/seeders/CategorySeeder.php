<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::insert([
            [
                'name' => 'Fiksi',
                'img' => 'fiksi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Non-Fiksi',
                'img' => 'nonfiksi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pendidikan',
                'img' => 'pendidikan.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Komik',
                'img' => 'komik.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anak-Anak',
                'img' => 'anak-anak.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
