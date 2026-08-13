<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\CorteCaja;
use App\Filament\Admin\Pages\EstrategiasPorDocente;
use App\Filament\Admin\Widgets\AlumnosPorSemestreChart;
use App\Filament\Admin\Widgets\AsistenciasHoyChart;
use App\Filament\Admin\Widgets\CalificacionesPorMateriaChart;
use App\Filament\Admin\Widgets\PagosCuotaVoluntaria;
use App\Filament\Admin\Widgets\StatsOverviewWidget;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->favicon(asset('images/favicon.ico'))
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('3rem')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->colors([
                'primary' => '#004225',
            ])
            ->sidebarWidth('15rem')
            ->globalSearch(false)
            ->passwordReset()
            ->emailVerification()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->plugins([
                AuthUIEnhancerPlugin::make()
                    ->formPanelPosition('left')
                    ->formPanelWidth('45%')
                    ->emptyPanelBackgroundImageUrl(asset('images/admin.jpg'))
                    ->emptyPanelBackgroundImageOpacity('60%')
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
                FilamentShieldPlugin::make(),
            ])
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->pages([
                Dashboard::class,
                CorteCaja::class,
                EstrategiasPorDocente::class,
            ])
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
                StatsOverviewWidget::class,
                PagosCuotaVoluntaria::class,
                AsistenciasHoyChart::class,
                AlumnosPorSemestreChart::class,
                CalificacionesPorMateriaChart::class,
            ])
            ->middleware([
                EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class,
                AuthenticateSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class,
                SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class])
            ->topNavigation();
    }
}
