<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\AlumnoPanelProvider;
use App\Providers\Filament\FinancieroPanelProvider;
use App\Providers\Filament\ProfesorPanelProvider;
use App\Providers\Filament\ServiciosEscolaresPanelProvider;
use Laravel\Sanctum\SanctumServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    FinancieroPanelProvider::class,
    ProfesorPanelProvider::class,
    AlumnoPanelProvider::class,
    ServiciosEscolaresPanelProvider::class,
    SanctumServiceProvider::class,
];
