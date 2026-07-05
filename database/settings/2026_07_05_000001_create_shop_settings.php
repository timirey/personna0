<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('shop.currency', 'MDL');
        $this->migrator->add('shop.telegram_bot_token', null);
        $this->migrator->add('shop.telegram_chat_id', null);
        $this->migrator->add('shop.instagram_url', 'https://www.instagram.com/personna0/');
        $this->migrator->add('shop.contact_phone', null);
        $this->migrator->add('shop.contact_email', null);
        $this->migrator->add('shop.address', null);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('shop.currency');
        $this->migrator->deleteIfExists('shop.telegram_bot_token');
        $this->migrator->deleteIfExists('shop.telegram_chat_id');
        $this->migrator->deleteIfExists('shop.instagram_url');
        $this->migrator->deleteIfExists('shop.contact_phone');
        $this->migrator->deleteIfExists('shop.contact_email');
        $this->migrator->deleteIfExists('shop.address');
    }
};
