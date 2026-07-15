<?php

namespace App\Filament\Admin\Resources\ProfesorResource\Pages;

use App\Filament\Admin\Resources\ProfesorResource;
use App\Models\Profesor;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateProfesor extends CreateRecord
{
    protected static string $resource = ProfesorResource::class;

    protected function handleRecordCreation(array $data): Profesor
    {
        $user = User::create($data['user']);
        $data['user_id'] = $user->id;
        unset($data['user']);

        return static::getModel()::create($data);
    }
}
