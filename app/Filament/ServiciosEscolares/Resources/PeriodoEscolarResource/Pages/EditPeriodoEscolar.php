<?php

namespace App\Filament\ServiciosEscolares\Resources\PeriodoEscolarResource\Pages;

use App\Filament\ServiciosEscolares\Resources\PeriodoEscolarResource;
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
