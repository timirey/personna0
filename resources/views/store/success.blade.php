@extends('layouts.store')

@section('title', __('shop.success.title'))

@php $locale = app()->getLocale(); @endphp

@push('head')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    <div class="wrap page success-page">
        <div class="success-card">
            <h1 class="page__title">{{ __('shop.success.title') }}</h1>
            <p>{{ __('shop.success.thanks') }}</p>
            <p class="success-ref">{{ __('shop.success.reference') }}: <strong>{{ $order->reference }}</strong></p>
            <p class="muted">{{ __('shop.success.contact_soon') }}</p>

            <div class="success-items">
                @foreach ($order->items as $item)
                    <div class="summary__line">
                        <span>{{ $item->product_name }}@if ($item->size) · {{ $item->size }}@endif × {{ $item->qty }}</span>
                        <span><x-money :amount="$item->line_total" /></span>
                    </div>
                @endforeach
                <div class="summary__row summary__row--total">
                    <span>{{ __('shop.cart.subtotal') }}</span>
                    <strong><x-money :amount="$order->total" /></strong>
                </div>
            </div>

            <a href="{{ route('catalogue', $locale) }}" class="btn">{{ __('shop.success.back') }}</a>
        </div>
    </div>
@endsection
