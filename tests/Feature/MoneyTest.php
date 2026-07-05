<?php

use App\Settings\ShopSettings;
use App\Support\Money;

it('formats money with the configured currency', function () {
    expect(Money::format(350))->toBe('350.00 MDL');
});

it('reflects a changed currency setting', function () {
    ShopSettings::fake(['currency' => 'EUR']);

    expect(Money::format(1234.5))->toBe('1,234.50 EUR')
        ->and(Money::currency())->toBe('EUR');
});
