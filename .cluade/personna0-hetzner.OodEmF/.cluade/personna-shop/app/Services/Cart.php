<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class Cart
{
    protected string $key = 'cart';

    /** Raw session lines keyed by "{productId}:{size}". */
    public function rawItems(): array
    {
        return session($this->key, []);
    }

    public function add(int $productId, ?string $size, int $qty = 1): void
    {
        $items   = $this->rawItems();
        $lineKey = $productId . ':' . ($size ?? '');
        $current = (int) ($items[$lineKey]['qty'] ?? 0);

        $items[$lineKey] = [
            'product_id' => $productId,
            'size'       => $size,
            'qty'        => $current + max(1, $qty),
        ];

        session([$this->key => $items]);
    }

    public function update(string $lineKey, int $qty): void
    {
        $items = $this->rawItems();
        if (! isset($items[$lineKey])) {
            return;
        }

        if ($qty <= 0) {
            unset($items[$lineKey]);
        } else {
            $items[$lineKey]['qty'] = $qty;
        }

        session([$this->key => $items]);
    }

    public function remove(string $lineKey): void
    {
        $items = $this->rawItems();
        unset($items[$lineKey]);
        session([$this->key => $items]);
    }

    public function clear(): void
    {
        session()->forget($this->key);
    }

    public function count(): int
    {
        return (int) collect($this->rawItems())->sum('qty');
    }

    /** Hydrated lines with product + computed totals (inactive/removed products dropped). */
    public function detailed(): Collection
    {
        $items = collect($this->rawItems());
        if ($items->isEmpty()) {
            return collect();
        }

        $products = Product::whereIn('id', $items->pluck('product_id'))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        return $items->map(function ($line, $lineKey) use ($products) {
            $product = $products->get($line['product_id']);
            if (! $product) {
                return null;
            }

            $qty = (int) $line['qty'];

            return [
                'lineKey'    => $lineKey,
                'product'    => $product,
                'size'       => $line['size'] ?? null,
                'qty'        => $qty,
                'unit_price' => (float) $product->price,
                'line_total' => round(((float) $product->price) * $qty, 2),
            ];
        })->filter()->values();
    }

    public function total(): float
    {
        return (float) $this->detailed()->sum('line_total');
    }
}
