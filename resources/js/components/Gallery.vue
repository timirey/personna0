<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    images: { type: Array, default: () => [] },
    alt: { type: String, default: '' },
});

const active = ref(0);
const track = ref(null);

onMounted(() => {
    if (!track.value || props.images.length < 2) return;
    const slides = Array.from(track.value.children);
    const observer = new IntersectionObserver(
        (entries) => entries.forEach((entry) => {
            if (entry.isIntersecting) active.value = slides.indexOf(entry.target);
        }),
        { root: track.value, threshold: 0.6 },
    );
    slides.forEach((slide) => observer.observe(slide));
});

function go(i) {
    const slide = track.value?.children[i];
    if (slide) track.value.scrollTo({ left: slide.offsetLeft, behavior: 'smooth' });
}
</script>

<template>
    <div v-if="images.length" class="product__gallery">
        <div ref="track" class="gallery__track">
            <div v-for="(img, i) in images" :key="i" class="gallery__slide">
                <img
                    :src="img.full"
                    :srcset="`${img.card} 800w, ${img.full} 1600w`"
                    sizes="(max-width: 860px) 100vw, 620px"
                    width="800"
                    height="1000"
                    :alt="alt"
                    :fetchpriority="i === 0 ? 'high' : null"
                    :loading="i === 0 ? 'eager' : 'lazy'"
                    decoding="async"
                />
            </div>
        </div>

        <div v-if="images.length > 1" class="gallery__dots" aria-hidden="true">
            <button
                v-for="(img, i) in images"
                :key="i"
                type="button"
                class="gallery__dot"
                :class="{ 'is-active': active === i }"
                @click="go(i)"
            ></button>
        </div>

        <div v-if="images.length > 1" class="product__thumbs">
            <img
                v-for="(img, i) in images"
                :key="i"
                :src="img.thumb"
                width="80"
                height="100"
                loading="lazy"
                :alt="alt"
                class="product__thumb"
                :class="{ 'is-active': active === i }"
                @click="go(i)"
            />
        </div>
    </div>

    <div v-else class="product-img product-img--ph"><span>Personna</span></div>
</template>
