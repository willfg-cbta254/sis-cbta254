<?php

namespace App\Filament\Admin\Resources\MateriaResource\Pages;

use App\Filament\Admin\Resources\MateriaResource;
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
