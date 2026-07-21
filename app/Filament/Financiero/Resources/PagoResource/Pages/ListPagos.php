<?php

namespace App\Filament\Financiero\Resources\PagoResource\Pages;

use App\Filament\Financiero\Resources\PagoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPagos extends ListRecords
{
    protected static string $resource = PagoResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
