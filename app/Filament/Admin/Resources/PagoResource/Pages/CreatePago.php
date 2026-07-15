<?php

namespace App\Filament\Admin\Resources\PagoResource\Pages;

use App\Filament\Admin\Resources\PagoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePago extends CreateRecord
{
    protected static string $resource = PagoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['usuario_id'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->referencia = $this->record->id + 100;
        $this->record->save();
    }
}
