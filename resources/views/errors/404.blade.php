@extends('layouts.store')

@section('title', '404')

@php $locale = app()->getLocale(); @endphp

@section('content')
    <div class="wrap page error-page">
        <p class="eyebrow">404</p>
        <h1 class="hero__title">—</h1>
        <a href="{{ route('catalogue', $locale) }}" class="btn">{{ __('shop.success.back') }}</a>
    </div>
@endsection
