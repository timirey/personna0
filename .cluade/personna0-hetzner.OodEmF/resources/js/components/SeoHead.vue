<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    ogImage: { type: String, default: null },
    preloadImage: { type: String, default: null },
    preloadSrcset: { type: String, default: null },
    preloadSizes: { type: String, default: null },
    noindex: { type: Boolean, default: false },
});

const page = usePage();
const seo = computed(() => page.props.seo);
const locale = computed(() => page.props.locale);
const ogLocales = { ro: 'ro_RO', ru: 'ru_RU', en: 'en_US' };

const fullTitle = computed(() => (props.title ? `${props.title} — Personna` : 'Personna'));

// og:image must be absolute — derive the origin from the (absolute) canonical.
const origin = computed(() => {
    try {
        return new URL(seo.value.canonical).origin;
    } catch {
        return '';
    }
});
const absolute = (url) => (!url || url.startsWith('http') ? url : origin.value + url);
const ogImageAbs = computed(() => absolute(props.ogImage));
</script>

<template>
    <Head :title="fullTitle">
        <link v-if="preloadImage" head-key="preload-lcp" rel="preload" as="image" :href="preloadImage"
              :imagesrcset="preloadSrcset" :imagesizes="preloadSizes" fetchpriority="high" />
        <meta head-key="robots" name="robots" :content="noindex ? 'noindex' : 'index, follow'" />
        <meta head-key="description" name="description" :content="description" />
        <link head-key="canonical" rel="canonical" :href="seo.canonical" />
        <link
            v-for="(url, code) in seo.alternates"
            :key="code"
            :head-key="`hreflang-${code}`"
            rel="alternate"
            :hreflang="code"
            :href="url"
        />
        <link head-key="hreflang-x-default" rel="alternate" hreflang="x-default" :href="seo.xDefault" />

        <meta head-key="og:type" property="og:type" content="website" />
        <meta head-key="og:site_name" property="og:site_name" content="Personna" />
        <meta head-key="og:title" property="og:title" :content="title || 'Personna'" />
        <meta head-key="og:description" property="og:description" :content="description" />
        <meta head-key="og:url" property="og:url" :content="seo.canonical" />
        <meta head-key="og:locale" property="og:locale" :content="ogLocales[locale] || 'ro_RO'" />
        <meta v-if="ogImageAbs" head-key="og:image" property="og:image" :content="ogImageAbs" />
        <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
    </Head>
</template>
