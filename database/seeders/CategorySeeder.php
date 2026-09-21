<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Teknologi',
            'Pendidikan',
            'Kesehatan',
            'Olahraga',
            'Ekonomi',
            'Sosial',
            'Politik',
            'Budaya',
            'Lingkungan'
        ];

        foreach($categories as $category){
            Category::create([
                'name' => $category,
                'slug'=> Str::slug($category),
            ]);
        }
    }
}
