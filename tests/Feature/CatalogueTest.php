<?php

use App\Models\Category;
use App\Models\Product;

it('lists only active products on the catalogue', function () {
    Product::factory()->create(['name' => ['ro' => 'Activ', 'ru' => 'Актив', 'en' => 'SentinelActive']]);
    Product::factory()->inactive()->create(['name' => ['en' => 'SentinelHidden']]);

    $this->get('/en')
        ->assertOk()
        ->assertSee('SentinelActive')
        ->assertDontSee('SentinelHidden');
});

it('wraps the catalogue grid in a turbo-frame for in-place filtering', function () {
    $this->get('/en')
        ->assertOk()
        ->assertSee('turbo-frame id="catalogue-grid"', false)
        ->assertSee('data-turbo-action="advance"', false);
});

it('filters products by category', function () {
    $category = Category::factory()->create(['slug' => 'tees']);
    Product::factory()->for($category)->create(['name' => ['en' => 'SentinelInCat']]);
    Product::factory()->create(['name' => ['en' => 'SentinelOther']]);

    $this->get('/en?category=tees')
        ->assertOk()
        ->assertSee('SentinelInCat')
        ->assertDontSee('SentinelOther');
});

it('shows an active product and 404s an inactive one', function () {
    Product::factory()->create(['slug' => 'black-tee', 'name' => ['en' => 'Black Tee']]);
    Product::factory()->inactive()->create(['slug' => 'hidden-tee']);

    $this->get('/en/product/black-tee')->assertOk()->assertSee('Black Tee');
    $this->get('/en/product/hidden-tee')->assertNotFound();
});

it('renders product content in the requested locale', function () {
    Product::factory()->create([
        'slug' => 'tee',
        'name' => ['ro' => 'Tricou', 'ru' => 'Футболка', 'en' => 'T-Shirt'],
    ]);

    $this->get('/ru/product/tee')->assertOk()->assertSee('Футболка');
    $this->get('/ro/product/tee')->assertOk()->assertSee('Tricou');
});
