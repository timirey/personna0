<?php

use App\Models\Product;

it('emits canonical, all hreflang alternates and x-default on the catalogue', function () {
    $this->get('/en')
        ->assertOk()
        ->assertSee('rel="canonical" href="'.route('catalogue', 'en').'"', false)
        ->assertSee('hreflang="ro" href="'.route('catalogue', 'ro').'"', false)
        ->assertSee('hreflang="ru" href="'.route('catalogue', 'ru').'"', false)
        ->assertSee('hreflang="en" href="'.route('catalogue', 'en').'"', false)
        ->assertSee('hreflang="x-default"', false);
});

it('sets the html lang attribute to the current locale', function () {
    $this->get('/ru')->assertOk()->assertSee('<html lang="ru">', false);
});

it('includes Organization JSON-LD site-wide with the Personna brand', function () {
    $this->get('/en')->assertOk()
        ->assertSee('"@type":"Organization"', false)
        ->assertSee('"name":"Personna"', false);
});

it('emits csrf-token and app-locale meta for the reactive layer', function () {
    $this->get('/ru')
        ->assertOk()
        ->assertSee('name="csrf-token"', false)
        ->assertSee('name="app-locale" content="ru"', false);
});

it('points the product canonical and alternates at the localized urls', function () {
    Product::factory()->create(['slug' => 'seo-tee']);

    $this->get('/ru/product/seo-tee')
        ->assertOk()
        ->assertSee('rel="canonical" href="'.route('product', ['ru', 'seo-tee']).'"', false)
        ->assertSee('hreflang="en" href="'.route('product', ['en', 'seo-tee']).'"', false);
});
