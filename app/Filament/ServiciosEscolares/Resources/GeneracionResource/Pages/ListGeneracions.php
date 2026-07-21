<?php

namespace App\Filament\ServiciosEscolares\Resources\GeneracionResource\Pages;

use App\Filament\ServiciosEscolares\Resources\GeneracionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGeneracions extends ListRecords
{
    protected static string $resource = GeneracionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
