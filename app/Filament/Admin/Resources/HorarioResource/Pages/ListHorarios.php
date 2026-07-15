<?php

namespace App\Filament\Admin\Resources\HorarioResource\Pages;

use App\Filament\Admin\Resources\HorarioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHorarios extends ListRecords
{
    protected static string $resource = HorarioResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
