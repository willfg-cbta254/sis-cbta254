<?php

use App\Filament\Imports\GrupoImporter;
use App\Models\Carrera;
use App\Models\Generacion;
use App\Models\Grupo;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function runGrupoImport(array $row): void
{
    $import = Import::create([
        'file_name' => 'grupos.csv',
        'file_path' => 'grupos.csv',
        'importer' => GrupoImporter::class,
        'total_rows' => 1,
        'user_id' => User::factory()->create()->id,
    ]);

    $columnMap = [
        'nombre' => 'nombre',
        'semestre_id' => 'semestre_id',
        'carrera' => 'carrera',
        'generacion' => 'generacion',
        'turno' => 'turno',
        'capacidad' => 'capacidad',
        'activo' => 'activo',
    ];

    (new GrupoImporter($import, $columnMap, []))($row);
}

it('imports a row that uses numeric semester and turno values', function () {
    $carrera = Carrera::create(['clave' => 'BAC-TEC', 'nombre' => 'Bachillerato Tecnológico']);
    $generacion = Generacion::create(['clave' => '2026-2029', 'anio_inicio' => 2026, 'anio_fin' => 2029]);

    runGrupoImport([
        'nombre' => '1 "A"',
        'semestre_id' => '1',
        'carrera' => (string) $carrera->id,
        'generacion' => (string) $generacion->id,
        'turno' => '1',
        'capacidad' => '45',
        'activo' => 'TRUE',
    ]);

    $grupo = Grupo::first();

    expect($grupo)->not->toBeNull()
        ->and($grupo->nombre)->toBe('1 "A"')
        ->and($grupo->semestre_id->value)->toBe('Primero')
        ->and($grupo->carrera_id)->toBe($carrera->id)
        ->and($grupo->generacion_id)->toBe($generacion->id)
        ->and($grupo->turno)->toBe('Matutino')
        ->and($grupo->capacidad)->toBe(45)
        ->and($grupo->activo)->toBeTrue();
});

it('imports a row that uses semester and turno names', function () {
    Carrera::create(['clave' => 'BAC-TEC', 'nombre' => 'Bachillerato Tecnológico']);
    Generacion::create(['clave' => '2026-2029', 'anio_inicio' => 2026, 'anio_fin' => 2029]);

    runGrupoImport([
        'nombre' => '2 "B"',
        'semestre_id' => 'segundo',
        'carrera' => 'Bachillerato Tecnológico',
        'generacion' => '2026-2029',
        'turno' => 'vespertino',
        'capacidad' => '40',
        'activo' => '1',
    ]);

    $grupo = Grupo::first();

    expect($grupo)->not->toBeNull()
        ->and($grupo->semestre_id->value)->toBe('Segundo')
        ->and($grupo->turno)->toBe('Vespertino');
});

it('creates a missing generacion while importing a row', function () {
    Carrera::create(['clave' => 'BAC-TEC', 'nombre' => 'Bachillerato Tecnológico']);

    runGrupoImport([
        'nombre' => '3 "A"',
        'semestre_id' => 'Tercero',
        'carrera' => 'Bachillerato Tecnológico',
        'generacion' => '2025-2028',
        'turno' => 'Matutino',
        'capacidad' => '45',
        'activo' => '1',
    ]);

    $generacion = Generacion::where('clave', '2025-2028')->first();
    $grupo = Grupo::first();

    expect($generacion)->not->toBeNull()
        ->and((int) $generacion->anio_inicio)->toBe(2025)
        ->and((int) $generacion->anio_fin)->toBe(2028)
        ->and($grupo)->not->toBeNull()
        ->and($grupo->generacion_id)->toBe($generacion->id);
});

it('reuses an existing generacion instead of creating a duplicate', function () {
    Carrera::create(['clave' => 'BAC-TEC', 'nombre' => 'Bachillerato Tecnológico']);
    $generacion = Generacion::create(['clave' => '2025-2028', 'anio_inicio' => 2025, 'anio_fin' => 2028]);

    runGrupoImport([
        'nombre' => '3 "A"',
        'semestre_id' => 'Tercero',
        'carrera' => 'Bachillerato Tecnológico',
        'generacion' => '2025-2028',
        'turno' => 'Matutino',
        'capacidad' => '45',
        'activo' => '1',
    ]);

    expect(Generacion::count())->toBe(1)
        ->and(Grupo::first()->generacion_id)->toBe($generacion->id);
});
