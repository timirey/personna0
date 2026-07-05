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

it('sets the application locale from the url', function () {
    $this->get('/ru')->assertOk()->assertSee('ru');
});

it('404s on an unsupported locale', function () {
    $this->get('/zz')->assertNotFound();
});

it('persists the visited locale in a cookie', function () {
    $this->get('/en')->assertOk()->assertPlainCookie('locale', 'en');
});
