<?php

namespace App\Filament\Pages;

use App\Settings\ShopSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageShopSettings extends SettingsPage
{
    protected static string $settings = ShopSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 4;

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
                Select::make('currency')
                    ->label(__('admin.fields.currency'))
                    ->options(['MDL' => 'MDL', 'EUR' => 'EUR', 'USD' => 'USD'])
                    ->required()
                    ->native(false),

                TextInput::make('telegram_bot_token')
                    ->label(__('admin.fields.telegram_token'))
                    ->password()
                    ->revealable(),

                TextInput::make('telegram_chat_id')
                    ->label(__('admin.fields.telegram_chat')),

                TextInput::make('instagram_url')
                    ->label(__('admin.fields.instagram'))
                    ->url(),

                TextInput::make('telegram_url')
                    ->label(__('admin.fields.telegram_url'))
                    ->url(),

                TextInput::make('contact_phone')
                    ->label(__('admin.fields.contact_phone')),

                FileUpload::make('hero_image')
                    ->label(__('admin.fields.hero_image'))
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('hero')
                    ->helperText(__('admin.help.hero_image'))
                    ->columnSpanFull(),
            ]);
    }
}
