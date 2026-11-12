<?php

namespace App\Filament\Profesor\Resources\SolicitudPermisos\Pages;

use App\Filament\Profesor\Resources\SolicitudPermisos\SolicitudPermisoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSolicitudPermiso extends CreateRecord
{
    protected static string $resource = SolicitudPermisoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['profesor_id'] = auth()->user()?->profesor?->id;
        $data['estado'] = 'pendiente';

        return $data;
    }
}
