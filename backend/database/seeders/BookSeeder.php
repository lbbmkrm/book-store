<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID dari masing-masing kategori berdasarkan nama
        $fiction = Category::where('name', 'Fiction')->first();
        $nonFiction = Category::where('name', 'Non-fiction')->first();
        $technology = Category::where('name', 'Technology')->first();
        $science = Category::where('name', 'Science')->first();

        Book::create([
            'category_id' => $fiction->id,
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'description' => 'A novel set in the Roaring Twenties, depicting the life of Jay Gatsby and his pursuit of the American Dream.',
            'stock' => 10,
            'price' => 150000.00,
            'img' => 'great-gatsby.jpg',
        ]);

        Book::create([
            'category_id' => $fiction->id,
            'title' => 'To Kill a Mockingbird',
            'author' => 'Harper Lee',
            'description' => 'A classic novel about racial injustice in the American South.',
            'stock' => 15,
            'price' => 130000.00,
            'img' => 'to-kill-a-mockingbird.jpg',
        ]);

        Book::create([
            'category_id' => $fiction->id,
            'title' => '1984',
            'author' => 'George Orwell',
            'description' => 'A dystopian novel about a totalitarian regime that uses surveillance to control its citizens.',
            'stock' => 20,
            'price' => 140000.00,
            'img' => '1984.jpg',
        ]);

        Book::create([
            'category_id' => $fiction->id,
            'title' => 'Pride and Prejudice',
            'author' => 'Jane Austen',
            'description' => 'A romantic novel exploring love, class, and societal expectations in 19th-century England.',
            'stock' => 12,
            'price' => 120000.00,
            'img' => 'pride-and-prejudice.jpg',
        ]);

        Book::create([
            'category_id' => $fiction->id,
            'title' => 'Brave New World',
            'author' => 'Aldous Huxley',
            'description' => 'A dystopian novel about a futuristic society driven by technology and conformity.',
            'stock' => 18,
            'price' => 135000.00,
            'img' => 'brave-new-world.jpg',
        ]);

        Book::create([
            'category_id' => $nonFiction->id,
            'title' => 'Sapiens: A Brief History of Humankind',
            'author' => 'Yuval Noah Harari',
            'description' => 'An exploration of human history from the Stone Age to the modern era.',
            'stock' => 25,
            'price' => 180000.00,
            'img' => 'sapiens.jpg',
        ]);

        Book::create([
            'category_id' => $technology->id,
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'description' => 'A guide to writing readable, maintainable, and efficient software code.',
            'stock' => 8,
            'price' => 200000.00,
            'img' => 'clean-code.jpg',
        ]);

        Book::create([
            'category_id' => $fiction->id,
            'title' => 'The Catcher in the Rye',
            'author' => 'J.D. Salinger',
            'description' => 'A story about teenage rebellion and alienation in post-war America.',
            'stock' => 14,
            'price' => 125000.00,
            'img' => 'catcher-in-the-rye.jpg',
        ]);

        Book::create([
            'category_id' => $fiction->id,
            'title' => 'Dune',
            'author' => 'Frank Herbert',
            'description' => 'A science fiction epic about politics, religion, and survival on a desert planet.',
            'stock' => 22,
            'price' => 160000.00,
            'img' => 'dune.jpg',
        ]);

        Book::create([
            'category_id' => $nonFiction->id,
            'title' => 'Educated',
            'author' => 'Tara Westover',
            'description' => 'A memoir about a woman’s journey from a restrictive upbringing to academic success.',
            'stock' => 17,
            'price' => 145000.00,
            'img' => 'educated.jpg',
        ]);

        Book::create([
            'category_id' => $fiction->id,
            'title' => 'Moby-Dick',
            'author' => 'Herman Melville',
            'description' => 'An adventure novel about a captain’s obsessive quest to hunt a white whale.',
            'stock' => 9,
            'price' => 110000.00,
            'img' => 'moby-dick.jpg',
        ]);

        Book::create([
            'category_id' => $science->id,
            'title' => 'Thinking, Fast and Slow',
            'author' => 'Daniel Kahneman',
            'description' => 'A study of human decision-making and the two systems that drive our thoughts.',
            'stock' => 13,
            'price' => 170000.00,
            'img' => 'thinking-fast-and-slow.jpg',
        ]);

        // 9 tambahan
        Book::create([
            'category_id' => $science->id,
            'title' => 'A Brief History of Time',
            'author' => 'Stephen Hawking',
            'description' => 'A landmark volume in science writing by one of the great minds of our time.',
            'stock' => 16,
            'price' => 150000.00,
            'img' => 'brief-history-of-time.jpg',
        ]);

        Book::create([
            'category_id' => $nonFiction->id,
            'title' => 'Becoming',
            'author' => 'Michelle Obama',
            'description' => 'A deeply personal memoir by the former First Lady of the United States.',
            'stock' => 20,
            'price' => 175000.00,
            'img' => 'becoming.jpg',
        ]);

        Book::create([
            'category_id' => $technology->id,
            'title' => 'The Pragmatic Programmer',
            'author' => 'Andrew Hunt & David Thomas',
            'description' => 'Your journey to mastery in the world of software development.',
            'stock' => 12,
            'price' => 190000.00,
            'img' => 'pragmatic-programmer.jpg',
        ]);

        Book::create([
            'category_id' => $technology->id,
            'title' => 'Introduction to Algorithms',
            'author' => 'Thomas H. Cormen',
            'description' => 'A comprehensive textbook covering a broad range of algorithms.',
            'stock' => 10,
            'price' => 250000.00,
            'img' => 'introduction-to-algorithms.jpg',
        ]);

        Book::create([
            'category_id' => $science->id,
            'title' => 'Cosmos',
            'author' => 'Carl Sagan',
            'description' => 'Explores the universe and our place in it with poetic scientific insight.',
            'stock' => 14,
            'price' => 180000.00,
            'img' => 'cosmos.jpg',
        ]);

        Book::create([
            'category_id' => $nonFiction->id,
            'title' => 'Outliers: The Story of Success',
            'author' => 'Malcolm Gladwell',
            'description' => 'An investigation into what makes high-achievers different.',
            'stock' => 21,
            'price' => 150000.00,
            'img' => 'outliers.jpg',
        ]);

        Book::create([
            'category_id' => $nonFiction->id,
            'title' => 'The Art of War',
            'author' => 'Sun Tzu',
            'description' => 'An ancient Chinese military treatise on strategy and tactics.',
            'stock' => 18,
            'price' => 120000.00,
            'img' => 'art-of-war.jpg',
        ]);

        Book::create([
            'category_id' => $technology->id,
            'title' => 'Code Complete',
            'author' => 'Steve McConnell',
            'description' => 'A practical handbook of software construction.',
            'stock' => 11,
            'price' => 210000.00,
            'img' => 'code-complete.jpg',
        ]);
    }
}
