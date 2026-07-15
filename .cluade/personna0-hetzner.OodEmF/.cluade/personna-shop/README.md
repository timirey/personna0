# Personna — Shop

A small Laravel storefront + admin for selling t-shirts.

- **Storefront** — public, server-rendered Blade (SEO-friendly, responsive), styled like the Personna landing page. Session cart, no customer accounts.
- **Admin** — Filament v3 panel at `/admin`. Manage products, view orders. One owner login, no registration, no roles.
- **Telegram** — every new order is pushed to your Telegram chat automatically.

This package is an **overlay**: a set of app files you drop into a fresh Laravel app. (It doesn't include the framework / vendor folder.)

---

## What's in here

```
app/Models/                 Product, Order, OrderItem
app/Observers/              OrderObserver -> fires Telegram on new order
app/Services/               TelegramNotifier, Cart (session-based)
app/Http/Controllers/       Catalogue, Cart, Checkout
app/Filament/Resources/     ProductResource, OrderResource (+ pages, items relation)
config/shop.php             currency + telegram config
database/migrations/        products, orders, order_items
database/seeders/           ProductSeeder (3 sample tees) + DatabaseSeeder
routes/web.php              storefront routes
resources/views/            store layout + pages + <x-money> component
public/css/store.css        self-contained storefront styling (no build step)
env-snippet.txt             env vars to add
```

The storefront styling is **plain CSS in `public/css/store.css`** — no Tailwind build, no npm step. Works the same on Laravel 11 or 12 regardless of their Tailwind version. (Filament ships its own admin assets.)

---

## Setup (about 5 minutes)

### 1. Create a fresh Laravel app
```bash
composer create-project laravel/laravel personna-shop
cd personna-shop
```

### 2. Copy the overlay files in
Copy everything from this package into the project, **keeping the folder paths**.
Overwrite `routes/web.php` and `database/seeders/DatabaseSeeder.php` when asked.
(`env-snippet.txt` and this README don't need to go into the app.)

### 3. Install Filament v3
```bash
composer require filament/filament:"^3.2"
php artisan filament:install --panels
```
This creates `app/Providers/Filament/AdminPanelProvider.php`. It auto-discovers the
resources in `app/Filament/Resources`, so the Products + Orders screens appear with no
extra wiring.

### 4. Configure `.env`
Open `env-snippet.txt` and add those lines to your `.env`. Then:
```bash
touch database/database.sqlite     # if using sqlite (recommended)
php artisan key:generate
```

### 5. Migrate, create your login, link storage, seed
```bash
php artisan migrate
php artisan make:filament-user      # <- your single owner login (email + password)
php artisan storage:link            # serves uploaded product images
php artisan db:seed                 # optional: 3 sample products
```

### 6. Run it
```bash
php artisan serve
```
- Store:  http://localhost:8000
- Admin:  http://localhost:8000/admin

Log in with the user from step 5, add products (upload an image, set price + sizes,
toggle Active), then place a test order from the storefront.

---

## Telegram setup

1. In Telegram, message **@BotFather** → `/newbot` → follow prompts → copy the **bot token**
   into `TELEGRAM_BOT_TOKEN`.
2. Get your **chat id**: send any message to your new bot, then open
   `https://api.telegram.org/bot<YOUR_TOKEN>/getUpdates` in a browser and read
   `message.chat.id`. (Or message **@userinfobot**.) Put it in `TELEGRAM_CHAT_ID`.
3. Place a test order — you'll get a formatted message with the reference, items, total,
   and customer details.

If the token/chat id are blank, notifications are simply skipped (the order still saves).
Sending happens synchronously in the order observer and is wrapped in try/catch, so a
Telegram hiccup never breaks checkout. To move it off the request, queue it: dispatch the
`TelegramNotifier` call from a job instead of calling it directly in `OrderObserver`.

---

## Notes / decisions

- **No customer accounts.** Cart lives in the session; checkout just collects name +
  phone (email/address/notes optional). Single owner logs into Filament; no registration
  route is exposed and no roles/permissions are used.
- **Money** is stored as `decimal(10,2)` and shown as `"<amount> <CURRENCY>"` via the
  `<x-money>` Blade component and a small formatter in Filament — no `intl` extension
  needed. Change the currency in `.env` (`SHOP_CURRENCY`).
- **Stock** is optional per product: leave it empty for unlimited, or set a number and it
  decrements on each order.
- **Order items are snapshots** (product name + price copied at purchase time), so editing
  or deleting a product later won't change past orders.
- **Swapping in real product photos:** do it in the admin — each product has an image
  upload. The storefront shows a clean "Personna" placeholder until an image is set.
