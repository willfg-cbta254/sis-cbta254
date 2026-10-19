<?php

namespace App\Filament\ServiciosEscolares\Resources\SeleccionAreaPropedeuticaResource\Pages;

use App\Filament\ServiciosEscolares\Resources\SeleccionAreaPropedeuticaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeleccionAreasPropedeuticas extends ListRecords
{
    protected static string $resource = SeleccionAreaPropedeuticaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
