<?php

namespace App\Filament\Admin\Resources\GrupoResource\Pages;

use App\Filament\Admin\Resources\GrupoResource;
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
