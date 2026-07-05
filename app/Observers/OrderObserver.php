<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\TelegramNotifier;

class OrderObserver
{
    public function created(Order $order): void
    {
        // Fire-and-forget; failures are logged inside the notifier and never block checkout.
        app(TelegramNotifier::class)->sendNewOrder($order);
    }
}
