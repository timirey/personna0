<?php

use App\Models\Product;
use App\Services\Cart;

beforeEach(function () {
    $this->cart = app(Cart::class);
});

it('adds items keyed by product and size, incrementing duplicates', function () {
    $product = Product::factory()->create();

    $this->cart->add($product->id, 'M', 2);
    expect($this->cart->count())->toBe(2);

    $this->cart->add($product->id, 'M', 1);      // same line → increments
    expect($this->cart->count())->toBe(3)
        ->and($this->cart->rawItems())->toHaveCount(1);

    $this->cart->add($product->id, 'L', 1);      // different size → new line
    expect($this->cart->rawItems())->toHaveCount(2);
});

it('updates and removes lines', function () {
    $product = Product::factory()->create();
    $this->cart->add($product->id, 'M', 1);
    $key = $product->id.':M';

    $this->cart->update($key, 5);
    expect($this->cart->count())->toBe(5);

    $this->cart->update($key, 0);                 // qty 0 removes
    expect($this->cart->isEmpty())->toBeTrue();

    $this->cart->add($product->id, 'S', 2);
    $this->cart->remove($product->id.':S');
    expect($this->cart->isEmpty())->toBeTrue();
});

it('caps a line quantity at 99', function () {
    $product = Product::factory()->create();
    $this->cart->add($product->id, null, 1);
    $this->cart->update($product->id.':', 500);

    expect($this->cart->count())->toBe(99);
});

it('hydrates detailed lines and drops inactive or missing products', function () {
    $active = Product::factory()->create(['price' => 100]);
    $inactive = Product::factory()->inactive()->create();

    $this->cart->add($active->id, 'M', 2);
    $this->cart->add($inactive->id, 'M', 1);

    $detailed = $this->cart->detailed();

    expect($detailed)->toHaveCount(1)
        ->and($detailed->first()['line_total'])->toBe(200.0)
        ->and($this->cart->total())->toBe(200.0);
});
