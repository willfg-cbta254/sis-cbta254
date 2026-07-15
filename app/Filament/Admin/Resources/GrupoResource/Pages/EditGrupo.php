<?php

namespace App\Filament\Admin\Resources\GrupoResource\Pages;

use App\Filament\Admin\Resources\GrupoResource;
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
