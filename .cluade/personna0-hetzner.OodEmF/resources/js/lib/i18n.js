import { usePage } from '@inertiajs/vue3';

// Dot-notation lookup into the current-locale shop.php strings shared by the
// HandleInertiaRequests middleware. Usage in templates: $t('nav.cart').
export function trans(key, replace = {}) {
    const messages = usePage().props.translations ?? {};
    let value = key.split('.').reduce((carry, part) => (carry != null ? carry[part] : undefined), messages);

    if (value == null) {
        return key;
    }

    for (const [token, replacement] of Object.entries(replace)) {
        value = value.replaceAll(`:${token}`, replacement);
    }

    return value;
}

export const i18n = {
    install(app) {
        app.config.globalProperties.$t = trans;
    },
};
