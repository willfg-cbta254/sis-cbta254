<?php

namespace App\Filament\Profesor\Resources\TutoriaIncidenciaResource\Pages;

use App\Filament\Profesor\Resources\TutoriaIncidenciaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTutoriaIncidencia extends CreateRecord
{
    protected static string $resource = TutoriaIncidenciaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['registrado_por_id'] = auth()->user()?->profesor?->id;

        if (($data['tipo'] ?? null) === 'individual') {
            $data['alumnos_ids'] = array_values($data['alumnos'] ?? []);
        } else {
            $data['alumnos_ids'] = null;
        }

        unset($data['alumnos']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->tipo === 'individual' && is_array($this->record->alumnos_ids)) {
            $this->record->alumnos()->sync($this->record->alumnos_ids);
        }
    }
}
