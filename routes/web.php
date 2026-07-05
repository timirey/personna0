<?php

use App\Http\Controllers\LocaleRedirectController;
use Illuminate\Support\Facades\Route;

// Root: detect locale and redirect to /{locale}
Route::get('/', LocaleRedirectController::class);

// Locale-prefixed storefront. Every public URL lives under /{ro|ru|en}/...
Route::prefix('{locale}')->middleware('setlocale')->group(function () {
    // Placeholder home — replaced by CatalogueController in Phase 5.
    Route::get('/', fn () => response(app()->getLocale()))->name('catalogue');
});
