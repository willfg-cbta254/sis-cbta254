<?php

namespace App\Filament\Admin\Resources\ConceptoPagoResource\Pages;

use App\Filament\Admin\Resources\ConceptoPagoResource;
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
