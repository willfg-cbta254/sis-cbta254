<?php

namespace App\Filament\Profesor\Resources\AsistenciaResource\Pages;

use App\Filament\Profesor\Resources\AsistenciaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAsistencia extends CreateRecord
{
    protected static string $resource = AsistenciaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['profesor_id'] ??= auth()->user()?->profesor?->id;

        return $data;
    }
}
