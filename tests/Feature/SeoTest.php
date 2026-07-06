<?php

use App\Models\Product;
use Inertia\Testing\AssertableInertia as Assert;

it('shares canonical + hreflang alternates for the catalogue', function () {
    $this->get('/en')->assertInertia(fn (Assert $page) => $page
        ->where('locale', 'en')
        ->where('seo.canonical', route('catalogue', 'en'))
        ->where('seo.alternates.ro', route('catalogue', 'ro'))
        ->where('seo.alternates.ru', route('catalogue', 'ru'))
        ->where('seo.alternates.en', route('catalogue', 'en'))
        ->has('seo.xDefault')
    );
});

it('shares localized canonical + alternates for a product', function () {
    Product::factory()->create(['slug' => 'seo-tee']);

    $this->get('/ru/product/seo-tee')->assertInertia(fn (Assert $page) => $page
        ->where('seo.canonical', route('product', ['ru', 'seo-tee']))
        ->where('seo.alternates.en', route('product', ['en', 'seo-tee']))
    );
});

it('shares the current-locale UI translations', function () {
    $this->get('/ru')->assertInertia(fn (Assert $page) => $page->where('translations.nav.cart', 'Корзина'));
    $this->get('/ro')->assertInertia(fn (Assert $page) => $page->where('translations.nav.cart', 'Coș'));
});
