<?php

namespace App\Filament\ServiciosEscolares\Resources\GeneracionResource\Pages;

use App\Filament\ServiciosEscolares\Resources\GeneracionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGeneracion extends EditRecord
{
    protected static string $resource = GeneracionResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
