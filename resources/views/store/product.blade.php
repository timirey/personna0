@extends('layouts.store')

@php
    $locale = app()->getLocale();
    $media = $product->getMedia('gallery');
    $ogImage = $product->getFirstMediaUrl('gallery', 'card');
    $canonical = \App\Support\Seo::canonical();
    $currency = \App\Support\Money::currency();
@endphp

@section('title', $product->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description ?: __('shop.tagline')), 155))

@push('og')
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
        <meta property="og:type" content="product">
    @endif
@endpush

@push('head')
    <script type="application/ld+json">
        {!! json_encode(array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags((string) $product->description),
            'sku' => 'PN-'.$product->id,
            'image' => $ogImage ?: null,
            'brand' => ['@type' => 'Brand', 'name' => 'Personna0'],
            'offers' => [
                '@type' => 'Offer',
                'price' => number_format((float) $product->price, 2, '.', ''),
                'priceCurrency' => $currency,
                'availability' => $product->isSoldOut() ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
                'url' => $canonical,
            ],
        ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect([
                ['name' => __('shop.nav.shop'), 'item' => route('catalogue', $locale)],
                ...($product->category ? [['name' => $product->category->name, 'item' => route('catalogue', ['locale' => $locale, 'category' => $product->category->slug])]] : []),
                ['name' => $product->name, 'item' => $canonical],
            ])->map(fn ($c, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $c['name'], 'item' => $c['item']])->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <div class="wrap product">
        <nav class="crumbs" aria-label="Breadcrumb">
            <a href="{{ route('catalogue', $locale) }}">{{ __('shop.nav.shop') }}</a>
            <span aria-hidden="true">/</span>
            <span>{{ $product->name }}</span>
        </nav>

        <div class="product__grid">
            <div class="product__gallery">
                @if ($media->isNotEmpty())
                    @php $first = $media->first(); @endphp
                    <img class="product__main" id="product-main"
                         src="{{ $first->getUrl('full') }}"
                         srcset="{{ $first->getUrl('card') }} 800w, {{ $first->getUrl('full') }} 1600w"
                         sizes="(max-width: 860px) 100vw, 620px"
                         width="800" height="1000" alt="{{ $product->name }}"
                         fetchpriority="high" decoding="async">
                    @if ($media->count() > 1)
                        <div class="product__thumbs">
                            @foreach ($media as $m)
                                <img src="{{ $m->getUrl('thumb') }}" width="80" height="100" loading="lazy"
                                     alt="{{ $product->name }}" class="product__thumb"
                                     data-full="{{ $m->getUrl('full') }}" data-card="{{ $m->getUrl('card') }}">
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="product-img product-img--ph"><span>Personna0</span></div>
                @endif
            </div>

            <div class="product__info">
                <h1 class="product__title">{{ $product->name }}</h1>
                <p class="product__price"><x-money :amount="$product->price" /></p>

                @if ($product->description)
                    <div class="product__desc">{!! nl2br(e($product->description)) !!}</div>
                @endif

                @if ($product->isSoldOut())
                    <p class="soldout">{{ __('shop.product.sold_out') }}</p>
                @else
                    <form method="POST" action="{{ route('cart.add', $locale) }}" class="add-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">

                        @if (! empty($product->sizes))
                            <fieldset class="sizes">
                                <legend>{{ __('shop.product.size') }}</legend>
                                <div class="sizes__options">
                                    @foreach ($product->sizes as $i => $size)
                                        <label class="size">
                                            <input type="radio" name="size" value="{{ $size }}" @checked($i === 0) required>
                                            <span>{{ $size }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                            @error('size')<p class="error">{{ $message }}</p>@enderror
                        @endif

                        <div class="qty">
                            <label for="qty">{{ __('shop.product.quantity') }}</label>
                            <input id="qty" type="number" name="qty" value="1" min="1" max="99" inputmode="numeric">
                        </div>

                        <button type="submit" class="btn btn--block">{{ __('shop.product.add_to_cart') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
