@extends('layouts.store')
@section('title', 'Personna — Shop')

@section('content')
<section class="hero-min">
  <p class="eyebrow">Independent clothing label</p>
  <h1 class="display">Personna</h1>
  <p class="hero-sub">Considered pieces, made in small runs.</p>
</section>

<section class="catalogue">
  @if($products->isEmpty())
    <p class="empty">No products yet — add some in the admin panel.</p>
  @else
    <div class="product-grid">
      @foreach($products as $product)
        <a class="product-card" href="{{ route('product', $product) }}">
          <div class="thumb">
            @if($product->image)
              <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" loading="lazy">
            @else
              <div class="thumb-empty">Personna</div>
            @endif
          </div>
          <div class="product-meta">
            <span class="product-name">{{ $product->name }}</span>
            <span class="product-price"><x-money :amount="$product->price" /></span>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</section>
@endsection
