<script setup>
defineProps({
    name: { type: String, required: true },
    size: { type: String, default: null },
    qty: { type: Number, default: 1 },
    amount: { type: String, default: '' },
});
</script>

<template>
    <div class="summary__line">
        <span class="summary__item">
            <span v-if="qty > 1" class="summary__qty">{{ qty }} ×</span>
            <span>{{ name }}</span>
            <span v-if="size" class="size-badge">{{ size }}</span>
        </span>
        <span class="summary__amount">{{ amount }}</span>
    </div>
</template>
