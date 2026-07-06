<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ShopSettings extends Settings
{
    /** Currency code shown across the store + Telegram messages (e.g. MDL, EUR, USD). */
    public string $currency;

    /** Telegram bot credentials — new orders are pushed here on creation. */
    public ?string $telegram_bot_token;
    public ?string $telegram_chat_id;

    /** Contact & social details rendered in the storefront footer/contact. */
    public ?string $instagram_url;
    public ?string $contact_phone;
    public ?string $contact_email;
    public ?string $address;

    /** Homepage hero image (URL or /path). Blank hides the hero image. */
    public ?string $hero_image;

    public static function group(): string
    {
        return 'shop';
    }

    /** Whether Telegram notifications are configured. */
    public function telegramEnabled(): bool
    {
        return filled($this->telegram_bot_token) && filled($this->telegram_chat_id);
    }
}
