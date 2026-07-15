<?php

namespace App\Filament\Admin\Resources\FichaResource\Pages;

use App\Filament\Admin\Resources\FichaResource\FichaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFicha extends ViewRecord
{
    protected static string $resource = FichaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
