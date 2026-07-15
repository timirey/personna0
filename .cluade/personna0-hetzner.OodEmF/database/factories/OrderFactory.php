<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reference' => 'PN-'.fake()->unique()->numerify('######').'-'.Str::upper(Str::random(4)),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'locale' => fake()->randomElement(['ro', 'ru', 'en']),
            'total' => fake()->randomFloat(2, 100, 2000),
            'status' => OrderStatus::New,
        ];
    }
}
