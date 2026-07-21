<?php

namespace App\Filament\ServiciosEscolares\Resources\HorarioResource\Pages;

use App\Filament\ServiciosEscolares\Resources\HorarioResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHorario extends EditRecord
{
    protected static string $resource = HorarioResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
