@extends('layouts.store')

@section('title', __('shop.cart.title'))

@php $locale = app()->getLocale(); @endphp

@section('content')
    <div class="wrap page">
        <h1 class="page__title">{{ __('shop.cart.title') }}</h1>

        @if ($rows->isEmpty())
            <p class="empty">{{ __('shop.cart.empty') }}</p>
            <a href="{{ route('catalogue', $locale) }}" class="btn">{{ __('shop.cart.continue') }}</a>
        @else
            <div class="cart">
                <div class="cart__lines">
                    @foreach ($rows as $row)
                        <div class="cart-row">
                            <a href="{{ route('product', [$locale, $row['product']->slug]) }}" class="cart-row__media">
                                <x-product-image :product="$row['product']" sizes="88px" />
                            </a>
                            <div class="cart-row__info">
                                <a href="{{ route('product', [$locale, $row['product']->slug]) }}" class="cart-row__name">{{ $row['product']->name }}</a>
                                @if ($row['size'])
                                    <span class="cart-row__meta">{{ __('shop.cart.size') }}: {{ $row['size'] }}</span>
                                @endif
                                <span class="cart-row__meta"><x-money :amount="$row['unit_price']" /></span>
                            </div>
                            <form method="POST" action="{{ route('cart.update', $locale) }}" class="cart-row__qty">
                                @csrf @method('PATCH')
                                <input type="hidden" name="line_key" value="{{ $row['lineKey'] }}">
                                <input type="number" name="qty" value="{{ $row['qty'] }}" min="0" max="99"
                                       onchange="this.form.submit()" aria-label="{{ __('shop.product.quantity') }}">
                                <noscript><button type="submit">↺</button></noscript>
                            </form>
                            <span class="cart-row__total"><x-money :amount="$row['line_total']" /></span>
                            <form method="POST" action="{{ route('cart.remove', $locale) }}" class="cart-row__remove">
                                @csrf @method('DELETE')
                                <input type="hidden" name="line_key" value="{{ $row['lineKey'] }}">
                                <button type="submit" aria-label="{{ __('shop.cart.remove') }}">×</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <aside class="cart__summary">
                    <div class="summary__row summary__row--total">
                        <span>{{ __('shop.cart.subtotal') }}</span>
                        <strong><x-money :amount="$total" /></strong>
                    </div>
                    <a href="{{ route('checkout', $locale) }}" class="btn btn--block">{{ __('shop.cart.checkout') }}</a>
                    <a href="{{ route('catalogue', $locale) }}" class="link-quiet">{{ __('shop.cart.continue') }}</a>
                </aside>
            </div>
        @endif
    </div>
@endsection
