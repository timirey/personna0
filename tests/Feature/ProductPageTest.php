<?php

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('renders a swipeable gallery slide per image with dots', function () {
    Storage::fake('public');
    $product = Product::factory()->create(['slug' => 'gallery-tee', 'name' => ['en' => 'GalleryTee']]);
    $product->addMedia(UploadedFile::fake()->image('a.jpg', 800, 1000))->toMediaCollection('gallery');
    $product->addMedia(UploadedFile::fake()->image('b.jpg', 800, 1000))->toMediaCollection('gallery');

    $response = $this->get('/en/product/gallery-tee')->assertOk();

    expect(substr_count($response->getContent(), 'gallery__slide'))->toBe(2);
    $response->assertSee('gallery__dot', false);
});

it('shows the brand placeholder when a product has no image', function () {
    Product::factory()->create(['slug' => 'no-img', 'name' => ['en' => 'NoImg']]);

    $this->get('/en/product/no-img')
        ->assertOk()
        ->assertSee('product-img--ph', false)
        ->assertSee('Personna', false);
});

it('renders sizes and the add-to-cart form for an in-stock product', function () {
    Product::factory()->withStock(5)->create([
        'slug' => 'tee',
        'name' => ['en' => 'Tee'],
        'sizes' => ['S', 'M', 'L'],
    ]);

    $this->get('/en/product/tee')
        ->assertOk()
        ->assertSee('Add to cart')
        ->assertSee('value="S"', false)
        ->assertSee('value="M"', false)
        ->assertSee('name="product_id"', false);
});

it('shows sold out and hides the form when stock is depleted', function () {
    Product::factory()->withStock(0)->create(['slug' => 'gone', 'name' => ['en' => 'Gone']]);

    $this->get('/en/product/gone')
        ->assertOk()
        ->assertSee('Sold out')
        ->assertDontSee('Add to cart');
});

it('emits Product JSON-LD with price and availability', function () {
    Product::factory()->withStock(3)->create(['slug' => 'tee2', 'price' => 250, 'name' => ['en' => 'JsonTee']]);

    $this->get('/en/product/tee2')
        ->assertOk()
        ->assertSee('"@type":"Product"', false)
        ->assertSee('"priceCurrency":"MDL"', false)
        ->assertSee('schema.org/InStock', false);
});
