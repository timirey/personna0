<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

it('stores and resolves product content in three locales', function () {
    $product = Product::factory()->create([
        'name' => ['ro' => 'Tricou', 'ru' => 'Футболка', 'en' => 'T-Shirt'],
    ]);

    expect($product->getTranslation('name', 'ru'))->toBe('Футболка')
        ->and($product->getTranslation('name', 'ro'))->toBe('Tricou');

    app()->setLocale('en');
    expect($product->name)->toBe('T-Shirt');
});

it('exposes a sold-out check driven by tracked stock', function () {
    expect(Product::factory()->withStock(0)->create()->isSoldOut())->toBeTrue()
        ->and(Product::factory()->withStock(3)->create()->isSoldOut())->toBeFalse()
        ->and(Product::factory()->unlimitedStock()->create()->isSoldOut())->toBeFalse();
});

it('registers the gallery media collection', function () {
    $product = Product::factory()->create();

    expect($product->getRegisteredMediaCollections()->pluck('name'))->toContain('gallery');
});

it('scopes active categories and products', function () {
    Category::factory()->create();
    Category::factory()->inactive()->create();

    expect(Category::query()->active()->count())->toBe(1);

    Product::factory()->create();
    Product::factory()->inactive()->create();

    expect(Product::query()->active()->count())->toBe(1);
});

it('relates a category to its products', function () {
    $category = Category::factory()->create();
    Product::factory()->count(2)->for($category)->create();

    expect($category->products)->toHaveCount(2);
});

it('casts order status to an enum and relates items', function () {
    $order = Order::factory()
        ->has(OrderItem::factory()->count(2), 'items')
        ->create();

    expect($order->status)->toBe(OrderStatus::New)
        ->and($order->items)->toHaveCount(2)
        ->and($order->items->first()->order->is($order))->toBeTrue();
});

it('gives each order status a badge color', function () {
    expect(OrderStatus::New->getColor())->toBe('warning')
        ->and(OrderStatus::Completed->getColor())->toBe('success')
        ->and(OrderStatus::Cancelled->getColor())->toBe('danger');
});
