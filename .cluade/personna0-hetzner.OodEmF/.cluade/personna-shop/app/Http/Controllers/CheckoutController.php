<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show(Cart $cart)
    {
        $rows = $cart->detailed();
        if ($rows->isEmpty()) {
            return redirect()->route('catalogue');
        }

        return view('store.checkout', [
            'rows'  => $rows,
            'total' => $cart->total(),
        ]);
    }

    public function store(Request $request, Cart $cart)
    {
        $data = $request->validate([
            'customer_name'  => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'customer_email' => ['nullable', 'email', 'max:160'],
            'address'        => ['nullable', 'string', 'max:500'],
            'notes'          => ['nullable', 'string', 'max:1000'],
        ]);

        $rows = $cart->detailed();
        if ($rows->isEmpty()) {
            return redirect()->route('catalogue');
        }

        $order = DB::transaction(function () use ($data, $rows) {
            $order = Order::create([
                'reference'      => $this->makeReference(),
                'customer_name'  => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'] ?? null,
                'address'        => $data['address'] ?? null,
                'notes'          => $data['notes'] ?? null,
                'total'          => $rows->sum('line_total'),
                'status'         => 'new',
            ]);

            foreach ($rows as $row) {
                $order->items()->create([
                    'product_id'   => $row['product']->id,
                    'product_name' => $row['product']->name,
                    'size'         => $row['size'],
                    'qty'          => $row['qty'],
                    'unit_price'   => $row['unit_price'],
                    'line_total'   => $row['line_total'],
                ]);

                if (! is_null($row['product']->stock)) {
                    $row['product']->decrement('stock', $row['qty']);
                }
            }

            return $order; // OrderObserver -> Telegram fires after create
        });

        $cart->clear();

        return redirect()->route('order.success', $order->reference);
    }

    public function success(string $reference)
    {
        $order = Order::where('reference', $reference)->with('items')->firstOrFail();

        return view('store.success', compact('order'));
    }

    protected function makeReference(): string
    {
        do {
            $reference = 'PN-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));
        } while (Order::where('reference', $reference)->exists());

        return $reference;
    }
}
