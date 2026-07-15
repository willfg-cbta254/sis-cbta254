<?php

namespace App\Filament\Admin\Resources\CarreraResource\Pages;

use App\Filament\Admin\Resources\CarreraResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCarrera extends EditRecord
{
    protected static string $resource = CarreraResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
