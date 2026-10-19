<?php

namespace App\Filament\Profesor\Resources\TutoriaIncidenciaRecibidaResource\Pages;

use App\Filament\Profesor\Resources\TutoriaIncidenciaRecibidaResource;
use Filament\Resources\Pages\ListRecords;

class ListTutoriaIncidenciasRecibidas extends ListRecords
{
    protected static string $resource = TutoriaIncidenciaRecibidaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
