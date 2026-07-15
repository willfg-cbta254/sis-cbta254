<?php

namespace App\Filament\Admin\Resources\CarreraResource\Pages;

use App\Filament\Admin\Resources\CarreraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCarreras extends ListRecords
{
    protected static string $resource = CarreraResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
