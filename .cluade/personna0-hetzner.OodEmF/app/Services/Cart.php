<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Session-backed shopping cart. Lines are keyed "{productId}:{size}" so the
 * same product in two sizes are distinct lines. State lives entirely in the
 * session, so the service itself is stateless and safe to resolve fresh.
 */
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
        $items = $this->rawItems();
        $lineKey = $this->lineKey($productId, $size);
        $current = (int) ($items[$lineKey]['qty'] ?? 0);

        $items[$lineKey] = [
            'product_id' => $productId,
            'size' => $size,
            'qty' => $current + max(1, $qty),
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
            $items[$lineKey]['qty'] = min($qty, 99);
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

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * Hydrated lines with product + computed totals. Inactive or deleted
     * products are silently dropped so totals always reflect live products.
     * The localized product name is resolved by the caller (view/notifier).
     */
    public function detailed(): Collection
    {
        $items = collect($this->rawItems());
        if ($items->isEmpty()) {
            return collect();
        }

        $products = Product::query()
            ->active()
            ->with('media')
            ->whereIn('id', $items->pluck('product_id'))
            ->get()
            ->keyBy('id');

        return $items->map(function ($line, $lineKey) use ($products) {
            $product = $products->get($line['product_id']);
            if (! $product) {
                return null;
            }

            $qty = (int) $line['qty'];
            $unit = $product->effectivePrice();

            return [
                'lineKey' => $lineKey,
                'product' => $product,
                'size' => $line['size'] ?? null,
                'qty' => $qty,
                'unit_price' => $unit,
                'original_unit_price' => $product->isOnSale() ? (float) $product->price : null,
                'line_total' => round($unit * $qty, 2),
            ];
        })->filter()->values();
    }

    public function total(): float
    {
        return (float) $this->detailed()->sum('line_total');
    }

    protected function lineKey(int $productId, ?string $size): string
    {
        return $productId.':'.($size ?? '');
    }
}
