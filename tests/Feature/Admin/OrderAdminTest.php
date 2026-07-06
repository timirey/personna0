<?php

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Models\Order;
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
