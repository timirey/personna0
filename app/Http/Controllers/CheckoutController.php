<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Services\Cart;
use App\Support\CartPresenter;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function show(Cart $cart): Response|RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('catalogue', app()->getLocale());
        }

        return Inertia::render('Checkout', [
            'rows' => CartPresenter::rows($cart, app()->getLocale()),
            'total' => $cart->total(),
            'totalFormatted' => Money::format($cart->total()),
            'title' => __('shop.checkout.title'),
        ]);
    }

    public function store(Request $request, Cart $cart): RedirectResponse
    {
        // Honeypot
        if (filled($request->input('website'))) {
            return redirect()->route('catalogue', app()->getLocale());
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:40'],
        ]);

        if ($cart->isEmpty()) {
            return redirect()->route('catalogue', app()->getLocale());
        }

        $locale = app()->getLocale();

        $order = DB::transaction(function () use ($data, $cart, $locale) {
            $rows = $cart->detailed();

            $locked = Product::query()
                ->whereIn('id', $rows->pluck('product.id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($rows as $row) {
                $product = $locked->get($row['product']->id);
                if ($product && ! is_null($product->stock) && $product->stock < $row['qty']) {
                    throw ValidationException::withMessages([
                        'cart' => __('shop.cart.insufficient', [
                            'name' => $product->getTranslation('name', $locale),
                        ]),
                    ]);
                }
            }

            $order = Order::create([
                'reference' => $this->makeReference(),
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'locale' => $locale,
                'total' => $rows->sum('line_total'),
                'status' => OrderStatus::New,
            ]);

            foreach ($rows as $row) {
                $product = $row['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->getTranslation('name', $locale),
                    'size' => $row['size'],
                    'qty' => $row['qty'],
                    'unit_price' => $row['unit_price'],
                    'line_total' => $row['line_total'],
                ]);

                if (! is_null($product->stock)) {
                    $locked->get($product->id)->decrement('stock', $row['qty']);
                }
            }

            return $order; // OrderObserver → Telegram fires after create
        });

        $cart->clear();

        return redirect()->route('order.success', [$locale, $order->reference]);
    }

    public function success(string $locale, string $reference): Response
    {
        $order = Order::where('reference', $reference)->with('items')->firstOrFail();

        return Inertia::render('Success', [
            'order' => [
                'reference' => $order->reference,
                'totalFormatted' => Money::format($order->total),
                'items' => CartPresenter::orderItems($order),
            ],
            'title' => __('shop.success.title'),
        ]);
    }

    protected function makeReference(): string
    {
        do {
            $reference = 'PN-'.Str::upper(Str::random(5));
        } while (Order::where('reference', $reference)->exists());

        return $reference;
    }
}
