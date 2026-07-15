<?php

namespace App\Filament\Admin\Resources\HorarioResource\Pages;

use App\Filament\Admin\Resources\HorarioResource;
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
