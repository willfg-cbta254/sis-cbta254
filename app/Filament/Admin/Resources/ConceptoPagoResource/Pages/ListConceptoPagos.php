<?php

namespace App\Filament\Admin\Resources\ConceptoPagoResource\Pages;

use App\Filament\Admin\Resources\ConceptoPagoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConceptoPagos extends ListRecords
{
    protected static string $resource = ConceptoPagoResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
