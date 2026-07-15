<?php

namespace App\Filament\Admin\Resources\InscripcionResource\Pages;

use App\Filament\Admin\Resources\InscripcionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInscripcion extends EditRecord
{
    protected static string $resource = InscripcionResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
