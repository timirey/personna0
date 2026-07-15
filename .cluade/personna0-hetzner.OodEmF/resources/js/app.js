import { createSSRApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePage } from './lib/resolve';
import { i18n } from './lib/i18n';

createInertiaApp({
    resolve: resolvePage,
    setup({ el, App, props, plugin }) {
        createSSRApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#1c1b17', // brand ink loader bar
    },
});
