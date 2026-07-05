# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this repo is

An **overlay package**, not a runnable app. The actual source lives under `.cluade/personna-shop/` (note the misspelled `.cluade` directory). It's a set of Laravel app files ("Personna — Shop", a t-shirt storefront + Filament admin) meant to be copied on top of a *fresh* Laravel 11/12 project — the framework, `vendor/`, `composer.json`, and `artisan` are intentionally absent here.

Because of this, **you cannot build, migrate, or run anything from this repo directly.** There is no test suite, linter, or dependency manifest in-tree. All the commands below only work *after* the overlay has been copied into a real Laravel app (see `.cluade/personna-shop/README.md` for the full 6-step setup).

## Commands (only valid inside a hydrated Laravel app)

```bash
composer require filament/filament:"^3.2"   # admin panel (v3)
php artisan filament:install --panels        # generates AdminPanelProvider; auto-discovers Resources
php artisan make:filament-user               # the single owner login (no registration route exists)
php artisan migrate
php artisan storage:link                      # serves uploaded product images
php artisan db:seed                           # 3 sample products (ProductSeeder)
php artisan serve                             # store at /, admin at /admin
```

## Architecture

Two surfaces over three models (`Product`, `Order`, `OrderItem`):

- **Storefront** — server-rendered Blade under `resources/views/store/`, routed in `routes/web.php` via `CatalogueController`, `CartController`, `CheckoutController`. No customer accounts. Styling is **plain CSS in `public/css/store.css`** — deliberately no Tailwind/npm build step so it's version-agnostic across Laravel 11/12.
- **Admin** — Filament v3 at `/admin`, resources in `app/Filament/Resources/`. Single owner, no roles/permissions.

Key design decisions worth preserving when editing:

- **Cart is session-only** (`app/Services/Cart.php`). Lines are keyed by `"{productId}:{size}"` in `session('cart')`. `detailed()` re-hydrates from the DB and silently drops products that are inactive or deleted, so cart totals always reflect current live products. It's resolved via DI in controllers but holds no instance state (everything lives in the session).
- **Order notifications fire via a model observer.** `Order` uses the Laravel 11 `#[ObservedBy([OrderObserver::class])]` attribute (no manual registration in a service provider). On `created`, `OrderObserver` calls `TelegramNotifier` **synchronously** inside the request. `TelegramNotifier` no-ops if the token/chat id are blank and wraps the HTTP call in try/catch + `Log`, so Telegram failures never break checkout. To move it off the request, dispatch it from a queued job instead.
- **Checkout is transactional.** `CheckoutController::store()` wraps order + item creation and stock decrement in `DB::transaction()`. Stock is optional per product (`null` = unlimited); it only decrements when `stock` is non-null.
- **Order items are immutable snapshots.** `product_name`, `unit_price`, and `line_total` are copied onto `OrderItem` at purchase time, so later edits/deletes of a `Product` never alter past orders.
- **Money** is `decimal(10,2)` in the DB, displayed as `"<amount> <CURRENCY>"` via the `<x-money>` Blade component and `number_format(...)` in Filament — no `intl` extension. Currency comes from `config('shop.currency')` (`SHOP_CURRENCY` env, defaults `MDL`).
- **Product routing** uses `slug` as the route key (`Product::getRouteKeyName()`); the slug is auto-generated from the name in the Filament form.

Config lives in `config/shop.php` (currency + Telegram credentials); env keys are documented in `.cluade/personna-shop/env-snippet.txt`.
