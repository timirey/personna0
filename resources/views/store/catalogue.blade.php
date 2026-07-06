@extends('layouts.store')

@section('title', __('shop.nav.shop'))
@section('meta_description', __('shop.tagline'))

@php $locale = app()->getLocale(); @endphp

@section('content')
    <section class="hero">
        <div class="wrap">
            <p class="eyebrow">Personna</p>
            <h1 class="hero__title">{{ __('shop.tagline') }}</h1>
        </div>
    </section>

    <section class="wrap catalogue">
        {{-- Category switching + pagination update this frame in place (no scroll jump);
             data-turbo-action="advance" keeps the URL shareable / back-button friendly. --}}
        <turbo-frame id="catalogue-grid" data-turbo-action="advance">
            @if ($categories->isNotEmpty())
                <nav class="filters" aria-label="{{ __('shop.catalogue.all') }}">
                    <a href="{{ route('catalogue', $locale) }}" @class(['is-active' => ! $activeCategory])>{{ __('shop.catalogue.all') }}</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('catalogue', ['locale' => $locale, 'category' => $category->slug]) }}"
                           @class(['is-active' => $activeCategory === $category->slug])>{{ $category->name }}</a>
                    @endforeach
                </nav>
            @endif

            @if ($products->isEmpty())
                <p class="empty">{{ __('shop.catalogue.empty') }}</p>
            @else
                <div class="grid">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                @if ($products->hasPages())
                    <nav class="pager" aria-label="Pagination">
                        @if ($products->onFirstPage())
                            <span class="pager__btn is-disabled" aria-hidden="true">←</span>
                        @else
                            <a class="pager__btn" href="{{ $products->previousPageUrl() }}" rel="prev">←</a>
                        @endif
                        <span class="pager__info">{{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
                        @if ($products->hasMorePages())
                            <a class="pager__btn" href="{{ $products->nextPageUrl() }}" rel="next">→</a>
                        @else
                            <span class="pager__btn is-disabled" aria-hidden="true">→</span>
                        @endif
                    </nav>
                @endif
            @endif
        </turbo-frame>
    </section>
@endsection
