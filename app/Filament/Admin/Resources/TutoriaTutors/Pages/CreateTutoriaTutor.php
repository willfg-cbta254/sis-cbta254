<?php

namespace App\Filament\Admin\Resources\TutoriaTutors\Pages;

use App\Filament\Admin\Resources\TutoriaTutors\TutoriaTutorResource;
use App\Models\Grupo;
use Filament\Resources\Pages\CreateRecord;

class CreateTutoriaTutor extends CreateRecord
{
    protected static string $resource = TutoriaTutorResource::class;

    protected function afterCreate(): void
    {
        $data = $this->data;
        if (! empty($data['grupo_id']) && $this->record->profesor_id) {
            Grupo::where('id', $data['grupo_id'])->update([
                'tutor_id' => $this->record->profesor_id,
            ]);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
}
