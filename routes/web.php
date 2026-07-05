<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LocaleRedirectController;
use Illuminate\Support\Facades\Route;

// Root: detect locale and redirect to /{locale}
Route::get('/', LocaleRedirectController::class);

// Locale-prefixed storefront. Every public URL lives under /{ro|ru|en}/...
Route::prefix('{locale}')->middleware('setlocale')->group(function () {
    Route::get('/', [CatalogueController::class, 'index'])->name('catalogue');
    Route::get('/product/{slug}', [CatalogueController::class, 'show'])->name('product');

    Route::get('/cart', [CartController::class, 'show'])->name('cart');
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add')->middleware('throttle:30,1');
    Route::patch('/cart', [CartController::class, 'update'])->name('cart.update')->middleware('throttle:60,1');
    Route::delete('/cart', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:6,1');
    Route::get('/order/{reference}', [CheckoutController::class, 'success'])->name('order.success');
});
