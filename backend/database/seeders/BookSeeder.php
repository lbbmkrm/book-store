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
        $categoryPendidikan = Category::where('name', 'Pendidikan')->first();
        $categoryKomik = Category::where('name', 'Komik')->first();
        $categoryNovel = Category::where('name', 'Novel')->first();

        Book::insert([
            [
                'category_id' => $categoryFiksi->id,
                'title' => 'Petualangan di Dunia Fantasi',
                'author' => 'Arman Hakim',
                'description' => 'Kisah petualangan luar biasa di dunia fantasi.',
                'stock' => 8,
                'price' => 135000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryNonFiksi->id,
                'title' => 'Panduan Manajemen Waktu',
                'author' => 'Lisa Putri',
                'description' => 'Strategi efektif mengatur waktu untuk produktivitas.',
                'stock' => 12,
                'price' => 98000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryPendidikan->id,
                'title' => 'Belajar Matematika Dasar',
                'author' => 'Dedi Sutisna',
                'description' => 'Buku pelajaran matematika untuk tingkat dasar.',
                'stock' => 20,
                'price' => 75000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryPendidikan->id,
                'title' => 'Sejarah Indonesia Modern',
                'author' => 'Rina Anjani',
                'description' => 'Membahas sejarah Indonesia dari masa penjajahan hingga reformasi.',
                'stock' => 7,
                'price' => 88000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryKomik->id,
                'title' => 'Komik Petualangan Si Riko',
                'author' => 'Teguh Santosa',
                'description' => 'Komik anak tentang petualangan lucu Si Riko.',
                'stock' => 15,
                'price' => 60000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryKomik->id,
                'title' => 'Cerita Detektif Anak',
                'author' => 'Yuli Handayani',
                'description' => 'Kumpulan cerita detektif untuk anak-anak.',
                'stock' => 10,
                'price' => 68000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryNovel->id,
                'title' => 'Cinta di Ujung Senja',
                'author' => 'Alya Rachma',
                'description' => 'Novel romansa remaja yang menyentuh hati.',
                'stock' => 6,
                'price' => 110000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryNovel->id,
                'title' => 'Langit Tak Lagi Biru',
                'author' => 'Bima Arya',
                'description' => 'Cerita tentang pencarian jati diri dan harapan.',
                'stock' => 9,
                'price' => 99000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryFiksi->id,
                'title' => 'Legenda Sang Penjaga',
                'author' => 'Indra Prasetyo',
                'description' => 'Dongeng fiksi tentang penjaga dunia sihir.',
                'stock' => 13,
                'price' => 125000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryNonFiksi->id,
                'title' => 'Psikologi untuk Pemula',
                'author' => 'Melati Kusuma',
                'description' => 'Pengenalan dasar ilmu psikologi untuk semua kalangan.',
                'stock' => 11,
                'price' => 105000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryNovel->id,
                'title' => 'Senandika Rindu',
                'author' => 'Raka Dharma',
                'description' => 'Puisi dan prosa yang menyentuh tentang kerinduan.',
                'stock' => 5,
                'price' => 92000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryFiksi->id,
                'title' => 'Dimensi Tak Terlihat',
                'author' => 'Nadya Aulia',
                'description' => 'Cerita sci-fi yang membawa pembaca ke dimensi lain.',
                'stock' => 14,
                'price' => 130000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryNonFiksi->id,
                'title' => 'Mindset Pengusaha Sukses',
                'author' => 'Tommy Wijaya',
                'description' => 'Pelajaran hidup dari para pengusaha top Indonesia.',
                'stock' => 18,
                'price' => 119000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryPendidikan->id,
                'title' => 'Bahasa Inggris Praktis',
                'author' => 'Citra Lestari',
                'description' => 'Belajar bahasa Inggris dengan metode sehari-hari.',
                'stock' => 22,
                'price' => 77000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $categoryKomik->id,
                'title' => 'Petualangan Super Budi',
                'author' => 'Erik Sanjaya',
                'description' => 'Komik lucu tentang anak biasa dengan kekuatan luar biasa.',
                'stock' => 17,
                'price' => 58000.00,
                'img' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
