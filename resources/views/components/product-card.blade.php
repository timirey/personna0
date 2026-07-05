@props(['product'])
<article class="card">
    <a href="{{ route('product', [app()->getLocale(), $product->slug]) }}" class="card__link">
        <div class="card__media">
            <x-product-image :product="$product" />
            @if ($product->isSoldOut())
                <span class="card__badge">{{ __('shop.product.sold_out') }}</span>
            @endif
        </div>
        <div class="card__body">
            <h3 class="card__title">{{ $product->name }}</h3>
            <p class="card__price"><x-money :amount="$product->price" /></p>
        </div>
    </a>
</article>
