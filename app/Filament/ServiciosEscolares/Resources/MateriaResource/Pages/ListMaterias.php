<?php

namespace App\Filament\ServiciosEscolares\Resources\MateriaResource\Pages;

use App\Filament\ServiciosEscolares\Resources\MateriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaterias extends ListRecords
{
    protected static string $resource = MateriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
