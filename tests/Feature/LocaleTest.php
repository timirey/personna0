<?php

it('falls back to the default locale for unsupported languages', function () {
    $this->get('/', ['Accept-Language' => 'fr-FR,fr;q=0.9'])->assertRedirect('/ro');
});

it('redirects the root using the Accept-Language header', function () {
    $this->get('/', ['Accept-Language' => 'ru'])->assertRedirect('/ru');
});

it('lets a locale cookie override the header', function () {
    $this->withUnencryptedCookie('locale', 'en')
        ->get('/', ['Accept-Language' => 'ru'])
        ->assertRedirect('/en');
});

it('404s on an unsupported locale', function () {
    $this->get('/zz')->assertNotFound();
});

it('sets and persists the locale from a prefixed request', function () {
    // Empty-cart checkout redirects (no view needed), but the setlocale middleware
    // still runs: the redirect target is built from app()->getLocale(), and the
    // locale is persisted as a plain cookie.
    $this->get('/en/checkout')
        ->assertRedirect(route('catalogue', 'en'))
        ->assertPlainCookie('locale', 'en');
});
