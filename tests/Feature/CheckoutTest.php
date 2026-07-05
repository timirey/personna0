<?php

use App\Models\Order;
use App\Models\Product;
use App\Settings\ShopSettings;
use Illuminate\Support\Facades\Http;

function cartSession(Product $product, string $size = 'M', int $qty = 2): array
{
    return ['cart' => [
        $product->id.':'.$size => [
            'product_id' => $product->id,
            'size' => $size,
            'qty' => $qty,
        ],
    ]];
}

it('creates an order with snapshot items and decrements tracked stock', function () {
    ShopSettings::fake([
        'currency' => 'MDL',
        'telegram_bot_token' => '123:ABC',
        'telegram_chat_id' => '999',
    ]);
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

    $product = Product::factory()->withStock(5)->create([
        'price' => 100,
        'name' => ['ro' => 'Tricou', 'ru' => 'Футболка', 'en' => 'T-Shirt'],
    ]);

    $response = $this->withSession(cartSession($product, 'M', 2))
        ->post('/ro/checkout', [
            'customer_name' => 'Ion Popescu',
            'customer_phone' => '069123456',
        ]);

    $order = Order::first();
    expect($order)->not->toBeNull();
    $response->assertRedirect(route('order.success', ['ro', $order->reference]));

    $this->assertDatabaseHas('orders', [
        'customer_name' => 'Ion Popescu',
        'customer_phone' => '069123456',
        'locale' => 'ro',
        'total' => 200,
        'status' => 'new',
    ]);
    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_name' => 'Tricou', // snapshot in RO (order locale)
        'size' => 'M',
        'qty' => 2,
        'line_total' => 200,
    ]);
    $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 3]);
    expect(session('cart'))->toBeNull(); // cart cleared

    Http::assertSent(fn ($r) => str_contains($r['text'], $order->reference));
});

it('leaves untracked (null) stock alone', function () {
    $product = Product::factory()->unlimitedStock()->create(['price' => 50]);

    $this->withSession(cartSession($product, 'M', 3))
        ->post('/ro/checkout', ['customer_name' => 'A', 'customer_phone' => '1']);

    $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => null]);
    expect(Order::count())->toBe(1);
});

it('redirects to catalogue when the cart is empty', function () {
    $this->post('/ro/checkout', ['customer_name' => 'A', 'customer_phone' => '1'])
        ->assertRedirect(route('catalogue', 'ro'));

    expect(Order::count())->toBe(0);
});

it('rejects checkout when stock is insufficient and creates no order', function () {
    $product = Product::factory()->withStock(1)->create();

    $this->withSession(cartSession($product, 'M', 3))
        ->post('/ro/checkout', ['customer_name' => 'A', 'customer_phone' => '1'])
        ->assertSessionHasErrors('cart');

    expect(Order::count())->toBe(0);
    $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 1]); // untouched
});

it('validates that name and phone are required', function () {
    $product = Product::factory()->create();

    $this->withSession(cartSession($product))
        ->post('/ro/checkout', [])
        ->assertSessionHasErrors(['customer_name', 'customer_phone']);

    expect(Order::count())->toBe(0);
});

it('ignores checkout submissions that trip the honeypot', function () {
    $product = Product::factory()->withStock(5)->create();

    $this->withSession(cartSession($product))
        ->post('/ro/checkout', [
            'customer_name' => 'A',
            'customer_phone' => '1',
            'website' => 'spam',
        ])->assertRedirect(route('catalogue', 'ro'));

    expect(Order::count())->toBe(0);
});
