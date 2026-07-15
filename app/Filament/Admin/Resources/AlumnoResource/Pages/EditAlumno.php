<?php

namespace App\Filament\Admin\Resources\AlumnoResource\Pages;

use App\Filament\Admin\Resources\AlumnoResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAlumno extends EditRecord
{
    protected static string $resource = AlumnoResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($user = $this->record?->user) {
            $data['user'] = $user->toArray();
            unset($data['user']['password']);
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $userData = $data['user'];
        unset($data['user']);

        if ($record->user) {
            $record->user->update($userData);
        } else {
            $user = User::create($userData);
            $data['user_id'] = $user->id;
        }

        $record->update($data);

        return $record;
    }
}
