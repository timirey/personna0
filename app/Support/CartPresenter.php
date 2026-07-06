<?php

namespace App\Support;

use App\Models\Order;
use App\Services\Cart;

class CartPresenter
{
    /** Hydrated cart lines shaped for the Vue cart/checkout pages. */
    public static function rows(Cart $cart, string $locale): array
    {
        return $cart->detailed()->map(fn (array $row) => [
            'lineKey' => $row['lineKey'],
            'name' => $row['product']->name,
            'slug' => $row['product']->slug,
            'url' => route('product', [$locale, $row['product']->slug]),
            'size' => $row['size'],
            'qty' => $row['qty'],
            'unitFormatted' => Money::format($row['unit_price']),
            'lineFormatted' => Money::format($row['line_total']),
            'image' => ProductPresenter::image($row['product']),
        ])->all();
    }

    /** Snapshot items for the order-success page. */
    public static function orderItems(Order $order): array
    {
        return $order->items->map(fn ($item) => [
            'name' => $item->product_name,
            'size' => $item->size,
            'qty' => $item->qty,
            'lineFormatted' => Money::format($item->line_total),
        ])->all();
    }
}
