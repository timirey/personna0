<?php

use App\Models\Product;

it('serves a sitemap listing every locale with alternates', function () {
    Product::factory()->create(['slug' => 'map-tee']);

    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    expect($response->headers->get('Content-Type'))->toContain('xml');

    $response
        ->assertSee(route('catalogue', 'ro'), false)
        ->assertSee(route('catalogue', 'ru'), false)
        ->assertSee(route('product', ['en', 'map-tee']), false)
        ->assertSee('xhtml:link', false)
        ->assertSee('hreflang="ru"', false);
});

it('excludes inactive products from the sitemap', function () {
    Product::factory()->inactive()->create(['slug' => 'ghost-tee']);

    $this->get('/sitemap.xml')->assertOk()->assertDontSee('ghost-tee');
});

it('serves robots.txt pointing at the sitemap', function () {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertSee('Sitemap: '.route('sitemap'), false)
        ->assertSee('Disallow: /admin', false);
});
