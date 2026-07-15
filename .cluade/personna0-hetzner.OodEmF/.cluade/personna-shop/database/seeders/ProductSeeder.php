<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        $items = [
            ['name' => 'Cheetah Tee — Stone', 'price' => 350, 'description' => 'Heavyweight cotton tee with the cheetah print.'],
            ['name' => 'Lips Tee — White',     'price' => 350, 'description' => 'White cotton tee with the lips print.'],
            ['name' => 'Personna Tee — White', 'price' => 320, 'description' => 'Clean white tee with the Personna back-print.'],
        ];

        foreach ($items as $item) {
            Product::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name'        => $item['name'],
                    'description' => $item['description'],
                    'price'       => $item['price'],
                    'sizes'       => $sizes,
                    'is_active'   => true,
                ],
            );
        }
    }
}
