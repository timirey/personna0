@inject('cart', 'App\Services\Cart')
@inject('shop', 'App\Settings\ShopSettings')
@php
    $locale = app()->getLocale();
    $alternates = \App\Support\Seo::alternates();
    $ogLocales = ['ro' => 'ro_RO', 'ru' => 'ru_RU', 'en' => 'en_US'];
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? $pageTitle.' — Personna' : 'Personna';
    $metaDescription = trim($__env->yieldContent('meta_description')) ?: __('shop.tagline');
@endphp
<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4f2ec">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-locale" content="{{ $locale }}">

    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ \App\Support\Seo::canonical() }}">

    @foreach ($alternates as $loc => $url)
        <link rel="alternate" hreflang="{{ $loc }}" href="{{ $url }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ \App\Support\Seo::xDefault() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Personna">
    <meta property="og:title" content="{{ $pageTitle ?: 'Personna' }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ \App\Support\Seo::canonical() }}">
    <meta property="og:locale" content="{{ $ogLocales[$locale] ?? 'ro_RO' }}">
    <meta name="twitter:card" content="summary_large_image">
    @stack('og')

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Personna',
            'url' => url('/'.$locale),
            'sameAs' => array_values(array_filter([$shop->instagram_url])),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @stack('head')
</head>
<body>
<a class="skip-link" href="#main">{{ __('shop.nav.home') }}</a>

<header class="site-header">
    <div class="wrap site-header__inner">
        <a href="{{ route('catalogue', $locale) }}" class="wordmark" aria-label="Personna">Personna</a>

        <nav class="site-nav" aria-label="Primary">
            <a href="{{ route('catalogue', $locale) }}">{{ __('shop.nav.shop') }}</a>
            @if ($shop->instagram_url)
                <a href="{{ $shop->instagram_url }}" target="_blank" rel="noopener" data-turbo="false">Instagram</a>
            @endif
        </nav>

        <div class="site-header__actions">
            <x-language-switcher :alternates="$alternates" :current="$locale" />
            <a href="{{ route('cart', $locale) }}" class="cart-link" aria-label="{{ __('shop.nav.cart') }}">
                {{ __('shop.nav.cart') }}
                <span class="cart-count" id="cart-count" {{ $cart->count() < 1 ? 'hidden' : '' }}>{{ $cart->count() }}</span>
            </a>
        </div>
    </div>
</header>

@if (session('status'))
    <div id="server-flash" data-message="{{ session('status') }}" data-type="success" hidden></div>
    <noscript><div class="flash wrap" role="status">{{ session('status') }}</div></noscript>
@endif

<main id="main">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="wrap site-footer__inner">
        <div>
            <p class="wordmark wordmark--sm">Personna</p>
            <p class="site-footer__tagline">{{ __('shop.tagline') }}</p>
        </div>
        <div class="site-footer__contact">
            <p class="eyebrow">{{ __('shop.footer.contact') }}</p>
            @if ($shop->contact_phone)<p><a href="tel:{{ $shop->contact_phone }}">{{ $shop->contact_phone }}</a></p>@endif
            @if ($shop->contact_email)<p><a href="mailto:{{ $shop->contact_email }}">{{ $shop->contact_email }}</a></p>@endif
            @if ($shop->instagram_url)<p><a href="{{ $shop->instagram_url }}" target="_blank" rel="noopener" data-turbo="false">Instagram</a></p>@endif
            @if ($shop->address)<p>{{ $shop->address }}</p>@endif
        </div>
    </div>
    <div class="wrap site-footer__legal">
        <span>© {{ date('Y') }} Personna. {{ __('shop.footer.rights') }}</span>
    </div>
</footer>

<div id="toasts" class="toasts" x-data="toasts()" data-turbo-permanent aria-live="polite" aria-atomic="true">
    <template x-for="t in items" :key="t.id">
        <div class="toast" :class="`toast--${t.type}`" x-text="t.message" @click="dismiss(t.id)"></div>
    </template>
</div>
</body>
</html>
