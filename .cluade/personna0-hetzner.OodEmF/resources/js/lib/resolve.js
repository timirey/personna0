import Layout from '../Layout.vue';

// Lazy page imports → Vite code-splits each page into its own chunk, so a route
// only ships its page's JS (less unused JavaScript). Inertia awaits the resolver
// on both the client and SSR. The persistent AppLayout keeps header/footer
// mounted across visits (no flicker).
const pages = import.meta.glob('../Pages/**/*.vue');

export async function resolvePage(name) {
    const importer = pages[`../Pages/${name}.vue`];

    if (!importer) {
        throw new Error(`Inertia page not found: ${name}`);
    }

    const page = await importer();
    page.default.layout = page.default.layout ?? Layout;

    return page;
}
