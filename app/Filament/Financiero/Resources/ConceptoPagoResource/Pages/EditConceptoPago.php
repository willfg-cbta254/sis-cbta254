<?php

namespace App\Filament\Financiero\Resources\ConceptoPagoResource\Pages;

use App\Filament\Financiero\Resources\ConceptoPagoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConceptoPago extends EditRecord
{
    protected static string $resource = ConceptoPagoResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
