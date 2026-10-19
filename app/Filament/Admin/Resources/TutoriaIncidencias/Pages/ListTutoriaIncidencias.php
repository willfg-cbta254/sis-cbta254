<?php

namespace App\Filament\Admin\Resources\TutoriaIncidencias\Pages;

use App\Filament\Admin\Resources\TutoriaIncidencias\TutoriaIncidenciaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTutoriaIncidencias extends ListRecords
{
    protected static string $resource = TutoriaIncidenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
