<script setup>
import { ref, computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import SeoHead from '../components/SeoHead.vue';
import JsonLd from '../components/JsonLd.vue';
import Gallery from '../components/Gallery.vue';
import { trans } from '../lib/i18n';
import { toast } from '../lib/toasts';

const props = defineProps({
    product: { type: Object, required: true },
    category: { type: Object, default: null },
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    ogImage: { type: String, default: null },
    jsonld: { type: Array, default: () => [] },
});

const page = usePage();
const catalogueUrl = computed(() => page.props.urls.catalogue);
const cartAddUrl = computed(() => page.props.urls.cartAdd);

const cooling = ref(false); // briefly disable after add to prevent double-submit
const form = useForm({
    product_id: props.product.id,
    size: props.product.sizes?.[0] ?? null,
    qty: 1,
    website: '',
});

function submit() {
    cooling.value = true;
    form.post(cartAddUrl.value, {
        preserveScroll: true,
        onSuccess: () => {
            toast(trans('cart.added'), 'success');
            setTimeout(() => { cooling.value = false; }, 1500);
        },
        onError: (errors) => {
            cooling.value = false;
            toast(Object.values(errors)[0] ?? 'Error', 'error');
        },
    });
}
</script>

<template>
    <SeoHead :title="title" :description="description" :og-image="ogImage" />
    <JsonLd :data="jsonld" />

    <div class="wrap product">
        <nav class="crumbs" aria-label="Breadcrumb">
            <Link :href="catalogueUrl">{{ $t('nav.shop') }}</Link>
            <span aria-hidden="true">/</span>
            <span>{{ product.name }}</span>
        </nav>

        <div class="product__grid">
            <Gallery :images="product.gallery" :alt="product.name" />

            <div class="product__info">
                <h1 class="product__title">{{ product.name }}</h1>
                <p class="product__price">{{ product.priceFormatted }}</p>

                <div v-if="product.description" class="product__desc" style="white-space: pre-line">{{ product.description }}</div>

                <p v-if="product.soldOut" class="soldout">{{ $t('product.sold_out') }}</p>

                <form v-else class="add-form" @submit.prevent="submit">
                    <input v-model="form.website" type="text" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true" />

                    <fieldset v-if="product.sizes.length" class="sizes">
                        <legend>{{ $t('product.size') }}</legend>
                        <div class="sizes__options">
                            <label v-for="size in product.sizes" :key="size" class="size">
                                <input v-model="form.size" type="radio" :value="size" required />
                                <span>{{ size }}</span>
                            </label>
                        </div>
                        <p v-if="form.errors.size" class="error">{{ form.errors.size }}</p>
                    </fieldset>

                    <div class="qty">
                        <label for="qty">{{ $t('product.quantity') }}</label>
                        <input id="qty" v-model.number="form.qty" type="number" min="1" max="99" inputmode="numeric" />
                    </div>

                    <button type="submit" class="btn btn--block" :disabled="cooling || form.processing">
                        {{ $t('product.add_to_cart') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
