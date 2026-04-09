<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Seed beberapa kategori default untuk blog.
     * Slug akan di-generate otomatis oleh model Category.
     */
    public function run(): void
    {
        $categories = [
            'Technology',
            'Lifestyle',
            'Tutorial',
            'Pengalaman',
            'Tips & Trik',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
