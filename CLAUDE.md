# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this repo is

**Personna0** — a tri-lingual (RO/RU/EN), SEO- and mobile-optimized clothing storefront (Laravel 13) with a Filament v5 single-owner admin. Session cart → name/phone checkout → Telegram notification. No payments, no delivery, no customer accounts.

The full spec and implementation plan live in `docs/superpowers/`. The original Claude-web prototype (Filament v3) is kept **for reference only** in `.cluade/` (note the misspelled dir) — brand photos are in `.cluade/pics`. Don't run or edit `.cluade`.

## Commands

```bash
# Tests (Pest 4) — the source of truth
./vendor/bin/pest                          # full suite
./vendor/bin/pest tests/Feature/CartTest.php
./vendor/bin/pest --filter="stock"         # by name

# Frontend build — REQUIRES Node >= 20.19. The system default is 20.10, so use node@22:
export PATH="/opt/homebrew/opt/node@22/bin:$PATH"
npm run build                              # or: npm run dev

php artisan migrate:fresh --seed           # rebuild DB + sample data (1 category, 3 tees)
php artisan personna:owner <email> <password>   # create/update the single admin owner
php artisan serve                          # store at /, admin at /admin
```

Vite 8 (Rolldown) hard-fails on Node < 20.19 — always build with `node@22`. `node_modules` must be installed under node@22 too (the native binding is version-specific).

## Architecture

**Storefront** — server-rendered Blade, deliberately near-zero JS, bespoke CSS (no Tailwind) for PageSpeed.
- All public URLs are locale-prefixed: `/{ro|ru|en}/...`. `App\Http\Middleware\SetLocale` (alias `setlocale`) validates the segment, sets the app locale, and persists a `locale` cookie (exempt from encryption in `bootstrap/app.php`). `/` → `LocaleRedirectController` detects locale (cookie → Accept-Language → `ro`).
- `config/locales.php` is the single source of supported locales.
- `App\Support\Seo` builds canonical + hreflang alternates by swapping the `{locale}` route segment. `SitemapController` serves `/sitemap.xml` (cached 1h, all locales + `xhtml:link` alternates) and `/robots.txt`. Product/Organization/Breadcrumb JSON-LD live in the Blade views.
- Controllers take `$locale` as the **first** parameter when they use route scalars (Laravel binds the `{locale}` segment positionally) — see `CatalogueController::show`.

**Domain** — `App\Services\Cart` is session-backed, keyed `"{productId}:{size}"`, and `detailed()` re-hydrates from the DB (dropping inactive products). `CheckoutController::store` runs in a `DB::transaction` with `lockForUpdate()` to prevent overselling, snapshots each line into `order_items` (product name in the order's locale), decrements tracked stock, and clears the cart. `Order` (`#[ObservedBy(OrderObserver::class)]`) fires `TelegramNotifier` on create — synchronous but wrapped in try/catch so it never breaks checkout. Currency/Telegram/contact come from `App\Settings\ShopSettings` (spatie/laravel-settings), not `.env`.

**i18n & media** — product/category content is translatable via `spatie/laravel-translatable` (JSON columns; `$translatable` on the models). Images use `spatie/laravel-medialibrary` (`gallery` collection, WebP `thumb`/`card`/`full` conversions, `nonQueued`).

**Admin** (`app/Filament/Resources/*`, Filament v5) — login-only (registration/reset intentionally not enabled; `User implements FilamentUser`). Resources are nested (`Resources/Products/{ProductResource, Schemas/ProductForm, Tables/ProductsTable, Pages/*}`). Product/Category resources are translatable via `lara-zeus/spatie-translatable` (the official Filament translatable plugin is abandoned/has no v5 release) — resources `use LaraZeus\...\Resources\Concerns\Translatable` and pages use the matching page concerns + `LocaleSwitcher`. Orders are view + inline status `SelectColumn` only (no create/edit). Settings via `ManageShopSettings` (extends the spatie settings `SettingsPage`).

## Conventions

- **TDD**: write/adjust Pest tests with every change; keep the suite green before committing.
- Money is `decimal(10,2)`, displayed via `App\Support\Money::format()` / `<x-money>` using the settings currency.
- Order statuses: `App\Enums\OrderStatus` (New/Completed/Cancelled) — implements Filament `HasLabel`/`HasColor`; labels come from `lang/{locale}/shop.php`.
- Storefront UI strings live in `lang/{ro,ru,en}/shop.php`.
- Design tokens & fonts: warm-neutral palette + Fraunces (serif wordmark) / Manrope (body, full Cyrillic), self-hosted via fontsource in `resources/css/app.css`.
