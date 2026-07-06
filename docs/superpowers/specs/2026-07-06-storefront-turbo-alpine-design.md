# Storefront Reactivity — Turbo Drive + Alpine (Design Spec)

**Date:** 2026-07-06

## Goal

Make the Blade storefront feel reactive/smooth without a JS-framework rewrite, while preserving the existing SSR, SEO, and PageSpeed advantages. Specifically:

1. **Add-to-cart feedback** — clicking "Add to cart" turns the button green and shows "Added successfully ✓" for 3 seconds (no page reload); header cart count updates live.
2. **Category switching** — updates the product grid in place with **no scroll-to-top jump**; URL still changes (shareable, back-button).
3. **Everything smooth**, including **language switching**, via reactive navigation (no jarring full reloads).
4. **Mobile-first product gallery** — images are a **swipeable slider** on phones (the primary device), with dot indicators; thumbnails on larger screens.
5. Keep server-rendered SEO (canonical, hreflang, JSON-LD), multilingual URLs, and low JS weight.

## Approach (decided during brainstorming)

**Hotwire Turbo (Drive, global) + Alpine.js**, on top of the existing Blade views. Chosen over (a) full Inertia + Vue 3 + SSR — rejected: much larger JS bundle, a persistent Node SSR process to run, and re-implementing the SEO head in Vue; and (b) Turbo Frames-only — rejected: user wants all navigation (incl. language) reactive, not just the category filter.

**Non-goals / explicitly out of scope:** Vue, Inertia, any Node SSR process, changes to the Filament admin (Turbo/Alpine load only on the storefront layout), changes to CSS/fonts/data model.

**Bundle impact (accepted):** storefront JS grows from ~0.2 KB to ~45 KB gz (`@hotwired/turbo` ~35 KB + `alpinejs` ~15 KB). Still far below an Inertia+Vue bundle; CSS/fonts unchanged. **Crawlers are unaffected** — Turbo is client-only; each URL still returns correct full server HTML.

## Design

### 1. Dependencies & entrypoint
- `npm i @hotwired/turbo alpinejs` (build with node@22, as documented).
- `resources/js/app.js`: import Turbo (auto-enables Drive), import + start Alpine, register the small components (add-to-cart, toasts) and the `turbo:load` lang-sync handler.

### 2. Turbo Drive (global) + SEO correctness
- Turbo intercepts same-origin links/forms → AJAX `<body>` swap + progress bar; `<head>` provisional elements (title, meta description, canonical, hreflang, og) are refreshed by Turbo on each visit.
- **Per-page JSON-LD (Product, BreadcrumbList) moves from `<head>` into `<body>`** (valid per Google) so it swaps cleanly with the body and never stales/duplicates. Site-wide **Organization JSON-LD stays in `<head>`** (constant).
- Add `<meta name="csrf-token" content="{{ csrf_token() }}">` to the layout head for `fetch` POSTs.
- Same-origin non-Turbo targets (none public today; the admin is a separate app) would use `data-turbo="false"` if ever linked from the storefront.

### 3. Reactive language switching
- Language-switcher links are **normal Turbo visits** (no `data-turbo="false"`). The body swaps to the target-locale HTML; head updates automatically.
- `<html lang>` gap (Turbo never touches `<html>` attributes) is closed by: server renders `<meta name="app-locale" content="{{ app()->getLocale() }}">`; a `turbo:load` listener sets `document.documentElement.lang` from that meta on every visit.
- **Scroll preserved across a language switch**: switcher links carry a marker; a click handler stores `window.scrollY` in `sessionStorage`, and the `turbo:load` handler restores it (then clears the marker) so the user stays in place while the page re-renders in the new language.

### 4. Category filter — Turbo Frame (in-place, no scroll jump)
- Wrap the product grid **and** pager in `<turbo-frame id="catalogue-grid">` inside `catalogue.blade.php`.
- Category filter links and pager links target that frame: `data-turbo-frame="catalogue-grid" data-turbo-action="advance"`. Result: only the grid re-renders, scroll position is preserved, and the URL advances to `/{locale}?category=…` (shareable, back-button, correct canonical on full loads).
- The same Blade renders the frame on both full page loads and frame responses — Turbo extracts the matching frame automatically.

### 5. Add-to-cart — Alpine + fetch (green button)
- The product page add form becomes an Alpine component (`x-data`), `@submit.prevent`.
- On submit: `fetch` POST to `route('cart.add')` with `FormData` + `X-CSRF-TOKEN` header + `Accept: application/json`. States: `idle → loading → added`.
  - **Success**: button gets `.is-added` (green) and label "Added successfully ✓" for 3s, then reverts to idle; the header cart-count badge is updated live from the JSON `count` (dispatch a `cart:updated` event the header listens for; on next navigation the server-rendered badge already matches).
  - **Error** (422 / stock): show an error **toast** with the server message.
- **Full-width on mobile**: the button stays `.btn--block` (full width of the content column) on phones. The `idle` and `is-added` states use a fixed min-height and identical box, so swapping the label to "Added successfully ✓" causes **no layout shift**.
- `CartController@add` returns JSON (`{ ok, count, message }`) when `expectsJson()`, and keeps the existing redirect-back-with-flash path for non-JS requests (progressive enhancement: form works with JS disabled).
- Honeypot + throttle unchanged.

### 6. Product gallery slider (mobile-first swipe)
Replaces the current main-image + thumbnail-swap markup (and retires the delegated thumbnail-swap script in `app.js`).
- **Track**: a horizontal `.gallery__track` of full slides using **CSS scroll-snap** (`scroll-snap-type: x mandatory`, `overflow-x:auto`, slides `flex:0 0 100%; scroll-snap-align:center`). This gives **native touch swipe** on phones with zero JS for the core interaction.
- **Dots** (mobile): a small Alpine component tracks the active slide via an `IntersectionObserver` on the slides and renders/marks dot indicators; dots are tappable (scroll the track to that slide).
- **Thumbnails** (≥ desktop breakpoint): the existing thumbnail strip remains; clicking a thumb `scrollIntoView`s the matching slide (smooth) and Alpine reflects the active thumb. On mobile the thumb strip is hidden in favor of swipe + dots.
- Single-image products render one slide, no dots. LCP image keeps `fetchpriority="high"`; other slides `loading="lazy"`. 4:5 aspect preserved (no CLS).

### 7. Toasts
- A small Alpine toast system in a `data-turbo-permanent` container (persists across visits). API: `window.dispatchEvent(new CustomEvent('toast', { detail: { message, type } }))`; auto-dismiss after 3s; dismissible.
- Used for: add-to-cart errors; and a fallback for any server `session('status')`/error flash (rendered into a data attribute the toast reads on load) so no-JS flashes and JS toasts look consistent.
- The green button is the **primary** success cue for add-to-cart; toasts cover errors and misc messages.

### 8. Files touched
- `package.json`, `resources/js/app.js` — deps + init (Turbo, Alpine, components: add-to-cart, toasts, gallery, lang-sync); remove the old delegated thumbnail-swap handler.
- `resources/css/app.css` — `.is-added` button-success state (fixed min-height), toast styles, gallery slider + dots, optional frame `aria-busy` cue.
- `resources/views/layouts/store.blade.php` — csrf + app-locale meta; toast container; Organization JSON-LD stays; language links keep Turbo (scroll marker).
- `resources/views/store/catalogue.blade.php` — `<turbo-frame id="catalogue-grid">` around grid+pager; frame-targeted category/pager links.
- `resources/views/store/product.blade.php` — Alpine add-to-cart form (full-width button); gallery slider (scroll-snap track + dots + desktop thumbs); move Product/Breadcrumb JSON-LD to body.
- `app/Http/Controllers/CartController.php` — `add()` returns JSON for XHR.

### 9. Testing
- **Server-side (Pest, headless):**
  - `CartController@add` returns JSON `{ok:true, count, message}` for `Accept: application/json`; still redirects + flashes for normal requests.
  - Catalogue renders `<turbo-frame id="catalogue-grid">` and category links carry `data-turbo-frame`.
  - Product page still emits Product JSON-LD (now in body) — existing SEO tests stay green.
  - Product page renders one gallery slide per image (`.gallery__slide`).
  - Layout emits `meta[name=csrf-token]` and `meta[name=app-locale]`.
  - Full existing suite (58) stays green.
- **Client behavior** (green full-width button, no-scroll filter, reactive lang switch, mobile swipe gallery): verified via successful `npm run build` + live `curl`/browser smoke test. A Pest v4 browser test is a possible later addition but not part of this scope.

## Risks / notes
- JS bundle grows to ~45 KB gz (accepted trade-off; still no Node process, SEO/CSS/fonts intact).
- Turbo head-merge + `<html lang>` handled as above; the main correctness watch-item is JSON-LD, resolved by moving per-page JSON-LD to the body.
- Alpine v3's MutationObserver auto-initializes components in Turbo-swapped bodies, so no manual re-init is needed; the toast container is `data-turbo-permanent`.
