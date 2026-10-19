<?php

namespace App\Filament\Admin\Resources\TutoriaIncidencias\Pages;

use App\Filament\Admin\Resources\TutoriaIncidencias\TutoriaIncidenciaResource;
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

    protected function afterSave(): void
    {
        $record = $this->record;
        if ($record->tipo === 'individual') {
            $alumnos = $this->data['alumnos'] ?? $record->alumnos_ids ?? [];
            $record->alumnos()->sync($alumnos);
            $record->alumnos_ids = $alumnos;
            $record->save();
        } else {
            $record->alumnos()->sync([]);
            $record->alumnos_ids = null;
            $record->save();
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['alumnos'])) {
            $data['alumnos_ids'] = $data['alumnos'];
        }

        return $data;
    }
}
