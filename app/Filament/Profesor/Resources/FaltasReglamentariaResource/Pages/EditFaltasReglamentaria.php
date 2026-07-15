<?php

namespace App\Filament\Profesor\Resources\FaltasReglamentariaResource\Pages;

use App\Filament\Profesor\Resources\FaltasReglamentariaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFaltasReglamentaria extends EditRecord
{
    protected static string $resource = FaltasReglamentariaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
