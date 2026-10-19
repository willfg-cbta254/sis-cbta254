<?php

use App\Filament\Imports\ProfesorImporter;
use App\Models\Profesor;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('Profesor');
});

function runProfesorImport(array $row): void
{
    $import = Import::create([
        'file_name' => 'profesores.csv',
        'file_path' => 'profesores.csv',
        'importer' => ProfesorImporter::class,
        'total_rows' => 1,
        'user_id' => User::factory()->create()->id,
    ]);

    $columnMap = [
        'email' => 'email',
        'name' => 'name',
        'password' => 'password',
        'curp' => 'curp',
        'telefono' => 'telefono',
        'fecha_nacimiento' => 'fecha_nacimiento',
        'direccion' => 'direccion',
        'numero_empleado' => 'numero_empleado',
        'fecha_ingreso' => 'fecha_ingreso',
        'especialidad' => 'especialidad',
    ];

    (new ProfesorImporter($import, $columnMap, []))($row);
}

it('imports a teacher with dates in d/m/Y format', function () {
    runProfesorImport([
        'email' => 'profesor@cbta254.edu.mx',
        'name' => 'María López Hernández',
        'password' => 'password123',
        'curp' => 'AAGJ831208HPLLLS02',
        'telefono' => '2221234567',
        'fecha_nacimiento' => '15/03/1985',
        'direccion' => 'Calle Falsa 123',
        'numero_empleado' => '245001',
        'fecha_ingreso' => '16/08/2007',
        'especialidad' => 'Ingeniero',
    ]);

    $profesor = Profesor::first();

    expect($profesor)->not->toBeNull()
        ->and($profesor->numero_empleado)->toBe('245001')
        ->and($profesor->fecha_ingreso->format('Y-m-d'))->toBe('2007-08-16')
        ->and($profesor->especialidad)->toBe('Ingeniero')
        ->and($profesor->user->name)->toBe('María López Hernández')
        ->and($profesor->user->hasRole('Profesor'))->toBeTrue();
});
