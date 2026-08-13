<?php

namespace App\Providers\Filament;

use App\Filament\Financiero\Widgets\PagosCuotaVoluntaria;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Forms\Components\FileUpload;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class FinancieroPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('financiero')
            ->path('financiero')
            ->favicon(asset('images/favicon.ico'))
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('3rem')
            ->viteTheme('resources/css/filament/financiero/theme.css')
            ->colors([
                'primary' => '#004225',
            ])
            ->login()
            ->passwordReset()
            ->topNavigation()
            ->plugins([
                AuthUIEnhancerPlugin::make()
                    ->formPanelPosition('left')
                    ->formPanelWidth('45%')
                    ->emptyPanelBackgroundImageUrl(asset('images/Financieros.jpg'))
                    ->emptyPanelBackgroundImageOpacity('50%')
                    ->showEmptyPanelOnMobile(false),
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
            ->discoverResources(in: app_path('Filament/Financiero/Resources'), for: 'App\\Filament\\Financiero\\Resources')
            ->discoverPages(in: app_path('Filament/Financiero/Pages'), for: 'App\\Filament\\Financiero\\Pages')
            ->discoverWidgets(in: app_path('Filament/Financiero/Widgets'), for: 'App\\Filament\\Financiero\\Widgets')
            ->pages([Dashboard::class])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
                PagosCuotaVoluntaria::class,
            ])
            ->middleware([
                EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class,
                AuthenticateSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class,
                SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
