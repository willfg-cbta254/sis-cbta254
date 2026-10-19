<?php

namespace App\Filament\Profesor\Resources\TutoriaIncidenciaResource\Pages;

use App\Filament\Profesor\Resources\TutoriaIncidenciaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTutoriaIncidencia extends EditRecord
{
    protected static string $resource = TutoriaIncidenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['alumnos'] = $this->record->alumnos()->pluck('alumnos.id')->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['tipo'] ?? null) === 'individual') {
            $data['alumnos_ids'] = array_values($data['alumnos'] ?? []);
        } else {
            $data['alumnos_ids'] = null;
        }

        unset($data['alumnos']);

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->tipo === 'individual' && is_array($this->record->alumnos_ids)) {
            $this->record->alumnos()->sync($this->record->alumnos_ids);
        } else {
            $this->record->alumnos()->sync([]);
        }
    }
}
