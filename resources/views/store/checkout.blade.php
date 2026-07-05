@extends('layouts.store')

@section('title', __('shop.checkout.title'))

@php $locale = app()->getLocale(); @endphp

@section('content')
    <div class="wrap page">
        <h1 class="page__title">{{ __('shop.checkout.title') }}</h1>

        <div class="checkout">
            <form method="POST" action="{{ route('checkout.store', $locale) }}" class="checkout__form">
                @csrf
                <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">

                @error('cart')<p class="error error--block">{{ $message }}</p>@enderror

                <div class="field">
                    <label for="customer_name">{{ __('shop.checkout.name') }}</label>
                    <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name') }}"
                           required maxlength="120" autocomplete="name" placeholder="{{ __('shop.checkout.name_ph') }}">
                    @error('customer_name')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="customer_phone">{{ __('shop.checkout.phone') }}</label>
                    <input id="customer_phone" type="tel" name="customer_phone" value="{{ old('customer_phone') }}"
                           required maxlength="40" autocomplete="tel" placeholder="{{ __('shop.checkout.phone_ph') }}">
                    @error('customer_phone')<p class="error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn btn--block">{{ __('shop.checkout.place_order') }}</button>
                <a href="{{ route('cart', $locale) }}" class="link-quiet">{{ __('shop.checkout.back') }}</a>
            </form>

            <aside class="checkout__summary">
                <h2 class="eyebrow">{{ __('shop.checkout.summary') }}</h2>
                @foreach ($rows as $row)
                    <div class="summary__line">
                        <span>{{ $row['product']->name }}@if ($row['size']) · {{ $row['size'] }}@endif × {{ $row['qty'] }}</span>
                        <span><x-money :amount="$row['line_total']" /></span>
                    </div>
                @endforeach
                <div class="summary__row summary__row--total">
                    <span>{{ __('shop.cart.subtotal') }}</span>
                    <strong><x-money :amount="$total" /></strong>
                </div>
            </aside>
        </div>
    </div>
@endsection
