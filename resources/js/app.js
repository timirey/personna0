// Storefront reactivity: Hotwire Turbo (smooth SSR navigation) + Alpine
// (add-to-cart feedback, toasts, mobile gallery). No SPA framework; SEO/SSR intact.
import '@hotwired/turbo';
import Alpine from 'alpinejs';

// ---- Toasts: window.dispatchEvent(new CustomEvent('toast', {detail:{message,type}})) ----
Alpine.data('toasts', () => ({
    items: [],
    seq: 0,
    init() {
        window.addEventListener('toast', (e) => this.push(e.detail || {}));
    },
    push({ message, type = 'info' }) {
        if (!message) return;
        const id = ++this.seq;
        this.items.push({ id, message, type });
        setTimeout(() => this.dismiss(id), 3000);
    },
    dismiss(id) {
        this.items = this.items.filter((t) => t.id !== id);
    },
}));

// ---- Add-to-cart: fetch POST → green button for 3s + live cart count ----
Alpine.data('addToCart', () => ({
    state: 'idle', // idle | loading | added
    async submit(event) {
        const form = event.target;
        this.state = 'loading';
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                const msg = data.message || Object.values(data.errors || {})[0]?.[0];
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: msg, type: 'error' } }));
                this.state = 'idle';
                return;
            }

            this.updateCount(data.count);
            this.state = 'added';
            setTimeout(() => { this.state = 'idle'; }, 3000);
        } catch (e) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Network error', type: 'error' } }));
            this.state = 'idle';
        }
    },
    updateCount(count) {
        const badge = document.getElementById('cart-count');
        if (!badge || typeof count !== 'number') return;
        badge.textContent = count;
        badge.hidden = count < 1;
    },
}));

// ---- Gallery: CSS scroll-snap slider; native swipe on mobile, dots + desktop thumbs ----
Alpine.data('gallery', () => ({
    active: 0,
    init() {
        const track = this.$refs.track;
        if (!track || track.children.length < 2) return;
        const slides = Array.from(track.children);
        const io = new IntersectionObserver(
            (entries) => entries.forEach((entry) => {
                if (entry.isIntersecting) this.active = slides.indexOf(entry.target);
            }),
            { root: track, threshold: 0.6 },
        );
        slides.forEach((s) => io.observe(s));
    },
    go(i) {
        const slide = this.$refs.track?.children[i];
        if (slide) this.$refs.track.scrollTo({ left: slide.offsetLeft, behavior: 'smooth' });
    },
}));

window.Alpine = Alpine;
Alpine.start();

// ---- Turbo correctness across reactive visits ----
document.addEventListener('turbo:load', () => {
    // Turbo swaps <body> but never <html> attributes — keep lang correct.
    const locale = document.querySelector('meta[name="app-locale"]')?.content;
    if (locale) document.documentElement.lang = locale;

    // Restore scroll after a language switch (saved just before the visit).
    const y = sessionStorage.getItem('localeScrollY');
    if (y !== null) {
        window.scrollTo(0, parseInt(y, 10));
        sessionStorage.removeItem('localeScrollY');
    }

    // Surface a server-side flash (no-JS paths) as a toast.
    const flash = document.getElementById('server-flash');
    if (flash?.dataset.message) {
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { message: flash.dataset.message, type: flash.dataset.type || 'success' },
        }));
    }
});

// Preserve scroll position when switching language.
document.addEventListener('click', (e) => {
    if (e.target.closest('[data-locale-switch]')) {
        sessionStorage.setItem('localeScrollY', String(window.scrollY));
    }
});
