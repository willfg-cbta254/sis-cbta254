<?php

namespace App\Providers\Filament;

use App\Filament\Profesor\Widgets\ProfesorStatsWidget;
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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class ProfesorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('profesor')
            ->path('profesor')
            ->viteTheme('resources/css/filament/profesor/theme.css')
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
                AuthUIEnhancerPlugin::make()
                    ->formPanelPosition('left')
                    ->formPanelWidth('46%')
                    ->emptyPanelBackgroundImageUrl(asset('images/Teacher.jpg'))
                    ->emptyPanelBackgroundImageOpacity('0%')
                    ->emptyPanelView('filament.auth.empty-panel')
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
            ->discoverResources(in: app_path('Filament/Profesor/Resources'), for: 'App\\Filament\\Profesor\\Resources')
            ->discoverPages(in: app_path('Filament/Profesor/Pages'), for: 'App\\Filament\\Profesor\\Pages')
            ->discoverWidgets(in: app_path('Filament/Profesor/Widgets'), for: 'App\\Filament\\Profesor\\Widgets')
            ->pages([Dashboard::class])
            ->widgets([
                AccountWidget::class,
                ProfesorStatsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class,
                AuthenticateSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class,
                SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
