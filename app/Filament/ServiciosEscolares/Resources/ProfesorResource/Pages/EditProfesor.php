<?php

namespace App\Filament\ServiciosEscolares\Resources\ProfesorResource\Pages;

use App\Filament\ServiciosEscolares\Resources\ProfesorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProfesor extends EditRecord
{
    protected static string $resource = ProfesorResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
