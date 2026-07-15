# Personna0 Shop — Design Spec

**Date:** 2026-07-05 · **Revised:** 2026-07-05 (max-effort review pass)
**Brand:** Personna0 · https://personna0.com · Instagram: https://www.instagram.com/personna0/
**Slogan:** "If you have to choose, choose comfort, quality, and uniqueness."
**Location:** Moldova · Default currency MDL (configurable) · Timezone Europe/Chisinau
**Repo:** `git@github.com:timirey/personna0.git`

## 1. Summary

A small, single-owner Laravel storefront + admin selling clothing (t-shirts to start, category-extensible). The public frontend is server-rendered, minimalist, tri-lingual (RO/RU/EN), and optimized aggressively for SEO and PageSpeed. The admin is a Filament v5 panel for one hardcoded user. New orders trigger a Telegram notification. No payments, no delivery, no customer accounts, no emails.

**Both the storefront and the admin are used primarily on phones** — mobile-first is a hard requirement for both, not just the storefront.

This is a **fresh build**, not a port. The existing `.cluade/personna-shop/` (Claude-web generated, Filament v3) is kept as a *reference* only — its domain patterns are reused conceptually; its Filament v3 code and single-language assumptions are rewritten.

## 2. Stack & key decisions

| Concern | Choice | Verified |
|---|---|---|
| Framework | Laravel 12 (PHP 8.3 target, min 8.2) | ✓ |
| Admin | Filament v5 (current stable v5.6.x) — needs PHP 8.2+, Laravel 11.28+, Tailwind 4.1+ | ✓ |
| Tests | **Pest 4** (Laravel 12 default runner) | ✓ |
| Frontend | Blade SSR + **bespoke hand-tuned CSS** via Vite, critical CSS inlined, near-zero JS | — |
| DB | SQLite (single-owner shop) | — |
| i18n content | `spatie/laravel-translatable` ^6 (JSON columns) + **`lara-zeus/spatie-translatable` ^2** for Filament v5 locale tabs | ✓ |
| i18n UI | Laravel lang files `lang/{ro,ru,en}` | — |
| Images | `filament/spatie-laravel-media-library-plugin` ^5 + `spatie/laravel-medialibrary` ^11 (WebP + responsive `srcset`) | ✓ |
| Settings | `filament/spatie-laravel-settings-plugin` ^5 + `spatie/laravel-settings` ^3 (typed class + Filament page) | ✓ |
| Sitemap | Lightweight custom cached route (no extra dep) | — |
| Build | Vite (minify, versioned assets) | — |

> **Correction from v1 of this spec:** the original `filament/spatie-laravel-translatable-plugin` is **officially abandoned** (Packagist redirects it to `lara-zeus/spatie-translatable`) and has **no Filament v5 release**. We use the maintained fork `lara-zeus/spatie-translatable` v2.x (supports Filament ^5). The underlying `spatie/laravel-translatable` v6 is fine and Laravel-12 compatible. Fallback if we ever want zero fork risk: build the locale tabs manually with `spatie/laravel-translatable`'s `getTranslation`/`setTranslation` — a small amount of extra code, no plugin.

**Decisions locked during brainstorming + review:**
- Ordering: cart + session checkout, **no payment**, **no delivery**, no accounts, no emails.
- **Checkout collects only: customer name + phone** (both required). Nothing else "for now." (Email/notes/address dropped.)
- Products: clothing with **categories**; per-product **sizes** (JSON, e.g. XS–XXL); image **gallery** per product (medialibrary). Colors NOT in scope (future).
- Translation depth: **UI + full product content** in RO/RU/EN.
- Locale: auto-detect from browser `Accept-Language`, cookie override, manual switcher.
- Settings editable in admin: **currency, Telegram credentials, contact & social** (Instagram/phone/email/address). No shipping, no banner.
- Auth: **exactly one user**, created directly in DB (seeder/artisan command). Login-only — in Filament v5 auth features are opt-in, so we call `->login()` and simply do NOT enable `->registration()`/`->passwordReset()`/`->emailVerification()`/`->profile()`. Those routes won't exist.
- Order statuses (minimal): **New → Completed / Cancelled**.

## 2a. Brand & design direction

From the brand photos (`.cluade/pics`): premium minimalism, warm natural light, café/editorial lifestyle. Products are white/black/navy tees with a **small serif "Personna" wordmark** on the chest (plus occasional graphic prints, e.g. lips). Nothing loud.

**Reuse the existing brand design tokens** from `.cluade/personna-shop/public/css/store.css` — they already match the photos:
- Palette: `--paper #F4F2EC`, `--paper-2 #ECEAE2`, `--ink #1C1B17`, `--ink-2 #26241B`, `--muted #8C887C`, `--line rgba(28,27,23,.14)`.
- Type: **Fraunces** (serif display / wordmark) + **Hanken Grotesk** (sans body). **Self-hosted** (local woff2, subset, `font-display: swap`, preload) — no third-party font CDN (a real PageSpeed/CWV win).
- Product imagery **4:5 portrait** aspect (matches the clothing shots); `object-fit: cover` in the grid.
- Breakpoints ~860 / 760 / 480px. Mobile-first.

Design language: serif "Personna0" wordmark, muted neutrals, generous whitespace, large editorial imagery, calm boutique feel. Adjusted for usability/mobile where it helps.

## 3. Data model

**categories**
- `id`, `name` (translatable JSON), `slug` (unique, indexed), `sort` (int), `is_active` (bool, indexed), timestamps

**products**
- `id`, `category_id` (FK, indexed)
- translatable JSON: `name`, `description`, `meta_title`, `meta_description`
- `slug` (unique, indexed; single slug shared across locales)
- `price` decimal(10,2)
- `stock` nullable unsignedInteger (null = unlimited; decrements on order when set)
- `sizes` JSON (array of size labels; empty = no size selection)
- `is_active` bool (indexed)
- images via medialibrary collection `gallery` (first = primary), WebP + responsive conversions
- timestamps
- index on `(is_active, category_id)` for catalogue queries

**orders**
- `id`, `reference` (unique, `PN-YYMMDD-XXXX`)
- `customer_name`, `customer_phone` (both required)
- `locale` (locale the order was placed in)
- `total` decimal(10,2)
- `status` string: `new` | `completed` | `cancelled` (default `new`, indexed)
- timestamps

**order_items** (immutable snapshots)
- `id`, `order_id` (FK, cascadeOnDelete), `product_id` (FK, nullOnDelete — survives product deletion)
- `product_name` (snapshot string in order's locale), `size` (nullable)
- `qty` unsignedInteger, `unit_price` decimal(10,2), `line_total` decimal(10,2)
- timestamps

**settings** (spatie `ShopSettings` group)
- `currency` (default `MDL`)
- `telegram_bot_token`, `telegram_chat_id` (nullable)
- `instagram_url`, `contact_phone`, `contact_email`, `address` (nullable)

## 4. Frontend (public storefront)

### Routing & locale
- All public routes under prefix `/{locale}` where `locale ∈ {ro, ru, en}`, guarded by a `SetLocale` middleware (validates locale, sets `app()->setLocale`, else 404).
- `GET /` → 302 to `/{detected}` (cookie > `Accept-Language` > fallback `ro`).
- Routes: catalogue (`/{locale}`), category filter, product (`/{locale}/product/{slug}`), cart, checkout, order success (`/{locale}/order/{reference}`), locale switch (preserves current page & query).
- Single shared product/category slug across locales; localized content resolved via app locale. (Per-locale slugs = future enhancement, not needed for strong SEO given hreflang.)

### Pages
- **Catalogue / home** — hero (wordmark + slogan), category filter, responsive product grid, pagination.
- **Product** — image gallery, localized name/description, size selector (first size preselected), qty, add-to-cart, sold-out state when tracked stock ≤ 0, JSON-LD.
- **Cart** — line items, qty update, remove, subtotal. Session-based (`App\Services\Cart`), keyed `productId:size`; re-hydrates from DB and drops inactive/deleted products.
- **Checkout** — name + phone only; on submit: `DB::transaction` with row-locked stock check (see §7 security), creates order + snapshot items, decrements tracked stock, clears cart, redirects to success.
- **Success** — reference + order summary; note that the shop will call the given phone.
- **Footer** — contact & social from settings, language switcher. **404 & error pages** localized and on-brand.

### SEO / PageSpeed (explicit goals)
- Per-page localized `<title>`, meta description, Open Graph, Twitter card, `<link rel="canonical">`.
- **`hreflang`** alternates for all three locales + `x-default` (→ default locale) on every page.
- **JSON-LD** structured data: `Organization` (site-wide), `Product` (offers/price/availability/brand), `BreadcrumbList`.
- Auto-generated `sitemap.xml` (all locale URLs, with alternates) + `robots.txt`.
- Responsive WebP images via medialibrary `srcset`/`sizes`, `loading="lazy"` below fold, **LCP hero/product image preloaded**, explicit width/height (zero CLS).
- **Self-hosted, preloaded, subset fonts** (no render-blocking third-party).
- Critical CSS inlined in `<head>`; rest deferred. **Near-zero JS** — cart/qty via plain form POSTs (progressive enhancement), tiny vanilla JS only for niceties (no Alpine on storefront).
- Semantic HTML5, accessible landmarks, skip-to-content, mobile-first responsive.
- Caching: config/route/view cache; per-locale catalogue product list cached (`Cache::remember`, busted on product save via observer). Cart count rendered server-side from session (pages not full-page cached, to avoid stale cart count).

## 5. Admin (Filament v5, `/admin`) — mobile-first

Primary device is a phone, so every resource is designed to work well on a small screen: keep 2–3 essential table columns visible with the rest `->toggleable()`, single-column responsive forms, touch-friendly actions, collapsible sections.

- **Single panel**, single user. Login page only (registration/reset/verification/profile not enabled → routes absent).
- **Resources:**
  - **Categories** — translatable name (locale tabs via lara-zeus), slug (auto from name), sort, active.
  - **Products** — translatable name/description/meta (locale tabs), slug, price (currency prefix from settings), stock (blank = ∞), sizes (checkbox list), gallery upload (`SpatieMediaLibraryFileUpload`, multiple + reorderable), category select, active. Mobile-friendly layout.
  - **Orders** — `canCreate()=false`. Nav badge = count of `new`. List: reference, customer, total, status badge, date (extra columns toggleable). View page shows snapshot items via a read-only relation manager. Status editable (New/Completed/Cancelled) with colored badges. Customer/phone searchable.
- **Settings page** — `filament/spatie-laravel-settings-plugin` page bound to `ShopSettings`: currency, Telegram token/chat, Instagram/phone/email/address.
- Navigation grouped ("Shop"). Shared currency-formatting helper with the frontend.

## 6. Orders → Telegram

- `Order` uses `#[ObservedBy(OrderObserver::class)]` (Laravel 11+ attribute).
- On `created`: `OrderObserver` → `App\Services\TelegramNotifier->sendNewOrder($order)`.
- Notifier reads token/chat from **settings** (not env). If blank → silently skips.
- Sends HTML-formatted message (`parse_mode=HTML`, values escaped with `e()` — correct for HTML mode): reference, items w/ size×qty, total in currency, customer name + phone.
- **Synchronous but wrapped in try/catch + `Log`** — a Telegram failure never breaks checkout. (Future: queue it.)

## 7. Security & correctness (from audit)

- **Stock race condition** (present in reference code): fix by selecting products with `lockForUpdate()` inside the checkout transaction, validating available stock before decrement, and rejecting/adjusting if insufficient. Prevents overselling on concurrent orders.
- **Order-spam / bot protection** (no payment gate → Telegram flood risk): add a **honeypot field** + **rate limiting** (`throttle`) on `POST /cart` and `POST /checkout`, plus basic phone-format validation.
- CSRF on all forms (Laravel default). Cart re-validates `size` as required + in the product's allowed set when the product defines sizes.
- Order-item snapshots make product edits/deletes safe for historical orders.
- Non-bugs noted by audit but intentionally kept: `e()` escaping in Telegram (required by HTML parse mode); `PN-YYMMDD-XXXX` reference with uniqueness loop (fine at this scale).

## 8. Testing (Pest 4)

Feature tests (TDD during implementation):
- Cart: add / update qty / remove; size required when product has sizes; inactive product dropped from `detailed()`.
- Checkout: creates order + snapshot items, decrements tracked stock, leaves untracked stock alone, clears cart, empty-cart redirects; Telegram fired with `Http::fake()`; honeypot/throttle blocks spam.
- Stock: concurrent/oversell guard rejects insufficient stock.
- Locale: `/` redirects to detected locale; cookie override wins; invalid locale 404s; app locale set by middleware.
- SEO smoke: product page emits canonical + 3 hreflang + x-default + Product JSON-LD.
- Admin: `/admin` requires auth; no registration/password-reset routes exist; order status update persists; product create/edit with translations.

## 9. Project layout / scaffolding

- Fresh Laravel 12 app scaffolded at the **repository root** (`/Users/tim/Personal/personna0`); `.cluade/` reference and `docs/` preserved alongside. (Scaffold into a temp dir, then move files in, since the root isn't empty.)
- One user provisioned via a seeder or `php artisan` command (no registration flow).
- `.env`: `APP_NAME=Personna0`, `APP_LOCALE=ro`, SQLite, `APP_URL=https://personna0.com`, `APP_TIMEZONE=Europe/Chisinau`; Telegram/currency live in settings (DB), not env.
- **Git:** init at root, remote `git@github.com:timirey/personna0.git`. Standard Laravel `.gitignore` (vendor, node_modules, .env, storage, *.sqlite, build). Commit `.cluade/` and `docs/`.

## 10. Out of scope (explicit — YAGNI)

Payments, delivery/shipping, customer accounts/registration, password reset, multiple admin users/roles, product color variants, per-region/multi-currency pricing, discount codes, stock audit trail / order timeline tables, customer email confirmations, address/notes at checkout. (Several of these were suggested by the code audit but are deliberately excluded for a simple single-owner shop.)

## 11. Reuse verdict (from audit)

| Area | Verdict |
|---|---|
| Cart service (session, `productId:size`, DB re-hydration) | **Adapt** — keep logic, add locale-aware naming |
| Checkout flow + order snapshots + reference generator | **Adapt** — add stock lock, trim to name+phone |
| `TelegramNotifier` message format + `OrderObserver` wiring | **Reuse** — creds move to settings |
| `CartController` / `CatalogueController` validation & shape | **Adapt** — add locale/category scoping |
| `store.css` design tokens (palette, fonts, 4:5, grid) | **Adapt** — reuse tokens, modularize, self-host fonts |
| Blade views/layout structure | **Rewrite** — i18n, SEO head, categories, gallery |
| Filament v3 resources/pages | **Rewrite** for v5 — translatable tabs, media, settings, mobile-first |
| Migrations / seeders | **Rewrite** — add categories, translatable JSON, minimal orders |
