<?php

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('lets the owner edit an order status, details and notes from the panel', function () {
    $order = Order::factory()->create([
        'status' => OrderStatus::New,
        'customer_name' => 'Ion',
    ]);

    Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
        ->fillForm([
            'status' => 'in_progress',
            'customer_name' => 'Ion Popescu',
            'admin_notes' => 'Called the client, ships tomorrow.',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'in_progress',
        'customer_name' => 'Ion Popescu',
        'admin_notes' => 'Called the client, ships tomorrow.',
    ]);
});

it('lets the owner edit order items and total', function () {
    $order = Order::factory()
        ->has(OrderItem::factory()->state(['product_name' => 'Tee', 'size' => 'M', 'qty' => 1, 'unit_price' => 100, 'line_total' => 100]), 'items')
        ->create(['total' => 100]);

    Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
        ->fillForm([
            'total' => 250,
            'items' => [[
                'product_name' => 'Tee XL', 'size' => 'XL', 'qty' => 2, 'unit_price' => 125, 'line_total' => 250,
            ]],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('orders', ['id' => $order->id, 'total' => 250]);
    $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'product_name' => 'Tee XL', 'qty' => 2]);
});
