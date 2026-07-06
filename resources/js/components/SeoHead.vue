<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    ogImage: { type: String, default: null },
    noindex: { type: Boolean, default: false },
});

const page = usePage();
const seo = computed(() => page.props.seo);
const locale = computed(() => page.props.locale);
const ogLocales = { ro: 'ro_RO', ru: 'ru_RU', en: 'en_US' };

const fullTitle = computed(() => (props.title ? `${props.title} — Personna` : 'Personna'));
</script>

<template>
    <Head :title="fullTitle">
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
        <meta v-if="ogImage" head-key="og:image" property="og:image" :content="ogImage" />
        <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
    </Head>
</template>
