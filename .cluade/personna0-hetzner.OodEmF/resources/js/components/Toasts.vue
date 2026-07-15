<script setup>
import { toastState, dismiss } from '../lib/toasts';
</script>

<template>
    <div class="toasts" aria-live="polite" aria-atomic="true">
        <TransitionGroup name="toast">
            <div
                v-for="t in toastState.items"
                :key="t.id"
                class="toast"
                :class="`toast--${t.type}`"
                @click="dismiss(t.id)"
            >
                {{ t.message }}
            </div>
        </TransitionGroup>
    </div>
</template>
