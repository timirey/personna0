# Personna0 Shop — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans (inline) to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** A tri-lingual (RO/RU/EN), SEO- and mobile-optimized Laravel 12 storefront with a Filament v5 single-user admin, session cart + name/phone checkout, and Telegram order notifications.

**Architecture:** Server-rendered Blade storefront (locale-prefixed routes, bespoke CSS via Vite, near-zero JS) over Eloquent models with Spatie-translatable JSON content and Spatie-medialibrary images. Filament v5 admin at `/admin` (login-only) manages Categories/Products/Orders/Settings. Orders fire a synchronous, failure-safe Telegram notification via a model observer.

**Tech Stack:** Laravel 12, PHP 8.4, Filament v5.6, Pest 4, SQLite, Vite; spatie/laravel-translatable ^6, spatie/laravel-medialibrary ^11 (+ filament plugin ^5), spatie/laravel-settings ^3 (+ filament plugin ^5), lara-zeus/spatie-translatable ^2.

## Global Constraints

- Locales: `ro`, `ru`, `en`. Default/fallback: `ro`. All public URLs are `/{locale}/...`.
- Currency: from settings (`ShopSettings::currency`, default `MDL`). Never hardcode.
- Auth: exactly one user, created in DB. Filament panel enables `->login()` ONLY — no registration/passwordReset/emailVerification/profile.
- Checkout collects ONLY `customer_name` + `customer_phone` (both required).
- Order statuses: `new` | `completed` | `cancelled` (default `new`).
- Money stored `decimal(10,2)`; displayed `"<number_format(2)> <CURRENCY>"`.
- Order items are immutable snapshots (name in the order's locale).
- Timezone `Europe/Chisinau`. App name `Personna0`.
- Design tokens (reuse): `--paper #F4F2EC`, `--paper-2 #ECEAE2`, `--ink #1C1B17`, `--ink-2 #26241B`, `--muted #8C887C`, `--line rgba(28,27,23,.14)`; fonts Fraunces (serif) + Hanken Grotesk (sans), self-hosted; product images 4:5.
- TDD with Pest; commit after each green task.

---

## Phase 0 — Scaffold & repo

### Task 0.1: Scaffold Laravel 12 into the non-empty repo root
**Files:** whole app tree; preserve existing `.cluade/`, `.idea/`, `docs/`, `CLAUDE.md`.
- [ ] Create app in temp: `laravel new personna0-tmp --no-interaction` in scratchpad (Pest, no starter kit). If the installer prompts, use flags: `--pest`, database `sqlite`.
- [ ] Move app files into repo root without clobbering existing dirs (move contents of temp, then merge). Keep existing `.cluade/`, `docs/`, `CLAUDE.md`.
- [ ] `composer install` if needed; `php artisan --version` → Laravel 12.x.
- [ ] Configure `.env`: `APP_NAME=Personna0`, `APP_URL=https://personna0.com`, `APP_LOCALE=ro`, `APP_FALLBACK_LOCALE=ro`, `APP_TIMEZONE=Europe/Chisinau`, `DB_CONNECTION=sqlite`; `touch database/database.sqlite`; `php artisan key:generate`; `php artisan migrate`.
- [ ] Verify Pest: `./vendor/bin/pest` → default tests pass.

### Task 0.2: Git init + first commit
- [ ] `git init`; ensure Laravel `.gitignore` (add `/database/*.sqlite`). 
- [ ] `git remote add origin git@github.com:timirey/personna0.git`.
- [ ] `git add -A && git commit` (initial scaffold). Do NOT push yet (ask user before first push).

---

## Phase 1 — Packages & config

### Task 1.1: Install Filament v5 + Spatie stack
- [ ] `composer require filament/filament:"^5.0"`
- [ ] `php artisan filament:install --panels` (creates `app/Providers/Filament/AdminPanelProvider.php`).
- [ ] `composer require spatie/laravel-translatable:"^6.0" lara-zeus/spatie-translatable:"^2.0"`
- [ ] `composer require filament/spatie-laravel-media-library-plugin:"^5.0"` (pulls spatie/laravel-medialibrary ^11); publish + run its migration: `php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag=medialibrary-migrations`.
- [ ] `composer require filament/spatie-laravel-settings-plugin:"^5.0"` (pulls spatie/laravel-settings ^3); `php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag=migrations`.
- [ ] `php artisan migrate`.
- [ ] Commit: "chore: install filament v5 + spatie stack".

### Task 1.2: Vite storefront entrypoints + self-hosted fonts
**Files:** `vite.config.js`, `resources/css/app.css` (storefront), `resources/js/app.js` (tiny), `resources/fonts/*` (Fraunces + Hanken Grotesk woff2 subsets), `public/build/*` (generated).
- [ ] Add storefront CSS/JS inputs to `vite.config.js` (laravel-vite-plugin).
- [ ] Download self-hosted woff2 for Fraunces + Hanken Grotesk into `resources/fonts/` (latin + latin-ext + cyrillic subsets — Cyrillic needed for RU). Declare `@font-face` with `font-display: swap`.
- [ ] `npm install && npm run build` succeeds.
- [ ] Commit.

---

## Phase 2 — Data model

### Task 2.1: Settings class + migration
**Files:** `app/Settings/ShopSettings.php`, `database/settings/2026_..._create_shop_settings.php`, config `config/settings.php` (register class), `tests/Feature/SettingsTest.php`.
- [ ] Test: `ShopSettings` resolves with defaults (`currency==='MDL'`, telegram/contact nullable).
- [ ] Create `ShopSettings extends Settings` with props: `string $currency`, `?string $telegram_bot_token`, `?string $telegram_chat_id`, `?string $instagram_url`, `?string $contact_phone`, `?string $contact_email`, `?string $address`; `group()==='shop'`.
- [ ] Settings migration seeds defaults (currency `MDL`, rest null).
- [ ] Register in `config/settings.php` `settings` array.
- [ ] `php artisan migrate`; test passes. Commit.

### Task 2.2: Migrations (categories, products, orders, order_items)
**Files:** four migrations in `database/migrations/`.
- [ ] `categories`: `id`, `json name`, `string slug unique`, `unsignedInteger sort default 0`, `boolean is_active default true index`, timestamps.
- [ ] `products`: `id`, `foreignId category_id constrained index`, `json name`, `json description nullable`, `json meta_title nullable`, `json meta_description nullable`, `string slug unique`, `decimal price 10,2 default 0`, `unsignedInteger stock nullable`, `json sizes nullable`, `boolean is_active default true`, timestamps; `index(['is_active','category_id'])`.
- [ ] `orders`: `id`, `string reference unique`, `string customer_name`, `string customer_phone`, `string locale`, `decimal total 10,2 default 0`, `string status default 'new' index`, timestamps.
- [ ] `order_items`: `id`, `foreignId order_id constrained cascadeOnDelete`, `foreignId product_id nullable constrained nullOnDelete`, `string product_name`, `string size nullable`, `unsignedInteger qty default 1`, `decimal unit_price 10,2 default 0`, `decimal line_total 10,2 default 0`, timestamps.
- [ ] `php artisan migrate`. Commit.

### Task 2.3: Models + factories
**Files:** `app/Models/{Category,Product,Order,OrderItem}.php`, `database/factories/{Category,Product,Order,OrderItem}Factory.php`, `tests/Feature/ModelTest.php`.
- [ ] `Category`: `use HasTranslations`, `public array $translatable=['name']`, `$fillable`, `getRouteKeyName='slug'`, `products(): HasMany`, scope `active`.
- [ ] `Product`: `use HasTranslations, InteractsWithMedia`; `$translatable=['name','description','meta_title','meta_description']`; casts `sizes=>array, price=>decimal:2, is_active=>bool`; `getRouteKeyName='slug'`; `category(): BelongsTo`; scope `active`; `registerMediaCollections()` (single `gallery`); `registerMediaConversions()` (WebP: `thumb` 400w, `card` 800w, `full` 1600w, all 4:5, `nonQueued()`).
- [ ] `Order`: `#[ObservedBy(OrderObserver::class)]`; casts `total=>decimal:2`; `items(): HasMany`; `$fillable`.
- [ ] `OrderItem`: casts, `order()`, `product()` BelongsTo, `$fillable`.
- [ ] Factories produce translated arrays for name/description (e.g. `['ro'=>..., 'ru'=>..., 'en'=>...]`).
- [ ] Test: create product with 3 translations; `->getTranslation('name','ru')` works; media collection registered; order hasMany items.
- [ ] Commit.

---

## Phase 3 — Domain services

### Task 3.1: Cart service
**Files:** `app/Services/Cart.php`, `tests/Feature/CartTest.php`.
- [ ] Tests: add creates line keyed `id:size`; adding same line increments; update sets/removes qty; remove deletes; `detailed()` hydrates active products and drops inactive/missing; `total()` sums; `count()` sums qty.
- [ ] Implement (adapt from `.cluade` Cart): session key `cart`; `add/update/remove/clear/count/rawItems/detailed/total`. `detailed()` returns collection of `[lineKey, product, size, qty, unit_price, line_total]` using localized product name at render time (name resolved in views/notifier, not stored here).
- [ ] Run tests → green. Commit.

### Task 3.2: Currency helper + money component
**Files:** `app/Support/Money.php` (or a helper) `format(float): string`, `tests/Unit/MoneyTest.php`, Blade `resources/views/components/money.blade.php`.
- [ ] Test: `Money::format(350)` === `'350.00 MDL'` (with settings currency).
- [ ] Implement using `app(ShopSettings::class)->currency`. Component renders it.
- [ ] Commit.

### Task 3.3: TelegramNotifier + OrderObserver
**Files:** `app/Services/TelegramNotifier.php`, `app/Observers/OrderObserver.php`, `tests/Feature/TelegramTest.php`.
- [ ] Tests (with `Http::fake()`): sends message when token+chat set (assert URL + payload contains reference, items, total, name, phone); skips (no HTTP) when creds blank; HTTP failure is caught (no exception) and logged.
- [ ] Implement notifier reading creds from `ShopSettings`; HTML message (escape with `e()`); try/catch + `Log`. Observer `created()` calls it.
- [ ] Commit.

---

## Phase 4 — Locale infrastructure

### Task 4.1: SetLocale middleware + root redirect + config
**Files:** `config/app.php` (or new `config/locales.php` with `supported=['ro','ru','en']`), `app/Http/Middleware/SetLocale.php`, `bootstrap/app.php` (register middleware alias/group), `routes/web.php` (root redirect + locale switch), `tests/Feature/LocaleTest.php`.
- [ ] Tests: `GET /` → 302 to `/ro` by default; `Accept-Language: ru` → `/ru`; cookie `locale=en` overrides header; `GET /xx/...` (unsupported) → 404; middleware sets `app()->getLocale()`.
- [ ] Implement middleware (validate `{locale}` route param against supported, else abort 404; `app()->setLocale`). Root `GET /` detection: cookie → `Accept-Language` (via `preferredLanguage`) → `ro`. Locale-switch route sets a 1-year cookie and redirects back preserving path/query.
- [ ] Commit.

### Task 4.2: UI translation lang files
**Files:** `lang/{ro,ru,en}.json` (or `lang/{ro,ru,en}/store.php`), `tests/Feature/LangTest.php`.
- [ ] Provide keys for all storefront UI strings (nav, cart, checkout labels, buttons, empty states, order success, validation attribute names). Real translations for RO/RU/EN.
- [ ] Test: each locale resolves a sample key distinctly.
- [ ] Commit.

---

## Phase 5 — Storefront controllers + routes

### Task 5.1: Catalogue (index + show)
**Files:** `app/Http/Controllers/CatalogueController.php`, `routes/web.php` (locale group), `tests/Feature/CatalogueTest.php`.
- [ ] Tests: index shows only active products (paginated), optional `?category=slug` filter; product show 200 for active, 404 for inactive; localized content renders in current locale.
- [ ] Implement `index` (active products, eager-load media+category, category filter, paginate 12) and `show` (by slug scope active). Register routes inside `Route::prefix('{locale}')->middleware('setlocale')` group.
- [ ] Commit.

### Task 5.2: Cart controller (with spam guards)
**Files:** `app/Http/Controllers/CartController.php`, `app/Http/Middleware` throttle usage, `routes/web.php`, `tests/Feature/CartControllerTest.php`.
- [ ] Tests: add validates product exists + size required/in-set when product has sizes + qty 1–99; add/update/remove mutate session; honeypot field filled → silently drop (no add); throttle after N requests → 429.
- [ ] Implement add/show/update/remove (adapt `.cluade` CartController) + honeypot check + `throttle:30,1` on `POST/PATCH/DELETE /cart`.
- [ ] Commit.

### Task 5.3: Checkout (name+phone, stock-locked)
**Files:** `app/Http/Controllers/CheckoutController.php`, `routes/web.php`, `tests/Feature/CheckoutTest.php`.
- [ ] Tests: `show` redirects to catalogue if cart empty; `store` with valid name+phone creates order (status `new`, correct total, locale) + snapshot items (name in current locale) + decrements tracked stock (leaves null stock alone) + clears cart + redirects to success; empty cart on store redirects; **insufficient stock rejected** (lockForUpdate path) with validation error and no order; honeypot + `throttle:5,1`; Telegram fired (`Http::fake`).
- [ ] Implement `store`: validate `customer_name`,`customer_phone` (+ honeypot); `DB::transaction` → re-fetch cart products `lockForUpdate()`, verify stock for each tracked line (else back with error), create order + items (snapshot localized name), decrement, clear cart. `success($locale,$reference)` view.
- [ ] Commit.

---

## Phase 6 — Storefront views & design

> Use the frontend-design skill for this phase. Reuse `.cluade` design tokens; adapt markup for i18n/SEO/gallery. Mobile-first.

### Task 6.1: SEO head + layout shell
**Files:** `resources/views/layouts/store.blade.php`, `resources/views/components/seo/head.blade.php`, `.../seo/hreflang.blade.php`, `.../seo/jsonld-organization.blade.php`, `tests/Feature/SeoTest.php`.
- [ ] Test: any page emits `<link rel=canonical>`, 3 `hreflang` + `x-default`, OG tags, Organization JSON-LD; `<html lang>` matches locale.
- [ ] Implement layout: `<head>` partials (title/meta/OG/twitter/canonical/hreflang), preloaded LCP image slot, inlined critical CSS + Vite CSS, self-hosted font preloads, sticky header (wordmark, nav, cart count from session, language switcher), footer (contact/social from settings), skip-to-content. Near-zero JS.
- [ ] Commit.

### Task 6.2: Catalogue + product-card + product page
**Files:** `resources/views/store/catalogue.blade.php`, `components/product-card.blade.php`, `store/product.blade.php`, `components/seo/jsonld-product.blade.php`, `tests/Feature/ProductPageTest.php`.
- [ ] Test: catalogue lists cards with responsive `srcset`; product page shows gallery, sizes (first preselected), add-to-cart form, sold-out when tracked stock ≤ 0, Product + BreadcrumbList JSON-LD.
- [ ] Implement (4:5 images via medialibrary conversions, `loading=lazy` below fold, explicit dimensions). Hero with slogan on catalogue.
- [ ] Commit.

### Task 6.3: Cart, checkout, success, 404
**Files:** `store/{cart,checkout,success}.blade.php`, `resources/views/errors/404.blade.php`.
- [ ] Cart page (qty update form, remove, subtotal, empty state); checkout (name/phone form, honeypot hidden field, order summary, CSRF); success (reference + summary + "we'll call you"); branded localized 404.
- [ ] Manual + existing feature tests cover routes. Commit.

### Task 6.4: store.css (bespoke, tokens) + critical CSS
**Files:** `resources/css/app.css`, inline critical partial.
- [ ] Port/adapt `.cluade/public/css/store.css` tokens & layout; mobile-first; grid 3→2→1; buttons/forms; language switcher; cart badge.
- [ ] `npm run build`; verify pages render. Commit.

---

## Phase 7 — SEO endpoints

### Task 7.1: sitemap.xml + robots.txt
**Files:** `app/Http/Controllers/SitemapController.php`, `routes/web.php` (outside locale group), `public/robots.txt` or route, `tests/Feature/SitemapTest.php`.
- [ ] Test: `GET /sitemap.xml` 200 XML lists each active product + catalogue in all 3 locales with `xhtml:link` alternates; robots references sitemap.
- [ ] Implement cached sitemap (bust on product save via observer or short TTL). robots allows all, points to sitemap.
- [ ] Commit.

---

## Phase 8 — Admin (Filament v5, mobile-first, login-only)

### Task 8.1: Panel provider — login only + brand + timezone
**Files:** `app/Providers/Filament/AdminPanelProvider.php`, `tests/Feature/Admin/PanelTest.php`.
- [ ] Test: `/admin` redirects guests to login; login route exists; `/admin/register` and password-reset routes 404; authed user reaches dashboard.
- [ ] Configure panel: `->login()` only; brand name/color (ink/paper); `->spa()` optional; register media/settings plugins; navigation groups. Ensure no `->registration()/passwordReset()/profile()`.
- [ ] Commit.

### Task 8.2: Single-user provisioning
**Files:** `database/seeders/AdminUserSeeder.php` or `app/Console/Commands/MakeOwner.php`, `tests/Feature/Admin/OwnerTest.php`.
- [ ] Test: command/seeder creates exactly one user with given email/password; idempotent.
- [ ] Implement `php artisan personna:owner {email} {password}` (or seeder reading env). No registration UI.
- [ ] Commit.

### Task 8.3: CategoryResource (translatable, mobile-first)
**Files:** `app/Filament/Resources/CategoryResource.php` (+ Pages), `tests/Feature/Admin/CategoryResourceTest.php`.
- [ ] Test (Livewire): authed owner can list/create/edit a category with `name` in 3 locales (lara-zeus locale tabs), slug auto from name; guests blocked.
- [ ] Implement resource with lara-zeus `Translatable` trait/tabs; slug (auto, unique), sort, is_active; compact mobile table (name, is_active; rest toggleable).
- [ ] Commit.

### Task 8.4: ProductResource (translatable + media + sizes, mobile-first)
**Files:** `app/Filament/Resources/ProductResource.php` (+ Pages), `tests/Feature/Admin/ProductResourceTest.php`.
- [ ] Test: create/edit product with 3-locale name/description/meta, category, price, stock (blank=∞), sizes checkboxlist, `SpatieMediaLibraryFileUpload` gallery (multiple, reorderable, `collection('gallery')`), is_active; slug auto.
- [ ] Implement; mobile-friendly single-column form sections; table: image, name, price, stock (∞ placeholder), is_active — extras toggleable; is_active filter.
- [ ] Commit.

### Task 8.5: OrderResource (view + status + items)
**Files:** `app/Filament/Resources/OrderResource.php` (+ Pages + ItemsRelationManager), `tests/Feature/Admin/OrderResourceTest.php`.
- [ ] Test: `canCreate()===false`; nav badge counts `new`; owner can change status (new→completed/cancelled) and it persists; items relation read-only.
- [ ] Implement: table (reference, customer_name, customer_phone, total, status badge colors, created_at; searchable name/phone); view page + editable status select; ItemsRelationManager (read-only snapshot columns). Mobile-first columns.
- [ ] Commit.

### Task 8.6: Settings page
**Files:** `app/Filament/Pages/ManageShopSettings.php`, view auto, `tests/Feature/Admin/SettingsPageTest.php`.
- [ ] Test: owner can load settings page and save currency + telegram + contact fields; persisted to `ShopSettings`.
- [ ] Implement via `php artisan make:filament-settings-page` bound to `ShopSettings`; grouped form (currency; Telegram token/chat; Instagram/phone/email/address).
- [ ] Commit.

---

## Phase 9 — Seeders, caching, final verification

### Task 9.1: Category + product seeders (sample data)
**Files:** `database/seeders/{Category,Product,Database}Seeder.php`.
- [ ] Seed a "T-Shirts" category (3 locales) + 3 sample tees (translated names/descriptions, prices in MDL, sizes XS–XXL). `DatabaseSeeder` calls them (+ AdminUser if env set).
- [ ] `php artisan migrate:fresh --seed` works. Commit.

### Task 9.2: Final verification
- [ ] `./vendor/bin/pest` — full suite green (capture output).
- [ ] `npm run build` — assets built.
- [ ] `php artisan config:cache route:cache view:cache` succeed (then clear for dev).
- [ ] Boot `php artisan serve`, smoke-check `/` redirect, a product page (hreflang/JSON-LD in source), `/admin` login gate, `/sitemap.xml`.
- [ ] Update root `CLAUDE.md` to describe the real app (replace overlay description). Commit.
- [ ] Ask user before `git push -u origin main`.

---

## Self-review notes
- Spec coverage: i18n (2.3/4.x/8.3–8.4), SEO (6.1/6.2/7.1), PageSpeed (1.2 fonts, 6.4 critical CSS, media conversions 2.3), mobile-first (6.x, 8.x), cart/checkout/stock-lock/Telegram (3.x/5.x), settings (2.1/8.6), single-user login-only (8.1/8.2), statuses (2.2/8.5) — all mapped.
- Deps verified compatible (see spec §2). Node 20.10 OK for Vite; if Vite 7 warns on engine, pin laravel-vite-plugin/vite to a Node-20-compatible line.
- YAGNI: no payments/delivery/emails/per-region pricing/audit tables.
