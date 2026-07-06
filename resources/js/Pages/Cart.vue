<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import SeoHead from '../components/SeoHead.vue';

defineProps({
    rows: { type: Array, default: () => [] },
    total: { type: Number, default: 0 },
    totalFormatted: { type: String, default: '' },
    title: { type: String, default: '' },
});

const urls = computed(() => usePage().props.urls);

function updateQty(lineKey, value) {
    router.patch(urls.value.cartUpdate, { line_key: lineKey, qty: Number(value) }, { preserveScroll: true });
}

function removeLine(lineKey) {
    router.delete(urls.value.cartRemove, { data: { line_key: lineKey }, preserveScroll: true });
}
</script>

<template>
    <SeoHead :title="title" noindex />

    <div class="wrap page">
        <h1 class="page__title">{{ $t('cart.title') }}</h1>

        <template v-if="!rows.length">
            <p class="empty">{{ $t('cart.empty') }}</p>
            <Link :href="urls.catalogue" class="btn">{{ $t('cart.continue') }}</Link>
        </template>

        <div v-else class="cart">
            <div class="cart__lines">
                <div v-for="row in rows" :key="row.lineKey" class="cart-row">
                    <Link :href="row.url" class="cart-row__media">
                        <img v-if="row.image" class="product-img" :src="row.image.thumb" width="88" height="110" :alt="row.name" loading="lazy" />
                        <div v-else class="product-img product-img--ph"><span>P</span></div>
                    </Link>
                    <div class="cart-row__info">
                        <Link :href="row.url" class="cart-row__name">{{ row.name }}</Link>
                        <span v-if="row.size" class="cart-row__meta">{{ $t('cart.size') }}: {{ row.size }}</span>
                        <span class="cart-row__meta">
                            <span :class="{ 'price--sale': row.onSale }">{{ row.unitFormatted }}</span>
                            <span v-if="row.onSale" class="price--old">{{ row.originalUnitFormatted }}</span>
                        </span>
                    </div>
                    <div class="cart-row__qty">
                        <input type="number" :value="row.qty" min="0" max="99"
                               :aria-label="$t('product.quantity')"
                               @change="updateQty(row.lineKey, $event.target.value)" />
                    </div>
                    <span class="cart-row__total">{{ row.lineFormatted }}</span>
                    <button class="cart-row__remove" type="button" :aria-label="$t('cart.remove')" @click="removeLine(row.lineKey)">×</button>
                </div>
            </div>

            <aside class="cart__summary">
                <div class="summary__row summary__row--total">
                    <span>{{ $t('cart.subtotal') }}</span>
                    <strong>{{ totalFormatted }}</strong>
                </div>
                <Link :href="urls.checkout" class="btn btn--block">{{ $t('cart.checkout') }}</Link>
                <Link :href="urls.catalogue" class="link-quiet">{{ $t('cart.continue') }}</Link>
            </aside>
        </div>
    </div>
</template>
