<script setup>
import { computed, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import LanguageSwitcher from './components/LanguageSwitcher.vue';
import Toasts from './components/Toasts.vue';

const page = usePage();
const shop = computed(() => page.props.shop);
const urls = computed(() => page.props.urls);
const cartCount = computed(() => page.props.cartCount);
const locale = computed(() => page.props.locale);
const year = new Date().getFullYear();

// Turbo/Inertia only swap the body — keep <html lang> correct on every visit
// (notably after a language switch).
watch(
    locale,
    (value) => {
        if (typeof document !== 'undefined') {
            document.documentElement.lang = value;
        }
    },
    { immediate: true },
);
</script>

<template>
    <a class="skip-link" href="#main">{{ $t('nav.home') }}</a>

    <header class="site-header">
        <div class="wrap site-header__inner">
            <Link :href="urls.catalogue" class="wordmark" aria-label="Personna">Personna</Link>

            <nav class="site-nav" aria-label="Primary">
                <Link :href="urls.catalogue">{{ $t('nav.shop') }}</Link>
                <a v-if="shop.instagram" :href="shop.instagram" target="_blank" rel="noopener">Instagram</a>
            </nav>

            <div class="site-header__actions">
                <LanguageSwitcher />
                <Link :href="urls.cart" class="cart-link" :aria-label="$t('nav.cart')">
                    {{ $t('nav.cart') }}
                    <span v-if="cartCount > 0" class="cart-count">{{ cartCount }}</span>
                </Link>
            </div>
        </div>
    </header>

    <main id="main">
        <slot />
    </main>

    <footer class="site-footer">
        <div class="wrap site-footer__inner">
            <div>
                <p class="wordmark wordmark--sm">Personna</p>
                <p class="site-footer__tagline">{{ $t('tagline') }}</p>
            </div>
            <div class="site-footer__contact">
                <p class="eyebrow">{{ $t('footer.contact') }}</p>
                <p v-if="shop.phone"><a :href="`tel:${shop.phone}`">{{ shop.phone }}</a></p>
                <p v-if="shop.email"><a :href="`mailto:${shop.email}`">{{ shop.email }}</a></p>
                <p v-if="shop.instagram"><a :href="shop.instagram" target="_blank" rel="noopener">Instagram</a></p>
                <p v-if="shop.address">{{ shop.address }}</p>
            </div>
        </div>
        <div class="wrap site-footer__legal">
            <span>© {{ year }} Personna. {{ $t('footer.rights') }}</span>
        </div>
    </footer>

    <Toasts />
</template>
