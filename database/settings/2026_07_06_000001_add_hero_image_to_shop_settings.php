<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('shop.hero_image', '/images/hero.jpg');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('shop.hero_image');
    }
};
