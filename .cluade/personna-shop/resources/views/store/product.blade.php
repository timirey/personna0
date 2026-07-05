@extends('layouts.store')
@section('title', $product->name.' — Personna')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description ?? ''), 150))

@section('content')
<a href="{{ route('catalogue') }}" class="back-link">← Shop</a>

<div class="product">
  <div class="product-image">
    @if($product->image)
      <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
    @else
      <div class="thumb-empty big">Personna</div>
    @endif
  </div>

  <div class="product-info">
    <h1 class="product-title">{{ $product->name }}</h1>
    <div class="product-price-lg"><x-money :amount="$product->price" /></div>
    @if($product->description)
      <p class="product-desc">{{ $product->description }}</p>
    @endif

    <form method="POST" action="{{ route('cart.add') }}" class="add-form">
      @csrf
      <input type="hidden" name="product_id" value="{{ $product->id }}">

      @if(!empty($product->sizes))
        <div class="field">
          <span class="field-label">Size</span>
          <div class="sizes">
            @foreach($product->sizes as $i => $size)
              <label class="size-chip">
                <input type="radio" name="size" value="{{ $size }}" @checked($i === 0)>
                <span>{{ $size }}</span>
              </label>
            @endforeach
          </div>
          @error('size')<p class="error">{{ $message }}</p>@enderror
        </div>
      @endif

      <div class="field qty-field">
        <label class="field-label" for="qty">Quantity</label>
        <input type="number" id="qty" name="qty" value="1" min="1" max="99" class="input qty-input">
      </div>

      @if(!is_null($product->stock) && $product->stock <= 0)
        <p class="sold-out">Sold out</p>
      @else
        <button type="submit" class="btn btn-block">Add to bag</button>
      @endif
    </form>
  </div>
</div>
@endsection
