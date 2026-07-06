<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    product: { type: Object, required: true },
});
</script>

<template>
    <article class="card">
        <Link :href="product.url" class="card__link">
            <div class="card__media">
                <img
                    v-if="product.image"
                    class="product-img"
                    :src="product.image.card"
                    :srcset="`${product.image.thumb} 400w, ${product.image.card} 800w, ${product.image.full} 1600w`"
                    sizes="(max-width: 760px) 50vw, 360px"
                    width="800"
                    height="1000"
                    :alt="product.name"
                    loading="lazy"
                    decoding="async"
                />
                <div v-else class="product-img product-img--ph"><span>Personna</span></div>
                <span v-if="product.soldOut" class="card__badge">{{ $t('product.sold_out') }}</span>
            </div>
            <div class="card__body">
                <h3 class="card__title">{{ product.name }}</h3>
                <p class="card__price">{{ product.priceFormatted }}</p>
            </div>
        </Link>
    </article>
</template>
