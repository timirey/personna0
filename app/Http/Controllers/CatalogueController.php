<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Support\ProductPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CatalogueController extends Controller
{
    public function index(Request $request): Response
    {
        $locale = app()->getLocale();

        $categories = Category::query()->active()->orderBy('sort')->get()
            ->map(fn (Category $c) => ['name' => $c->name, 'slug' => $c->slug])
            ->values();

        $activeCategory = $request->query('category');

        $products = Product::query()
            ->active()
            ->with(['media', 'category'])
            ->when($activeCategory, function ($query, $slug) {
                $query->whereHas('category', fn ($c) => $c->where('slug', $slug));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Product $p) => ProductPresenter::card($p, $locale));

        return Inertia::render('Catalogue', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'title' => __('shop.nav.shop'),
            'description' => __('shop.tagline'),
        ]);
    }

    public function show(string $locale, string $slug): Response
    {
        $product = Product::query()
            ->active()
            ->with(['media', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        $current = app()->getLocale();
        $detail = ProductPresenter::detail($product, $current);

        return Inertia::render('Product', [
            'product' => $detail,
            'category' => $product->category
                ? ['name' => $product->category->name, 'slug' => $product->category->slug]
                : null,
            'title' => $product->name,
            'description' => Str::limit(strip_tags((string) ($product->description ?: __('shop.tagline'))), 155),
            'ogImage' => $detail['image']['card'] ?? null,
            'jsonld' => $this->jsonLd($product, $detail, $current),
        ]);
    }

    private function jsonLd(Product $product, array $detail, string $locale): array
    {
        $canonical = \App\Support\Seo::canonical();

        $crumbs = collect([
            ['name' => __('shop.nav.shop'), 'item' => route('catalogue', $locale)],
        ]);
        if ($product->category) {
            $crumbs->push([
                'name' => $product->category->name,
                'item' => route('catalogue', ['locale' => $locale, 'category' => $product->category->slug]),
            ]);
        }
        $crumbs->push(['name' => $product->name, 'item' => $canonical]);

        return [
            array_filter([
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->name,
                'description' => strip_tags((string) $product->description),
                'sku' => 'PN-'.$product->id,
                'image' => $detail['image']['card'] ?? null,
                'brand' => ['@type' => 'Brand', 'name' => 'Personna'],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => number_format((float) $product->price, 2, '.', ''),
                    'priceCurrency' => \App\Support\Money::currency(),
                    'availability' => $product->isSoldOut()
                        ? 'https://schema.org/OutOfStock'
                        : 'https://schema.org/InStock',
                    'url' => $canonical,
                ],
            ]),
            [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $crumbs
                    ->map(fn ($c, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $c['name'], 'item' => $c['item']])
                    ->all(),
            ],
        ];
    }
}
