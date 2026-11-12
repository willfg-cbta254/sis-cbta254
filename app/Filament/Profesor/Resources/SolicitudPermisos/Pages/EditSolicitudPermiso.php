<?php

namespace App\Filament\Profesor\Resources\SolicitudPermisos\Pages;

use App\Filament\Profesor\Resources\SolicitudPermisos\SolicitudPermisoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSolicitudPermiso extends EditRecord
{
    protected static string $resource = SolicitudPermisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
