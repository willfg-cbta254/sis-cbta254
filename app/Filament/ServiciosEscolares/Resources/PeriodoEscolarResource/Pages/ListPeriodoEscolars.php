<?php

namespace App\Filament\ServiciosEscolares\Resources\PeriodoEscolarResource\Pages;

use App\Filament\ServiciosEscolares\Resources\PeriodoEscolarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPeriodoEscolars extends ListRecords
{
    protected static string $resource = PeriodoEscolarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
