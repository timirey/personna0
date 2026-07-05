<?php

use App\Models\Product;

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
