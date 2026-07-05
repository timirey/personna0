@props([
    'product',
    'sizes' => '(max-width: 760px) 50vw, 360px',
    'priority' => false,
])
@php $media = $product->getFirstMedia('gallery'); @endphp
@if ($media)
    <img class="product-img"
         src="{{ $media->getUrl('card') }}"
         srcset="{{ $media->getUrl('thumb') }} 400w, {{ $media->getUrl('card') }} 800w, {{ $media->getUrl('full') }} 1600w"
         sizes="{{ $sizes }}"
         width="800" height="1000"
         alt="{{ $product->name }}"
         decoding="async"
         loading="{{ $priority ? 'eager' : 'lazy' }}"
         @if ($priority) fetchpriority="high" @endif>
@else
    <div class="product-img product-img--ph" role="img" aria-label="{{ $product->name }}">
        <span>Personna0</span>
    </div>
@endif
