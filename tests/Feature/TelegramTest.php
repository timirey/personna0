<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Settings\ShopSettings;
use Illuminate\Support\Facades\Http;

it('sends a telegram notification for a new order when configured', function () {
    ShopSettings::fake([
        'currency' => 'MDL',
        'telegram_bot_token' => '123:ABC',
        'telegram_chat_id' => '999',
    ]);
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

    $order = Order::factory()
        ->has(OrderItem::factory()->count(1), 'items')
        ->create(['customer_name' => 'Ion Popescu', 'customer_phone' => '069123456']);

    Http::assertSent(function ($request) use ($order) {
        return str_contains($request->url(), '/bot123:ABC/sendMessage')
            && $request['chat_id'] === '999'
            && str_contains($request['text'], $order->reference)
            && str_contains($request['text'], 'Ion Popescu');
    });
});

it('skips telegram when credentials are not set', function () {
    ShopSettings::fake([
        'currency' => 'MDL',
        'telegram_bot_token' => null,
        'telegram_chat_id' => null,
    ]);
    Http::fake();

    Order::factory()->has(OrderItem::factory()->count(1), 'items')->create();

    Http::assertNothingSent();
});

it('never throws when telegram fails', function () {
    ShopSettings::fake([
        'currency' => 'MDL',
        'telegram_bot_token' => '123:ABC',
        'telegram_chat_id' => '999',
    ]);
    Http::fake(['api.telegram.org/*' => Http::response('server error', 500)]);

    $order = Order::factory()->has(OrderItem::factory()->count(1), 'items')->create();

    expect($order->exists)->toBeTrue();
});
