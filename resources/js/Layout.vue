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
                <div class="socials">
                    <a v-if="shop.instagram" :href="shop.instagram" target="_blank" rel="noopener" class="social" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="5" />
                            <circle cx="12" cy="12" r="4" />
                            <circle cx="17.5" cy="6.5" r="1.1" fill="currentColor" stroke="none" />
                        </svg>
                        <span>Instagram</span>
                    </a>
                    <a v-if="shop.telegram" :href="shop.telegram" target="_blank" rel="noopener" class="social" aria-label="Telegram">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
                            <path d="M21.94 4.64 18.9 19a1 1 0 0 1-1.61.6l-4.1-3.02-2.03 1.96a.55.55 0 0 1-.94-.35l-.28-4.06 7.4-6.68c.28-.25-.06-.4-.42-.16l-9.14 5.76-3.94-1.23c-.79-.24-.8-.79.18-1.17l15.4-5.94c.66-.25 1.24.16 1.02 1.13z" />
                        </svg>
                        <span>Telegram</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="wrap site-footer__legal">
            <span>© {{ year }} Personna. {{ $t('footer.rights') }}</span>
        </div>
    </footer>

    <Toasts />
</template>
