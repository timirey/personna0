<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()->active()->orderBy('sort')->get();

        $activeCategory = $request->query('category');

        $products = Product::query()
            ->active()
            ->with(['media', 'category'])
            ->when($activeCategory, function ($query, $slug) {
                $query->whereHas('category', fn ($c) => $c->where('slug', $slug));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('store.catalogue', compact('products', 'categories', 'activeCategory'));
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->active()
            ->with(['media', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('store.product', compact('product'));
    }
}
