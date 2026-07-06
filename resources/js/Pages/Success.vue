<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import SeoHead from '../components/SeoHead.vue';
import OrderLine from '../components/OrderLine.vue';

defineProps({
    order: { type: Object, required: true },
    title: { type: String, default: '' },
});

const catalogueUrl = computed(() => usePage().props.urls.catalogue);
</script>

<template>
    <SeoHead :title="title" noindex />

    <div class="wrap page success-page">
        <div class="success-card">
            <h1 class="page__title">{{ $t('success.title') }}</h1>
            <p>{{ $t('success.thanks') }}</p>
            <p class="success-ref">{{ $t('success.reference') }}: <strong>{{ order.reference }}</strong></p>
            <p class="muted">{{ $t('success.contact_soon') }}</p>

            <div class="success-items">
                <OrderLine v-for="(item, i) in order.items" :key="i"
                           :name="item.name" :size="item.size" :qty="item.qty" :amount="item.lineFormatted" />
                <div class="summary__row summary__row--total">
                    <span>{{ $t('cart.subtotal') }}</span>
                    <strong>{{ order.totalFormatted }}</strong>
                </div>
            </div>

            <Link :href="catalogueUrl" class="btn">{{ $t('success.back') }}</Link>
        </div>
    </div>
</template>
