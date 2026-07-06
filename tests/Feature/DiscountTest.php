<?php

use App\Models\Product;
use App\Services\Cart;
use Inertia\Testing\AssertableInertia as Assert;

it('computes effective price, on-sale flag and percent', function () {
    $p = Product::factory()->onSale(400, 300)->create();

    expect($p->isOnSale())->toBeTrue()
        ->and($p->effectivePrice())->toBe(300.0)
        ->and($p->discountPercent())->toBe(25);

    $regular = Product::factory()->create(['price' => 400, 'sale_price' => null]);
    expect($regular->isOnSale())->toBeFalse()
        ->and($regular->effectivePrice())->toBe(400.0);
});

it('ignores a sale price that is not below the base price', function () {
    $p = Product::factory()->create(['price' => 400, 'sale_price' => 400]);

    expect($p->isOnSale())->toBeFalse();
});

it('charges the sale price in the cart', function () {
    $product = Product::factory()->onSale(400, 300)->create();
    $cart = app(Cart::class);
    $cart->add($product->id, null, 2);

    $row = $cart->detailed()->first();
    expect($row['unit_price'])->toBe(300.0)
        ->and($row['original_unit_price'])->toBe(400.0)
        ->and($cart->total())->toBe(600.0);
});

it('exposes sale info to the catalogue', function () {
    Product::factory()->onSale(400, 300)->create(['slug' => 'sale-tee']);

    $this->get('/en')->assertInertia(fn (Assert $page) => $page
        ->where('products.data.0.onSale', true)
        ->where('products.data.0.discountPercent', 25)
        ->where('products.data.0.price', fn ($price) => (float) $price === 300.0)
        ->etc()
    );
});

it('snapshots the original price on a discounted order line', function () {
    $product = Product::factory()->onSale(400, 300)->create(['slug' => 'sale-checkout', 'sizes' => []]);

    $this->withSession(['cart' => [
        $product->id.':' => ['product_id' => $product->id, 'size' => null, 'qty' => 1],
    ]])->post('/ro/checkout', ['customer_name' => 'A', 'customer_phone' => '1']);

    $this->assertDatabaseHas('order_items', [
        'product_id' => $product->id,
        'unit_price' => 300,
        'original_unit_price' => 400,
    ]);
});
