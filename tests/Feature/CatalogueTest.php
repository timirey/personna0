<?php

use App\Models\Category;
use App\Models\Product;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the catalogue with only active products', function () {
    Product::factory()->create(['name' => ['en' => 'SentinelActive']]);
    Product::factory()->inactive()->create();

    $this->get('/en')->assertInertia(fn (Assert $page) => $page
        ->component('Catalogue')
        ->has('products.data', 1)
        ->where('products.data.0.name', 'SentinelActive')
    );
});

it('filters products by category', function () {
    $category = Category::factory()->create(['slug' => 'tees']);
    Product::factory()->for($category)->create(['name' => ['en' => 'InCat']]);
    Product::factory()->create(['name' => ['en' => 'Other']]);

    $this->get('/en?category=tees')->assertInertia(fn (Assert $page) => $page
        ->component('Catalogue')
        ->where('activeCategory', 'tees')
        ->has('products.data', 1)
        ->where('products.data.0.name', 'InCat')
    );
});

it('shows an active product and 404s an inactive one', function () {
    Product::factory()->create(['slug' => 'black-tee', 'name' => ['en' => 'Black Tee']]);
    Product::factory()->inactive()->create(['slug' => 'hidden-tee']);

    $this->get('/en/product/black-tee')->assertInertia(fn (Assert $page) => $page
        ->component('Product')
        ->where('product.name', 'Black Tee')
    );

    $this->get('/en/product/hidden-tee')->assertNotFound();
});

it('renders product content in the requested locale', function () {
    Product::factory()->create([
        'slug' => 'tee',
        'name' => ['ro' => 'Tricou', 'ru' => 'Футболка', 'en' => 'T-Shirt'],
    ]);

    $this->get('/ru/product/tee')->assertInertia(fn (Assert $page) => $page->where('product.name', 'Футболка'));
    $this->get('/ro/product/tee')->assertInertia(fn (Assert $page) => $page->where('product.name', 'Tricou'));
});
