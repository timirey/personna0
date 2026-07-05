<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $base = fake()->unique()->words(3, true);
        $desc = fake()->sentence(12);

        return [
            'category_id' => Category::factory(),
            'name' => [
                'ro' => Str::title($base),
                'ru' => Str::title($base).' (ru)',
                'en' => Str::title($base),
            ],
            'description' => [
                'ro' => $desc,
                'ru' => $desc.' (ru)',
                'en' => $desc,
            ],
            'meta_title' => [
                'ro' => Str::title($base),
                'ru' => Str::title($base),
                'en' => Str::title($base),
            ],
            'meta_description' => [
                'ro' => $desc,
                'ru' => $desc,
                'en' => $desc,
            ],
            'slug' => Str::slug($base),
            'price' => fake()->randomFloat(2, 100, 900),
            'stock' => fake()->optional(0.5)->numberBetween(1, 50), // null half the time = unlimited
            'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function unlimitedStock(): static
    {
        return $this->state(fn () => ['stock' => null]);
    }

    public function withStock(int $stock): static
    {
        return $this->state(fn () => ['stock' => $stock]);
    }

    public function noSizes(): static
    {
        return $this->state(fn () => ['sizes' => []]);
    }
}
