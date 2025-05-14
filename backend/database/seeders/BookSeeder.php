<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run()
    {
        $categoryFiksi = Category::where('name', 'Fiksi')->first();
        $categoryNonFiksi = Category::where('name', 'Non-Fiksi')->first();

        Book::insert([
            [
                'category_id' => $categoryFiksi->id,
                'title' => 'Novel Aksi',
                'author' => 'John Doe',
                'description' => 'A thrilling action novel',
                'stock' => 10,
                'price' => 150000.00,
                'img' => 'novel_aksi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryNonFiksi->id,
                'title' => 'Biografi Seorang Pemimpin',
                'author' => 'Jane Smith',
                'description' => 'A biography of a renowned leader',
                'stock' => 5,
                'price' => 120000.00,
                'img' => 'biografi_pemimpin.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
