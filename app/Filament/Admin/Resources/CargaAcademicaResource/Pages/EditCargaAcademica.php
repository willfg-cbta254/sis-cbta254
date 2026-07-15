<?php

namespace App\Filament\Admin\Resources\CargaAcademicaResource\Pages;

use App\Filament\Admin\Resources\CargaAcademicaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCargaAcademica extends EditRecord
{
    protected static string $resource = CargaAcademicaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
