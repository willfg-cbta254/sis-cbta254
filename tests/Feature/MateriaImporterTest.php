<?php

use App\Filament\Imports\MateriaImporter;
use App\Models\Materia;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function runMateriaImport(array $row): void
{
    $import = Import::create([
        'file_name' => 'materias.csv',
        'file_path' => 'materias.csv',
        'importer' => MateriaImporter::class,
        'total_rows' => 1,
        'user_id' => User::factory()->create()->id,
    ]);

    $columnMap = [
        'nombre' => 'nombre',
        'clave' => 'clave',
        'tipo' => 'tipo',
        'semestre_id' => 'semestre_id',
        'horas_semana' => 'horas_semana',
    ];

    (new MateriaImporter($import, $columnMap, []))($row);
}

it('imports a subject row', function () {
    runMateriaImport([
        'nombre' => 'Matemáticas',
        'clave' => 'MAT-101',
        'tipo' => 'Curriculum Fundamental',
        'semestre_id' => 'Primero',
        'horas_semana' => '5',
    ]);

    $materia = Materia::first();

    expect($materia)->not->toBeNull()
        ->and($materia->nombre)->toBe('Matemáticas')
        ->and($materia->clave)->toBe('MAT-101')
        ->and($materia->tipo->value)->toBe('Curriculum Fundamental')
        ->and($materia->semestre_id->value)->toBe('Primero')
        ->and($materia->horas_semana)->toBe(5);
});

it('imports a row that uses a numeric semester value', function () {
    runMateriaImport([
        'nombre' => 'Física',
        'clave' => 'FIS-201',
        'tipo' => 'Curriculum Laboral',
        'semestre_id' => '2',
        'horas_semana' => '4',
    ]);

    $materia = Materia::first();

    expect($materia)->not->toBeNull()
        ->and($materia->semestre_id->value)->toBe('Segundo');
});

it('runs the import synchronously in the local environment', function () {
    app()->detectEnvironment(fn (): string => 'local');

    expect((new MateriaImporter(new Import, [], []))->getJobConnection())->toBe('sync');
});
