<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogueController::class, 'index'])->name('catalogue');
Route::get('/product/{product}', [CatalogueController::class, 'show'])->name('product');

Route::get('/cart', [CartController::class, 'show'])->name('cart');
Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/{reference}', [CheckoutController::class, 'success'])->name('order.success');
