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

    protected static string|UnitEnum|null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return 'Settings';
    }

    public function getTitle(): string
    {
        return 'Shop settings';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('currency')
                    ->required()
                    ->maxLength(3)
                    ->helperText('Currency code, e.g. MDL, EUR, USD.'),

                TextInput::make('telegram_bot_token')
                    ->label('Telegram bot token')
                    ->password()
                    ->revealable(),

                TextInput::make('telegram_chat_id')
                    ->label('Telegram chat ID'),

                TextInput::make('hero_image')
                    ->label('Homepage hero image (URL or /path)')
                    ->helperText('Shown beside the slogan on the homepage. Leave blank to hide it.'),

                TextInput::make('instagram_url')
                    ->label('Instagram URL')
                    ->url(),

                TextInput::make('contact_phone'),

                TextInput::make('contact_email')
                    ->email(),

                TextInput::make('address'),
            ]);
    }
}
