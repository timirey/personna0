<?php

return [
    // Currency code used for display + Telegram messages (e.g. MDL, EUR, USD)
    'currency' => env('SHOP_CURRENCY', 'MDL'),

    // Telegram bot credentials — orders are pushed here on creation
    'telegram_token'   => env('TELEGRAM_BOT_TOKEN'),
    'telegram_chat_id' => env('TELEGRAM_CHAT_ID'),
];
