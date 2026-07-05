@extends('layouts.store')
@section('title', 'Order placed — Personna')

@section('content')
<section class="success">
  <p class="eyebrow">Thank you</p>
  <h1 class="display sm">Order placed</h1>
  <p class="success-ref">Reference <strong>{{ $order->reference }}</strong></p>
  <p class="success-msg">We've received your order and will contact you on the number you provided to confirm and arrange delivery.</p>

  <div class="success-card">
    @foreach($order->items as $item)
      <div class="summary-item">
        <span>{{ $item->product_name }}@if($item->size) · {{ $item->size }}@endif × {{ $item->qty }}</span>
        <span><x-money :amount="$item->line_total" /></span>
      </div>
    @endforeach
    <div class="summary-total"><span>Total</span><span><x-money :amount="$order->total" /></span></div>
  </div>

  <a href="{{ route('catalogue') }}" class="btn">Back to shop</a>
</section>
@endsection
