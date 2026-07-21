<?php

namespace App\Filament\ServiciosEscolares\Resources\AlumnoResource\Pages;

use App\Filament\ServiciosEscolares\Resources\AlumnoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAlumnos extends ListRecords
{
    protected static string $resource = AlumnoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
