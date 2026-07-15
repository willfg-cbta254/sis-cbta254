<?php

namespace App\Filament\Admin\Resources\FaltasReglamentariaResource\Pages;

use App\Filament\Admin\Resources\FaltasReglamentariaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFaltasReglamentarias extends ListRecords
{
    protected static string $resource = FaltasReglamentariaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
