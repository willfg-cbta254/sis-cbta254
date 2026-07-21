<?php

namespace App\Filament\ServiciosEscolares\Resources\GrupoResource\Pages;

use App\Filament\ServiciosEscolares\Resources\GrupoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGrupos extends ListRecords
{
    protected static string $resource = GrupoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
