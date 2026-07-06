<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import SeoHead from '../components/SeoHead.vue';
import ProductCard from '../components/ProductCard.vue';

const props = defineProps({
    products: { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    activeCategory: { type: String, default: null },
    heroImage: { type: String, default: null },
    title: { type: String, default: '' },
    description: { type: String, default: '' },
});

const catalogueUrl = computed(() => usePage().props.urls.catalogue);
const categoryHref = (slug) => (slug ? `${catalogueUrl.value}?category=${slug}` : catalogueUrl.value);
</script>

<template>
    <SeoHead :title="title" :description="description" />

    <section class="hero" :class="{ 'hero--split': heroImage }">
        <div class="wrap hero__inner">
            <div class="hero__text">
                <p class="eyebrow">Personna</p>
                <h1 class="hero__title">{{ $t('tagline') }}</h1>
            </div>
            <div v-if="heroImage" class="hero__media">
                <img :src="heroImage" alt="Personna" fetchpriority="high" decoding="async" />
            </div>
        </div>
    </section>

    <section class="wrap catalogue">
        <nav v-if="categories.length" class="filters" :aria-label="$t('catalogue.all')">
            <Link :href="categoryHref(null)" preserve-scroll :only="['products', 'activeCategory']"
                  :class="{ 'is-active': !activeCategory }">{{ $t('catalogue.all') }}</Link>
            <Link v-for="c in categories" :key="c.slug" :href="categoryHref(c.slug)" preserve-scroll
                  :only="['products', 'activeCategory']" :class="{ 'is-active': activeCategory === c.slug }">
                {{ c.name }}
            </Link>
        </nav>

        <p v-if="!products.data.length" class="empty">{{ $t('catalogue.empty') }}</p>

        <template v-else>
            <div class="grid">
                <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
            </div>

            <nav v-if="products.last_page > 1" class="pager" aria-label="Pagination">
                <Link v-if="products.prev_page_url" class="pager__btn" :href="products.prev_page_url"
                      preserve-scroll :only="['products', 'activeCategory']" rel="prev">←</Link>
                <span v-else class="pager__btn is-disabled" aria-hidden="true">←</span>

                <span class="pager__info">{{ products.current_page }} / {{ products.last_page }}</span>

                <Link v-if="products.next_page_url" class="pager__btn" :href="products.next_page_url"
                      preserve-scroll :only="['products', 'activeCategory']" rel="next">→</Link>
                <span v-else class="pager__btn is-disabled" aria-hidden="true">→</span>
            </nav>
        </template>
    </section>
</template>
