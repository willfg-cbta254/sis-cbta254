<?php

namespace App\Filament\ServiciosEscolares\Resources\GrupoResource\Pages;

use App\Filament\ServiciosEscolares\Resources\GrupoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGrupo extends ViewRecord
{
    protected static string $resource = GrupoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
