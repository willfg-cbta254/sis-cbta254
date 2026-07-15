<?php

namespace App\Filament\Admin\Resources\AsistenciaResource\Pages;

use App\Filament\Admin\Resources\AsistenciaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAsistencia extends EditRecord
{
    protected static string $resource = AsistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
