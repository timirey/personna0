import { reactive } from 'vue';

// Tiny client-side toast store (used for add-to-cart success + errors).
export const toastState = reactive({ items: [], seq: 0 });

export function toast(message, type = 'info') {
    if (!message) return;
    const id = ++toastState.seq;
    toastState.items.push({ id, message, type });
    setTimeout(() => dismiss(id), 3000);
}

export function dismiss(id) {
    toastState.items = toastState.items.filter((item) => item.id !== id);
}
