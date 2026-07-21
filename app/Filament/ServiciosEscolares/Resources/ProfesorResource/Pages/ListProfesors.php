<?php

namespace App\Filament\ServiciosEscolares\Resources\ProfesorResource\Pages;

use App\Filament\ServiciosEscolares\Resources\ProfesorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProfesors extends ListRecords
{
    protected static string $resource = ProfesorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
