<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 't-shirts')->first();
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        $products = [
            [
                'slug' => 'personna-tee',
                'price' => 320,
                'name' => ['ro' => 'Tricou Personna', 'ru' => 'Футболка Personna', 'en' => 'Personna Tee'],
                'description' => [
                    'ro' => 'Tricou din bumbac premium cu logotipul Personna brodat discret pe piept.',
                    'ru' => 'Футболка из премиального хлопка с деликатным логотипом Personna на груди.',
                    'en' => 'Premium cotton tee with the Personna wordmark subtly embroidered on the chest.',
                ],
            ],
            [
                'slug' => 'lips-tee',
                'price' => 350,
                'name' => ['ro' => 'Tricou Lips', 'ru' => 'Футболка Lips', 'en' => 'Lips Tee'],
                'description' => [
                    'ro' => 'Tricou alb cu imprimeu grafic „lips", croială relaxată.',
                    'ru' => 'Белая футболка с графическим принтом «lips», свободный крой.',
                    'en' => 'White tee with a graphic lips print and a relaxed fit.',
                ],
            ],
            [
                'slug' => 'cheetah-tee',
                'price' => 350,
                'name' => ['ro' => 'Tricou Cheetah', 'ru' => 'Футболка Cheetah', 'en' => 'Cheetah Tee'],
                'description' => [
                    'ro' => 'Tricou din bumbac cu imprimeu inspirat de ghepard, pentru un look unic.',
                    'ru' => 'Хлопковая футболка с принтом в стиле гепарда для уникального образа.',
                    'en' => 'Cotton tee with a cheetah-inspired print for a one-of-a-kind look.',
                ],
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $category?->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'meta_title' => $data['name'],
                    'meta_description' => $data['description'],
                    'price' => $data['price'],
                    'sizes' => $sizes,
                    'is_active' => true,
                ],
            );
        }
    }
}
