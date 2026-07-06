<?php

namespace App\Filament\Pages;

use App\Settings\ShopSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageShopSettings extends SettingsPage
{
    protected static string $settings = ShopSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.shop');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.settings');
    }

    public function getTitle(): string
    {
        return __('admin.settings.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('currency')
                    ->label(__('admin.fields.currency'))
                    ->required()
                    ->maxLength(3)
                    ->helperText(__('admin.help.currency')),

                TextInput::make('telegram_bot_token')
                    ->label(__('admin.fields.telegram_token'))
                    ->password()
                    ->revealable(),

                TextInput::make('telegram_chat_id')
                    ->label(__('admin.fields.telegram_chat')),

                TextInput::make('hero_image')
                    ->label(__('admin.fields.hero_image'))
                    ->helperText(__('admin.help.hero_image')),

                TextInput::make('instagram_url')
                    ->label(__('admin.fields.instagram'))
                    ->url(),

                TextInput::make('contact_phone')
                    ->label(__('admin.fields.contact_phone')),

                TextInput::make('contact_email')
                    ->label(__('admin.fields.contact_email'))
                    ->email(),

                TextInput::make('address')
                    ->label(__('admin.fields.address')),
            ]);
    }
}
