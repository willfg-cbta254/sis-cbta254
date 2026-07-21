<?php

namespace App\Filament\ServiciosEscolares\Resources\CarreraResource\Pages;

use App\Filament\ServiciosEscolares\Resources\CarreraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCarreras extends ListRecords
{
    protected static string $resource = CarreraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
