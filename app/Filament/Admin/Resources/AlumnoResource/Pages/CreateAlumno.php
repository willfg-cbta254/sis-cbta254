<?php

namespace App\Filament\Admin\Resources\AlumnoResource\Pages;

use App\Filament\Admin\Resources\AlumnoResource;
use App\Models\Alumno;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateAlumno extends CreateRecord
{
    protected static string $resource = AlumnoResource::class;

    protected function handleRecordCreation(array $data): Alumno
    {
        $user = User::create($data['user']);
        $data['user_id'] = $user->id;
        unset($data['user']);

        return static::getModel()::create($data);
    }
}
