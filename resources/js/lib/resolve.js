import Layout from '../Layout.vue';

// Eager glob keeps SSR + client resolution simple for a small page set, and
// applies the persistent AppLayout so the header/footer never re-mount (no flicker).
const pages = import.meta.glob('../Pages/**/*.vue', { eager: true });

export function resolvePage(name) {
    const page = pages[`../Pages/${name}.vue`];

    if (!page) {
        throw new Error(`Inertia page not found: ${name}`);
    }

    page.default.layout = page.default.layout ?? Layout;

    return page;
}
