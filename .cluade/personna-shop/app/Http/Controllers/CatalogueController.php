<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CatalogueController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->latest()
            ->get();

        return view('store.catalogue', compact('products'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        return view('store.product', compact('product'));
    }
}
