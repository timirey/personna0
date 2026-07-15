<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('shop.telegram_url', null);
        $this->migrator->deleteIfExists('shop.contact_email');
        $this->migrator->deleteIfExists('shop.address');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('shop.telegram_url');
        $this->migrator->add('shop.contact_email', null);
        $this->migrator->add('shop.address', null);
    }
};
