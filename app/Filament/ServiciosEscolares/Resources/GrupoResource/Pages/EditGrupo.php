<?php

namespace App\Filament\ServiciosEscolares\Resources\GrupoResource\Pages;

use App\Filament\ServiciosEscolares\Resources\GrupoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGrupo extends EditRecord
{
    protected static string $resource = GrupoResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
