<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4f2ec">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @foreach (\App\Support\Assets::fontPreloads() as $font)
        <link rel="preload" as="font" type="font/woff2" href="{{ $font }}" crossorigin>
    @endforeach

    {{-- Inline the storefront CSS so it isn't a render-blocking request (FCP/LCP). --}}
    <style>{!! Vite::content('resources/css/app.css') !!}</style>

    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body>
@inertia
</body>
</html>
