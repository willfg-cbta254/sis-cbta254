<?php

namespace App\Providers\Filament;

use App\Filament\Alumno\Pages\MiHorario;
use App\Filament\Alumno\Pages\MisAsistencias;
use App\Filament\Alumno\Pages\MisCalificaciones;
use App\Filament\Alumno\Pages\MisHorasLaborSocial;
use App\Filament\Alumno\Pages\MisMaterias;
use App\Filament\Alumno\Pages\SeleccionarAreaPropedeutica;
use App\Filament\Alumno\Widgets\AlumnoStatsWidget;
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

class AlumnoPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('alumno')
            ->path('alumno')
            ->viteTheme('resources/css/filament/alumno/theme.css')
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
                    ->emptyPanelBackgroundImageUrl(asset('images/Alumnos.jpg'))
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
            ->discoverResources(in: app_path('Filament/Alumno/Resources'), for: 'App\\Filament\\Alumno\\Resources')
            ->discoverPages(in: app_path('Filament/Alumno/Pages'), for: 'App\\Filament\\Alumno\\Pages')
            ->discoverWidgets(in: app_path('Filament/Alumno/Widgets'), for: 'App\\Filament\\Alumno\\Widgets')
            ->pages([
                Dashboard::class,
                MisCalificaciones::class,
                MisAsistencias::class,
                MiHorario::class,
                MisMaterias::class,
                MisHorasLaborSocial::class,
                SeleccionarAreaPropedeutica::class,
            ])
            ->widgets([
                AccountWidget::class,
                AlumnoStatsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class,
                AuthenticateSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class,
                SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
