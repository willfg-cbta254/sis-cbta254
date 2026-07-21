<?php

namespace App\Filament\ServiciosEscolares\Resources\CarreraResource\Pages;

use App\Filament\ServiciosEscolares\Resources\CarreraResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCarrera extends EditRecord
{
    protected static string $resource = CarreraResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
