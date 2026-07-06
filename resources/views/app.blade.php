<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4f2ec">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body>
@inertia
</body>
</html>
