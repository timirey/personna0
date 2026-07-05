import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// Storefront assets only. The Filament admin ships its own precompiled
// Tailwind/Alpine bundle, so we keep the public site deliberately lean:
// hand-written CSS + self-hosted variable fonts (Fraunces + Manrope),
// no Tailwind, near-zero JS — tuned for PageSpeed / Core Web Vitals.
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
