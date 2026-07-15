<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::updateOrCreate(
            ['slug' => 't-shirts'],
            [
                'name' => ['ro' => 'Tricouri', 'ru' => 'Футболки', 'en' => 'T-Shirts'],
                'sort' => 1,
                'is_active' => true,
            ],
        );
    }
}
