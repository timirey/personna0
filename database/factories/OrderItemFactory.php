<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 3);
        $unit = fake()->randomFloat(2, 100, 900);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_name' => fake()->words(3, true),
            'size' => fake()->randomElement(['S', 'M', 'L', null]),
            'qty' => $qty,
            'unit_price' => $unit,
            'line_total' => round($unit * $qty, 2),
        ];
    }
}
