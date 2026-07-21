<?php

namespace App\Filament\Profesor\Resources\EstrategiaDidacticaResource\Pages;

use App\Filament\Profesor\Resources\EstrategiaDidacticaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEstrategiaDidacticas extends ListRecords
{
    protected static string $resource = EstrategiaDidacticaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Subir Estrategia')];
    }
}
