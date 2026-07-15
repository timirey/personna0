<?php

namespace App\Support;

use App\Settings\ShopSettings;
use Illuminate\Support\Facades\Storage;

class Hero
{
    /**
     * Resolve the homepage hero into { url, width, height } so the <img> can
     * carry explicit dimensions (no CLS, passes Lighthouse "explicit width/
     * height"). Falls back to the optimized default WebP.
     */
    public static function resolve(): array
    {
        $value = app(ShopSettings::class)->hero_image;

        // Absolute URL — can't measure, serve as-is.
        if ($value && str_starts_with($value, 'http')) {
            return ['url' => $value, 'width' => null, 'height' => null];
        }

        // Rooted path in /public (e.g. the default /images/hero.webp).
        if ($value && str_starts_with($value, '/')) {
            return static::fromFile(public_path(ltrim($value, '/')), $value);
        }

        // Uploaded file on the public disk.
        if ($value && Storage::disk('public')->exists($value)) {
            return static::fromFile(
                Storage::disk('public')->path($value),
                Storage::disk('public')->url($value),
            );
        }

        return static::fromFile(public_path('images/hero.webp'), '/images/hero.webp');
    }

    private static function fromFile(string $path, string $url): array
    {
        $size = is_file($path) ? @getimagesize($path) : false;

        return [
            'url' => $url,
            'width' => $size[0] ?? null,
            'height' => $size[1] ?? null,
        ];
    }
}
