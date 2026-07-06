import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { createSSRApp, h } from 'vue';
import { resolvePage } from './lib/resolve';
import { i18n } from './lib/i18n';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        resolve: resolvePage,
        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(i18n);
        },
    }),
);
