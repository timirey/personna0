<?php

use App\Settings\ShopSettings;

it('resolves shop settings with sensible defaults', function () {
    $settings = app(ShopSettings::class);

    expect($settings->currency)->toBe('MDL')
        ->and($settings->telegramEnabled())->toBeFalse()
        ->and($settings->instagram_url)->toBe('https://www.instagram.com/personna0/');
});

it('reports telegram enabled once credentials are set', function () {
    $settings = app(ShopSettings::class);
    $settings->telegram_bot_token = '123:ABC';
    $settings->telegram_chat_id = '999';

    expect($settings->telegramEnabled())->toBeTrue();
});
