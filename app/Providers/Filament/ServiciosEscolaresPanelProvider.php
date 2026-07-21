<?php

namespace App\Providers\Filament;

use Filament\Forms\Components\FileUpload;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class ServiciosEscolaresPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('servicios-escolares')
            ->path('servicios-escolares')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->favicon(asset('images/favicon.ico'))
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('3rem')
            ->colors([
                'primary' => '#004225',
            ])
            ->login()
            ->passwordReset()
            ->sidebarWidth('15rem')
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        userMenuLabel: 'Mi Perfil',
                        shouldRegisterNavigation: false,
                        hasAvatars: true,
                    )
                    ->avatarUploadComponent(fn (FileUpload $fileUpload) => $fileUpload
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->preventFilePathTampering(),
                    )
                    ->enableSanctumTokens()
                    ->enableBrowserSessions(),
            ])
            ->discoverResources(in: app_path('Filament/ServiciosEscolares/Resources'), for: 'App\\Filament\\ServiciosEscolares\\Resources')
            ->discoverPages(in: app_path('Filament/ServiciosEscolares/Pages'), for: 'App\\Filament\\ServiciosEscolares\\Pages')
            ->discoverWidgets(in: app_path('Filament/ServiciosEscolares/Widgets'), for: 'App\\Filament\\ServiciosEscolares\\Widgets')
            ->pages([Dashboard::class])
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class,
                AuthenticateSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class,
                SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class])
            ->topNavigation()
            ->renderHook(PanelsRenderHook::STYLES_AFTER, fn (): string => '
                <style>
                    .fi-user-menu-trigger-text,
                    .fi-dropdown-header {
                        display: none !important;
                    }
                </style>
            ');

    }
}
