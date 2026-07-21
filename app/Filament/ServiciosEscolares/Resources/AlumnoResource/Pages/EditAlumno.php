<?php

namespace App\Filament\ServiciosEscolares\Resources\AlumnoResource\Pages;

use App\Filament\ServiciosEscolares\Resources\AlumnoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAlumno extends EditRecord
{
    protected static string $resource = AlumnoResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
