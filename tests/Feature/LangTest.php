<?php

use App\Enums\OrderStatus;

it('resolves storefront strings in each locale', function () {
    app()->setLocale('en');
    expect(__('shop.nav.cart'))->toBe('Cart');

    app()->setLocale('ro');
    expect(__('shop.nav.cart'))->toBe('Coș');

    app()->setLocale('ru');
    expect(__('shop.nav.cart'))->toBe('Корзина');
});

it('translates order status labels through the enum', function () {
    app()->setLocale('ru');
    expect(OrderStatus::New->getLabel())->toBe('Новый');

    app()->setLocale('ro');
    expect(OrderStatus::Completed->getLabel())->toBe('Finalizată');
});
