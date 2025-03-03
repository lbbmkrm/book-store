<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['Fiction', 'Non-fiction', 'Technology', 'Science', 'Self-help'];
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
