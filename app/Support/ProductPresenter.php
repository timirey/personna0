<?php

namespace App\Support;

use App\Models\Product;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductPresenter
{
    /** Card-level payload for catalogue grids. */
    public static function card(Product $product, string $locale): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->effectivePrice(),
            'priceFormatted' => Money::format($product->effectivePrice()),
            'onSale' => $product->isOnSale(),
            'originalPriceFormatted' => $product->isOnSale() ? Money::format($product->price) : null,
            'discountPercent' => $product->discountPercent(),
            'url' => route('product', [$locale, $product->slug]),
            'soldOut' => $product->isSoldOut(),
            'image' => static::image($product),
        ];
    }

    /** Full payload for the product page. */
    public static function detail(Product $product, string $locale): array
    {
        return array_merge(static::card($product, $locale), [
            'description' => $product->description,
            'sizes' => $product->sizes ?? [],
            'gallery' => $product->getMedia('gallery')
                ->map(fn (Media $m) => static::urls($m))
                ->values()
                ->all(),
        ]);
    }

    public static function image(Product $product): ?array
    {
        $media = $product->getFirstMedia('gallery');

        return $media ? static::urls($media) : null;
    }

    private static function urls(Media $media): array
    {
        return [
            'thumb' => $media->getUrl('thumb'),
            'card' => $media->getUrl('card'),
            'full' => $media->getUrl('full'),
        ];
    }
}
