<?php

namespace App\Filament\Profesor\Resources\EstrategiaDidacticaResource\Pages;

use App\Filament\Profesor\Resources\EstrategiaDidacticaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEstrategiaDidactica extends CreateRecord
{
    protected static string $resource = EstrategiaDidacticaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['profesor_id'] = auth()->user()?->profesor?->id;

        return $data;
    }
}
