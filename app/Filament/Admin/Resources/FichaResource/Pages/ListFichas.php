<?php

namespace App\Filament\Admin\Resources\FichaResource\Pages;

use App\Filament\Admin\Resources\FichaResource\FichaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFichas extends ListRecords
{
    protected static string $resource = FichaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
