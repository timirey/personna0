<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Personna')
            // Login only — registration, password reset, email verification and
            // profile are intentionally NOT enabled, so those routes don't exist.
            ->login()
            ->colors([
                'primary' => Color::Stone,
            ])
            ->plugin(
                SpatieTranslatablePlugin::make()
                    ->defaultLocales(config('locales.supported'))
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            // No dashboard: the Orders list is the landing page.
            ->homeUrl(fn (): string => OrderResource::getUrl())
            // Panel UI language (RU default) + switcher in the user menu.
            ->userMenuItems([
                MenuItem::make()->label('Русский')->icon('heroicon-o-language')->url(fn () => route('admin.locale', 'ru')),
                MenuItem::make()->label('Română')->icon('heroicon-o-language')->url(fn () => route('admin.locale', 'ro')),
                MenuItem::make()->label('English')->icon('heroicon-o-language')->url(fn () => route('admin.locale', 'en')),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetAdminLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
