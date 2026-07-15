@extends('layouts.store')
@section('title', 'Your bag — Personna')

@section('content')
<h1 class="page-title">Your bag</h1>

@if($rows->isEmpty())
  <p class="empty">Your bag is empty. <a href="{{ route('catalogue') }}">Browse the shop →</a></p>
@else
  <div class="cart">
    <div class="cart-list">
      @foreach($rows as $row)
        <div class="cart-row">
          <div class="cart-thumb">
            @if($row['product']->image)
              <img src="{{ asset('storage/'.$row['product']->image) }}" alt="{{ $row['product']->name }}">
            @else
              <div class="thumb-empty sm">P</div>
            @endif
          </div>

          <div class="cart-detail">
            <a class="cart-name" href="{{ route('product', $row['product']) }}">{{ $row['product']->name }}</a>
            @if($row['size'])<span class="cart-size">Size {{ $row['size'] }}</span>@endif
            <span class="cart-unit"><x-money :amount="$row['unit_price']" /> each</span>
          </div>

          <form method="POST" action="{{ route('cart.update') }}" class="cart-qty">
            @csrf @method('PATCH')
            <input type="hidden" name="line_key" value="{{ $row['lineKey'] }}">
            <input type="number" name="qty" value="{{ $row['qty'] }}" min="0" max="99"
                   class="input qty-input" onchange="this.form.submit()">
          </form>

          <div class="cart-line"><x-money :amount="$row['line_total']" /></div>

          <form method="POST" action="{{ route('cart.remove') }}" class="cart-remove">
            @csrf @method('DELETE')
            <input type="hidden" name="line_key" value="{{ $row['lineKey'] }}">
            <button type="submit" aria-label="Remove item">&times;</button>
          </form>
        </div>
      @endforeach
    </div>

    <aside class="cart-summary">
      <div class="summary-row"><span>Subtotal</span><span><x-money :amount="$total" /></span></div>
      <p class="summary-note">Shipping is arranged after you order.</p>
      <a href="{{ route('checkout') }}" class="btn btn-block">Checkout</a>
      <a href="{{ route('catalogue') }}" class="link-back">Continue shopping</a>
    </aside>
  </div>
@endif
@endsection
