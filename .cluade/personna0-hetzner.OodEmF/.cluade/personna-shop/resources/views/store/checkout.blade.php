@extends('layouts.store')
@section('title', 'Checkout — Personna')

@section('content')
<h1 class="page-title">Checkout</h1>

<div class="checkout">
  <form method="POST" action="{{ route('checkout.store') }}" class="checkout-form">
    @csrf
    <div class="field">
      <label class="field-label" for="customer_name">Name *</label>
      <input class="input" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
      @error('customer_name')<p class="error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
      <label class="field-label" for="customer_phone">Phone *</label>
      <input class="input" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
      @error('customer_phone')<p class="error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
      <label class="field-label" for="customer_email">Email</label>
      <input class="input" type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}">
      @error('customer_email')<p class="error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
      <label class="field-label" for="address">Delivery address</label>
      <textarea class="input" id="address" name="address" rows="3">{{ old('address') }}</textarea>
      @error('address')<p class="error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
      <label class="field-label" for="notes">Notes</label>
      <textarea class="input" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
      @error('notes')<p class="error">{{ $message }}</p>@enderror
    </div>

    <button type="submit" class="btn btn-block">Place order</button>
    <p class="checkout-note">No account needed — we'll contact you to confirm.</p>
  </form>

  <aside class="checkout-summary">
    <h2 class="summary-title">Order</h2>
    @foreach($rows as $row)
      <div class="summary-item">
        <span>{{ $row['product']->name }}@if($row['size']) · {{ $row['size'] }}@endif × {{ $row['qty'] }}</span>
        <span><x-money :amount="$row['line_total']" /></span>
      </div>
    @endforeach
    <div class="summary-total"><span>Total</span><span><x-money :amount="$total" /></span></div>
  </aside>
</div>
@endsection
