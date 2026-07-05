<?php

use App\Models\User;

it('requires authentication for the admin panel', function () {
    $this->get('/admin')->assertRedirect();
    $this->get('/admin/products')->assertRedirect();
    $this->get('/admin/orders')->assertRedirect();
});

it('exposes login but no registration or password-reset routes', function () {
    $this->get('/admin/login')->assertOk();
    $this->get('/admin/register')->assertNotFound();
    $this->get('/admin/password-reset/request')->assertNotFound();
});

it('lets an authenticated owner reach the panel, resources and settings', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin')->assertOk();
    $this->get('/admin/products')->assertOk();
    $this->get('/admin/categories')->assertOk();
    $this->get('/admin/orders')->assertOk();
    $this->get('/admin/manage-shop-settings')->assertOk();
});
