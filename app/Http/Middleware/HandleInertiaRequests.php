<?php

namespace App\Http\Middleware;

use App\Services\Cart;
use App\Settings\ShopSettings;
use App\Support\Seo;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Props shared with every Inertia response (available in all Vue pages).
     */
    public function share(Request $request): array
    {
        // Everything is a closure so Inertia evaluates it late — after the route's
        // SetLocale middleware has run (correct locale) and only for real Inertia
        // responses (no DB hits on redirects / the Filament admin).
        return [
            ...parent::share($request),

            'locale' => fn () => app()->getLocale(),

            'locales' => fn () => collect(config('locales.supported'))
                ->map(fn (string $code) => [
                    'code' => $code,
                    'name' => config('locales.names')[$code] ?? strtoupper($code),
                ])->values()->all(),

            // Current-locale UI strings (lang/{locale}/shop.php).
            'translations' => fn () => trans('shop'),

            // Common nav URLs in the current locale (no client router / Ziggy).
            'urls' => fn () => [
                'catalogue' => route('catalogue', app()->getLocale()),
                'cart' => route('cart', app()->getLocale()),
                'checkout' => route('checkout', app()->getLocale()),
                'cartAdd' => route('cart.add', app()->getLocale()),
                'cartUpdate' => route('cart.update', app()->getLocale()),
                'cartRemove' => route('cart.remove', app()->getLocale()),
                'checkoutStore' => route('checkout.store', app()->getLocale()),
            ],

            // Per-URL SEO: canonical + hreflang alternates (rendered in <SeoHead>).
            'seo' => fn () => [
                'canonical' => Seo::canonical(),
                'alternates' => Seo::alternates(),
                'xDefault' => Seo::xDefault(),
            ],

            'cartCount' => fn () => app(Cart::class)->count(),

            'flash' => [
                'status' => fn () => $request->session()->get('status'),
                'error' => fn () => $request->session()->get('error'),
            ],

            'shop' => fn () => [
                'currency' => app(ShopSettings::class)->currency,
                'instagram' => app(ShopSettings::class)->instagram_url,
                'telegram' => app(ShopSettings::class)->telegram_url,
                'phone' => app(ShopSettings::class)->contact_phone,
            ],
        ];
    }
}
