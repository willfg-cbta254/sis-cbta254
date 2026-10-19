<?php

namespace App\Filament\Profesor\Resources\TutoriaIncidenciaResource\Pages;

use App\Filament\Profesor\Resources\TutoriaIncidenciaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTutoriaIncidencias extends ListRecords
{
    protected static string $resource = TutoriaIncidenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Reportar Incidencia'),
        ];
    }
}
