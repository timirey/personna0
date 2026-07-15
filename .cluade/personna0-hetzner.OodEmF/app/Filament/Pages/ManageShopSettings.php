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
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;

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

    /**
     * Optimize a freshly uploaded hero into a web-sized WebP so the homepage
     * LCP image stays small — regardless of what the owner uploaded.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $hero = $data['hero_image'] ?? null;

        if (! $hero || str_starts_with($hero, '/') || str_starts_with($hero, 'http')) {
            return $data;
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($hero) || str_ends_with($hero, '.webp')) {
            return $data;
        }

        try {
            $webp = preg_replace('/\.\w+$/', '.webp', $hero);
            Image::load($disk->path($hero))
                ->fit(Fit::Max, 1000, 1600)
                ->format('webp')
                ->quality(82)
                ->save($disk->path($webp));

            $disk->delete($hero);
            $data['hero_image'] = $webp;
        } catch (\Throwable $e) {
            // Keep the original upload if conversion fails.
        }

        return $data;
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
