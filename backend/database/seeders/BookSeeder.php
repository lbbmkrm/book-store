<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'category_id' => 1,
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'description' => 'A novel set in the Roaring Twenties, depicting the life of Jay Gatsby and his pursuit of the American Dream.',
            'stock' => 10,
            'price' => 150000.00,
            'img' => 'great-gatsby.jpg',
        ]);

        Book::create([
            'category_id' => 2,
            'title' => 'To Kill a Mockingbird',
            'author' => 'Harper Lee',
            'description' => 'A classic novel about racial injustice in the American South.',
            'stock' => 15,
            'price' => 130000.00,
            'img' => 'to-kill-a-mockingbird.jpg',
        ]);

        Book::create([
            'category_id' => 3,
            'title' => '1984',
            'author' => 'George Orwell',
            'description' => 'A dystopian novel about a totalitarian regime that uses surveillance to control its citizens.',
            'stock' => 20,
            'price' => 140000.00,
            'img' => '1984.jpg',
        ]);
    }
}
