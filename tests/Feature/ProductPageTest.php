<?php

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

it('exposes product props including sizes', function () {
    Product::factory()->withStock(5)->create([
        'slug' => 'tee',
        'name' => ['en' => 'Tee'],
        'sizes' => ['S', 'M', 'L'],
    ]);

    $this->get('/en/product/tee')->assertInertia(fn (Assert $page) => $page
        ->component('Product')
        ->where('product.sizes', ['S', 'M', 'L'])
        ->where('product.soldOut', false)
    );
});

it('marks a depleted product as sold out', function () {
    Product::factory()->withStock(0)->create(['slug' => 'gone']);

    $this->get('/en/product/gone')->assertInertia(fn (Assert $page) => $page->where('product.soldOut', true));
});

it('passes Product + Breadcrumb JSON-LD data', function () {
    Product::factory()->withStock(3)->create(['slug' => 'tee2', 'price' => 250]);

    $this->get('/en/product/tee2')->assertInertia(fn (Assert $page) => $page
        ->has('jsonld', 2)
        ->where('jsonld.0.@type', 'Product')
        ->where('jsonld.0.offers.priceCurrency', 'MDL')
    );
});

it('provides gallery images for the swipe slider', function () {
    Storage::fake('public');
    $product = Product::factory()->create(['slug' => 'gallery-tee']);
    $product->addMedia(UploadedFile::fake()->image('a.jpg', 800, 1000))->toMediaCollection('gallery');
    $product->addMedia(UploadedFile::fake()->image('b.jpg', 800, 1000))->toMediaCollection('gallery');

    $this->get('/en/product/gallery-tee')->assertInertia(fn (Assert $page) => $page->has('product.gallery', 2));
});
