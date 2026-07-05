<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Personna')</title>
<meta name="description" content="@yield('meta_description', 'Personna — independent clothing label.')">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400&family=Hanken+Grotesk:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/store.css') }}">
</head>
<body>
@php $cartCount = app(\App\Services\Cart::class)->count(); @endphp

<header class="site-header">
  <div class="wrap header-inner">
    <a href="{{ route('catalogue') }}" class="brand">Personna</a>
    <nav class="header-nav">
      <a href="{{ route('catalogue') }}">Shop</a>
      <a href="https://www.instagram.com/personna0/" target="_blank" rel="noopener">Instagram</a>
      <a href="{{ route('cart') }}" class="cart-link">Bag@if($cartCount)<span class="cart-count">{{ $cartCount }}</span>@endif</a>
    </nav>
  </div>
</header>

<main class="wrap site-main">
  @if(session('status'))
    <div class="flash">{{ session('status') }}</div>
  @endif
  @yield('content')
</main>

<footer class="site-footer">
  <div class="wrap footer-inner">
    <span class="brand-sm">Personna</span>
    <span class="muted">© {{ date('Y') }} Personna</span>
    <a href="https://www.instagram.com/personna0/" target="_blank" rel="noopener">@personna0</a>
  </div>
</footer>
</body>
</html>
