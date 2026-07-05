// Product gallery: swap the main image when a thumbnail is clicked.
// Event-delegated, dependency-free — the storefront ships no JS framework.
document.addEventListener('click', (event) => {
    const thumb = event.target.closest('.product__thumb');
    if (!thumb) return;

    const main = document.getElementById('product-main');
    if (!main) return;

    if (thumb.dataset.full) {
        main.src = thumb.dataset.full;
        main.srcset = `${thumb.dataset.card} 800w, ${thumb.dataset.full} 1600w`;
    }
});
