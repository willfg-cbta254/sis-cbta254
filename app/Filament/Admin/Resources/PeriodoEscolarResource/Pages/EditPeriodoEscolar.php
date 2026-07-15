<?php

namespace App\Filament\Admin\Resources\PeriodoEscolarResource\Pages;

use App\Filament\Admin\Resources\PeriodoEscolarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPeriodoEscolar extends EditRecord
{
    protected static string $resource = PeriodoEscolarResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
