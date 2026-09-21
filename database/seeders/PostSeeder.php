<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //pastiin ada user sama category
        $admin = User::first();
        $categories = Category::all();

        if (!$admin){
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        $posts = [
            [
                'title' => 'Laravel 13 Resmi Dirilis dengan Fitur Terbaru',
                'content' => 'Laravel 13 telah resmi dirilis dengan fitur terbaru yang memudahkan developer..',
                'category' => 'Teknologi'
            ],
            [
                'title' => 'Pendidik Digital di Era Modern',
                'content' => 'Transformasi digital dalam dunia pendidikan semakin pesat berkembang...',
                'category' => 'Pendidikan'
            ],
            [
                'title' => '5 Makanan Sehat untuk Jantung',
                'content' => 'Menjaga kesehatan jantung dengan mengkonsumsi makan sehat dan menjaga pola makan...',
                'category' => 'Kesehatan'
            ],
        ];
        foreach ($posts as $data) {
            $category = Category::where('name', $data['category'])->first();
            if($category){
                Post::create([
                    'category_id' => $category->id,
                    'user_id' => $admin->id,
                    'title' => $data['title'],
                    'slug' => Str::slug($data['title']),
                    'excerpt' => Str::limit($data['content'], 150),
                    'content' => $data['content'],
                    'status' => 'published',
                    'is_featured' => rand(0, 1) === 1,
                    'published_at'=> now(),
                ]);
            }
        }
    }
}
