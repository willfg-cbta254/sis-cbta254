<?php

namespace App\Filament\Profesor\Resources\AsistenciaResource\Pages;

use App\Filament\Profesor\Resources\AsistenciaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAsistencias extends ListRecords
{
    protected static string $resource = AsistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
