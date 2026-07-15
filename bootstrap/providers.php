<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\AlumnoPanelProvider;
use App\Providers\Filament\ProfesorPanelProvider;
use Laravel\Sanctum\SanctumServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    ProfesorPanelProvider::class,
    AlumnoPanelProvider::class,
    SanctumServiceProvider::class,
];
