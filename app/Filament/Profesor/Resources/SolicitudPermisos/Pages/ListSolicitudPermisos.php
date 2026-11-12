<?php

namespace App\Filament\Profesor\Resources\SolicitudPermisos\Pages;

use App\Filament\Profesor\Resources\SolicitudPermisos\SolicitudPermisoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSolicitudPermisos extends ListRecords
{
    protected static string $resource = SolicitudPermisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
