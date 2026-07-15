<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const locales = computed(() => page.props.locales);
const current = computed(() => page.props.locale);
const alternates = computed(() => page.props.seo.alternates);
</script>

<template>
    <div class="lang-switcher" role="group" :aria-label="$t('language')">
        <Link
            v-for="l in locales"
            :key="l.code"
            :href="alternates[l.code]"
            rel="alternate"
            :hreflang="l.code"
            preserve-scroll
            :class="{ 'is-active': l.code === current }"
            :aria-current="l.code === current ? 'true' : null"
        >
            {{ l.code.toUpperCase() }}
        </Link>
    </div>
</template>
