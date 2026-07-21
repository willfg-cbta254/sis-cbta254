<?php

namespace App\Filament\ServiciosEscolares\Resources\InscripcionResource\Pages;

use App\Filament\ServiciosEscolares\Resources\InscripcionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInscripcions extends ListRecords
{
    protected static string $resource = InscripcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
