<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    public function show(Cart $cart): View
    {
        return view('store.cart', [
            'rows' => $cart->detailed(),
            'total' => $cart->total(),
        ]);
    }

    public function add(Request $request, Cart $cart): RedirectResponse|JsonResponse
    {
        // Honeypot: bots fill hidden fields. Silently ignore.
        if (filled($request->input('website'))) {
            return $request->expectsJson()
                ? response()->json(['ok' => true, 'count' => $cart->count()])
                : back();
        }

        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'size' => ['nullable', 'string', 'max:16'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::query()->active()->findOrFail($data['product_id']);

        $sizes = $product->sizes ?? [];
        if (! empty($sizes)) {
            $request->validate(['size' => ['required', Rule::in($sizes)]]);
        }

        $cart->add($product->id, $data['size'] ?? null, $data['qty'] ?? 1);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'count' => $cart->count(),
                'message' => __('shop.cart.added'),
            ]);
        }

        return back()->with('status', __('shop.cart.added'));
    }

    public function update(Request $request, Cart $cart): RedirectResponse
    {
        $data = $request->validate([
            'line_key' => ['required', 'string'],
            'qty' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cart->update($data['line_key'], $data['qty']);

        return back();
    }

    public function remove(Request $request, Cart $cart): RedirectResponse
    {
        $data = $request->validate([
            'line_key' => ['required', 'string'],
        ]);

        $cart->remove($data['line_key']);

        return back();
    }
}
