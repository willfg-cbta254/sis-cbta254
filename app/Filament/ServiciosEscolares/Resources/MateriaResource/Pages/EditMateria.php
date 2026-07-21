<?php

namespace App\Filament\ServiciosEscolares\Resources\MateriaResource\Pages;

use App\Filament\ServiciosEscolares\Resources\MateriaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMateria extends EditRecord
{
    protected static string $resource = MateriaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
