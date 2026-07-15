<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Cart $cart)
    {
        return view('store.cart', [
            'rows'  => $cart->detailed(),
            'total' => $cart->total(),
        ]);
    }

    public function add(Request $request, Cart $cart)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'size'       => ['nullable', 'string', 'max:10'],
            'qty'        => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::where('is_active', true)->findOrFail($data['product_id']);

        // If the product defines sizes, require a valid one.
        $sizes = $product->sizes ?? [];
        if (! empty($sizes)) {
            $request->validate([
                'size' => ['required', 'in:' . implode(',', $sizes)],
            ]);
        }

        $cart->add($product->id, $data['size'] ?? null, (int) ($data['qty'] ?? 1));

        return redirect()->route('cart')->with('status', 'Added to your bag.');
    }

    public function update(Request $request, Cart $cart)
    {
        $data = $request->validate([
            'line_key' => ['required', 'string'],
            'qty'      => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cart->update($data['line_key'], (int) $data['qty']);

        return redirect()->route('cart');
    }

    public function remove(Request $request, Cart $cart)
    {
        $data = $request->validate([
            'line_key' => ['required', 'string'],
        ]);

        $cart->remove($data['line_key']);

        return redirect()->route('cart');
    }
}
