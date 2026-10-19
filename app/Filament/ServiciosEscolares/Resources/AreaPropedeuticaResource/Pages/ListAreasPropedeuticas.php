<?php

namespace App\Filament\ServiciosEscolares\Resources\AreaPropedeuticaResource\Pages;

use App\Filament\ServiciosEscolares\Resources\AreaPropedeuticaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAreasPropedeuticas extends ListRecords
{
    protected static string $resource = AreaPropedeuticaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
