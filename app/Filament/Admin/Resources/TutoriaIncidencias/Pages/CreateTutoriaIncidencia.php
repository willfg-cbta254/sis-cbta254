<?php

namespace App\Filament\Admin\Resources\TutoriaIncidencias\Pages;

use App\Filament\Admin\Resources\TutoriaIncidencias\TutoriaIncidenciaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTutoriaIncidencia extends CreateRecord
{
    protected static string $resource = TutoriaIncidenciaResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;
        if ($record->tipo === 'individual' && is_array($record->alumnos_ids)) {
            $record->alumnos()->sync($record->alumnos_ids);
        } elseif ($record->tipo === 'individual') {
            $alumnos = $this->data['alumnos'] ?? [];
            $record->alumnos()->sync($alumnos);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['alumnos'])) {
            $data['alumnos_ids'] = $data['alumnos'];
        }

        return $data;
    }
}
