<script setup>
import { computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import SeoHead from '../components/SeoHead.vue';

defineProps({
    rows: { type: Array, default: () => [] },
    total: { type: Number, default: 0 },
    totalFormatted: { type: String, default: '' },
    title: { type: String, default: '' },
});

const urls = computed(() => usePage().props.urls);

const form = useForm({
    customer_name: '',
    customer_phone: '',
    website: '',
});

function submit() {
    form.post(urls.value.checkoutStore);
}
</script>

<template>
    <SeoHead :title="title" noindex />

    <div class="wrap page">
        <h1 class="page__title">{{ $t('checkout.title') }}</h1>

        <div class="checkout">
            <form class="checkout__form" @submit.prevent="submit">
                <input v-model="form.website" type="text" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true" />

                <p v-if="form.errors.cart" class="error error--block">{{ form.errors.cart }}</p>

                <div class="field">
                    <label for="customer_name">{{ $t('checkout.name') }}</label>
                    <input id="customer_name" v-model="form.customer_name" type="text" required maxlength="120"
                           autocomplete="name" :placeholder="$t('checkout.name_ph')" />
                    <p v-if="form.errors.customer_name" class="error">{{ form.errors.customer_name }}</p>
                </div>

                <div class="field">
                    <label for="customer_phone">{{ $t('checkout.phone') }}</label>
                    <input id="customer_phone" v-model="form.customer_phone" type="tel" required maxlength="40"
                           autocomplete="tel" :placeholder="$t('checkout.phone_ph')" />
                    <p v-if="form.errors.customer_phone" class="error">{{ form.errors.customer_phone }}</p>
                </div>

                <button type="submit" class="btn btn--block" :disabled="form.processing">{{ $t('checkout.place_order') }}</button>
                <Link :href="urls.cart" class="link-quiet">{{ $t('checkout.back') }}</Link>
            </form>

            <aside class="checkout__summary">
                <h2 class="eyebrow">{{ $t('checkout.summary') }}</h2>
                <div v-for="row in rows" :key="row.lineKey" class="summary__line">
                    <span>{{ row.name }}<template v-if="row.size"> · {{ row.size }}</template> × {{ row.qty }}</span>
                    <span>{{ row.lineFormatted }}</span>
                </div>
                <div class="summary__row summary__row--total">
                    <span>{{ $t('cart.subtotal') }}</span>
                    <strong>{{ totalFormatted }}</strong>
                </div>
            </aside>
        </div>
    </div>
</template>
