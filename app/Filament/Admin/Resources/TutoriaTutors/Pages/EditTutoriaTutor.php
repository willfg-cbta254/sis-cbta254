<?php

namespace App\Filament\Admin\Resources\TutoriaTutors\Pages;

use App\Filament\Admin\Resources\TutoriaTutors\TutoriaTutorResource;
use App\Models\Grupo;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTutoriaTutor extends EditRecord
{
    protected static string $resource = TutoriaTutorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $data = $this->data;
        if (isset($data['grupo_id'])) {
            Grupo::where('tutor_id', $this->record->profesor_id)->update(['tutor_id' => null]);
            if (! empty($data['grupo_id'])) {
                Grupo::where('id', $data['grupo_id'])->update([
                    'tutor_id' => $this->record->profesor_id,
                ]);
            }
        }
    }
}
