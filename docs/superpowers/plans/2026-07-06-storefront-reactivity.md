# Storefront Reactivity (Turbo + Alpine) — Implementation Plan

> Execute inline; keep the Pest suite green; commit per task; build with node@22.

**Spec:** `docs/superpowers/specs/2026-07-06-storefront-turbo-alpine-design.md`

## Global constraints
- No Vue/Inertia/Node SSR. Turbo Drive + Alpine only, storefront layout only (not admin).
- Preserve SSR/SEO/PageSpeed. Crawlers get correct per-URL HTML (Turbo is client-only).
- Visible brand = "Personna"; domain stays personna0.com.
- Build: `export PATH="/opt/homebrew/opt/node@22/bin:$PATH"; npm run build`.

## Task 1 — Deps + JS entrypoint
- `npm i @hotwired/turbo alpinejs`.
- Rewrite `resources/js/app.js`: import Turbo; import Alpine + `Alpine.start()`; register Alpine components `addToCart`, `toasts`, `gallery`; add `turbo:load` handler that syncs `document.documentElement.lang` from `meta[name=app-locale]` and restores locale-switch scroll from sessionStorage; remove old thumbnail-swap handler.
- Build; commit.

## Task 2 — CartController JSON + brand/app name
- `CartController@add`: when `$request->expectsJson()`, return `response()->json(['ok'=>true,'count'=>$cart->count(),'message'=>__('shop.cart.added')])`; on the size/validation path the framework returns 422 JSON automatically. Keep redirect+flash for non-JSON.
- `.env` + `.env.example`: `APP_NAME=Personna`. `AdminPanelProvider`: `->brandName('Personna')`.
- Test: `tests/Feature/CartControllerTest.php` — add a case posting with `Accept: application/json` asserting `assertJson(['ok'=>true,'count'=>2])`.
- Run pest; commit.

## Task 3 — Layout: meta, toasts, brand, lang links
- `layouts/store.blade.php`: add `<meta name="csrf-token">` and `<meta name="app-locale" content="{{ app()->getLocale() }}">` in head. Rename visible "Personna0"→"Personna" (header wordmark, footer wordmark, og:site_name, `$fullTitle` suffix, Organization JSON-LD name). Add a `data-turbo-permanent` toast container (`<div id="toasts" x-data="toasts()">`). Language-switcher links: keep Turbo, add `data-locale-switch` marker (for scroll preservation). Render server flash into a `data-flash` attribute the toast reads.
- Test: `tests/Feature/SeoTest.php` (or new) — layout emits `meta[name=csrf-token]`, `meta[name=app-locale]`, and Organization JSON-LD `"name":"Personna"`.
- Pest; commit.

## Task 4 — Catalogue Turbo Frame
- `store/catalogue.blade.php`: wrap grid + pager in `<turbo-frame id="catalogue-grid" data-turbo-action="advance">`. Category links + pager links get `data-turbo-frame="catalogue-grid"`.
- Test: `tests/Feature/CatalogueTest.php` — response contains `turbo-frame id="catalogue-grid"` and a category link with `data-turbo-frame`.
- Pest; commit.

## Task 5 — Product: Alpine add form + gallery slider + JSON-LD to body
- `store/product.blade.php`:
  - Move Product + BreadcrumbList JSON-LD from `@push('head')` into the body (bottom of the content).
  - Add-to-cart form → `x-data="addToCart()"` `@submit.prevent="submit"`; button bound to state (idle/loading/added), `.btn--block`, `.is-added` on success. Keep hidden honeypot + csrf.
  - Gallery: `.gallery` `x-data="gallery()"`; `.gallery__track` scroll-snap with one `.gallery__slide` per media (first `fetchpriority=high`, rest lazy); `.gallery__dots` (mobile) bound to active index; `.product__thumbs` (desktop) click → scroll slide into view. Single image → one slide, no dots.
  - Placeholder text "Personna0"→"Personna".
- Test: `tests/Feature/ProductPageTest.php` — page renders `gallery__slide` (one per image), still emits `"@type":"Product"` JSON-LD, add button present.
- Pest; commit.

## Task 6 — CSS: button success, toasts, gallery/dots
- `resources/css/app.css`: `.btn.is-added` (green bg, fixed `min-height` matching idle so no shift); `.toasts`/`.toast` (fixed, stacked, animated in/out); `.gallery__track`/`__slide`/`__dots`/dot active; hide `.product__thumbs` on mobile, hide `.gallery__dots` on desktop; frame `[aria-busy] { opacity }`.
- `product-image` placeholder already updated in Task 5. Build; commit.

## Task 7 — Verify + push
- `./vendor/bin/pest` (all green), `npm run build`, `php artisan serve` + curl smoke (green button endpoint returns JSON; catalogue has turbo-frame; product has gallery slides; `/ru` still renders; brand shows "Personna").
- Commit any fixups; `git push origin main`.

## Notes
- Alpine v3 MutationObserver auto-inits components in Turbo-swapped bodies; toast container is `data-turbo-permanent`.
- Do not add `data-turbo="false"` to locale links (language switch must stay reactive); `<html lang>` handled via meta + `turbo:load`.
