<?php

use App\Models\Product;

it('adds a product to the cart', function () {
    $product = Product::factory()->create();

    $this->post('/ro/cart', [
        'product_id' => $product->id,
        'size' => 'M',
        'qty' => 2,
    ])->assertRedirect();

    $this->assertNotEmpty(session('cart'));
    expect(session('cart'))->toHaveKey($product->id.':M')
        ->and(session('cart')[$product->id.':M']['qty'])->toBe(2);
});

it('returns JSON with the cart count for an XHR add', function () {
    $product = Product::factory()->create();

    $this->postJson('/ro/cart', [
        'product_id' => $product->id,
        'size' => 'M',
        'qty' => 2,
    ])->assertOk()->assertJson(['ok' => true, 'count' => 2]);

    expect(session('cart'))->toHaveKey($product->id.':M');
});

it('requires a size when the product defines sizes', function () {
    $product = Product::factory()->create(); // default sizes XS..XXL

    $this->post('/ro/cart', ['product_id' => $product->id, 'qty' => 1])
        ->assertSessionHasErrors('size');

    expect(session('cart'))->toBeNull();
});

it('allows adding a product with no sizes', function () {
    $product = Product::factory()->noSizes()->create();

    $this->post('/ro/cart', ['product_id' => $product->id, 'qty' => 1])
        ->assertRedirect();

    expect(session('cart'))->toHaveKey($product->id.':');
});

it('ignores submissions that trip the honeypot', function () {
    $product = Product::factory()->create();

    $this->post('/ro/cart', [
        'product_id' => $product->id,
        'size' => 'M',
        'website' => 'http://spam.example',
    ])->assertRedirect();

    expect(session('cart'))->toBeNull();
});

it('updates and removes cart lines', function () {
    $product = Product::factory()->create();
    $key = $product->id.':M';
    $line = [$key => ['product_id' => $product->id, 'size' => 'M', 'qty' => 1]];

    $this->withSession(['cart' => $line])
        ->patch('/ro/cart', ['line_key' => $key, 'qty' => 4])
        ->assertRedirect()
        ->assertSessionHas('cart', fn ($cart) => $cart[$key]['qty'] === 4);

    $this->withSession(['cart' => $line])
        ->delete('/ro/cart', ['line_key' => $key])
        ->assertRedirect()
        ->assertSessionHas('cart', fn ($cart) => $cart === []);
});
