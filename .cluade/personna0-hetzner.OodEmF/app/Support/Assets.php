<?php

namespace App\Support;

class Assets
{
    /**
     * URLs of the primary latin woff2 fonts (Manrope body + Tinos wordmark)
     * to preload — they are used on every page's first paint, so preloading
     * cuts font swap time (FCP/LCP). Hashed names are resolved from the build.
     */
    public static function fontPreloads(): array
    {
        return collect([
            'assets/manrope-latin-wght-normal-*.woff2',
            'assets/tinos-latin-400-normal-*.woff2',
        ])->flatMap(fn (string $pattern) => glob(public_path('build/'.$pattern)) ?: [])
            ->map(fn (string $path) => asset('build/assets/'.basename($path)))
            ->values()
            ->all();
    }
}
