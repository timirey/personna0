<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $base = fake()->unique()->words(2, true);

        return [
            'name' => [
                'ro' => Str::title($base),
                'ru' => Str::title($base).' (ru)',
                'en' => Str::title($base),
            ],
            'slug' => Str::slug($base),
            'sort' => fake()->numberBetween(0, 20),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
