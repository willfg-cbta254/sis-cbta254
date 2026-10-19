<?php

use App\Filament\Imports\AlumnoImporter;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Generacion;
use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\PeriodoEscolar;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('Alumno');
    Role::findOrCreate('Profesor');
});

function runAlumnoImport(array $row): void
{
    $import = Import::create([
        'file_name' => 'alumnos.csv',
        'file_path' => 'alumnos.csv',
        'importer' => AlumnoImporter::class,
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
        'matricula' => 'matricula',
        'generacion' => 'generacion',
        'carrera' => 'carrera',
        'semestre_actual_id' => 'semestre_actual_id',
        'fecha_ingreso' => 'fecha_ingreso',
        'grupo' => 'grupo',
    ];

    (new AlumnoImporter($import, $columnMap, []))($row);
}

it('imports a student with a numeric semester value', function () {
    $carrera = Carrera::create(['clave' => 'BAC-TEC', 'nombre' => 'Bachillerato Tecnológico']);
    $generacion = Generacion::create(['clave' => '2026-2029', 'anio_inicio' => 2026, 'anio_fin' => 2029]);

    Grupo::create([
        'nombre' => '1 "A"',
        'semestre_id' => 'Primero',
        'carrera_id' => $carrera->id,
        'generacion_id' => $generacion->id,
        'turno' => 'Matutino',
        'capacidad' => 45,
        'activo' => true,
    ]);

    PeriodoEscolar::create([
        'clave' => '2026-B',
        'fecha_inicio' => '2026-08-01',
        'fecha_fin' => '2027-01-15',
        'activo' => true,
    ]);

    runAlumnoImport([
        'email' => 'alumno@cbta254.edu.mx',
        'name' => 'Juan Pérez López',
        'password' => 'password123',
        'curp' => 'AAGJ831208HPLLLS02',
        'telefono' => '2221234567',
        'fecha_nacimiento' => '15/03/2008',
        'direccion' => 'Calle Falsa 123',
        'matricula' => '2026-0001',
        'generacion' => '2026-2029',
        'carrera' => 'Bachillerato Tecnológico',
        'semestre_actual_id' => '1',
        'fecha_ingreso' => '26/08/2026',
        'grupo' => '1 "A"',
    ]);

    $alumno = Alumno::first();

    expect($alumno)->not->toBeNull()
        ->and($alumno->matricula)->toBe('2026-0001')
        ->and($alumno->semestre_actual_id->value)->toBe('Primero')
        ->and($alumno->carrera_id)->toBe($carrera->id)
        ->and($alumno->generacion_id)->toBe($generacion->id)
        ->and($alumno->fecha_ingreso->format('Y-m-d'))->toBe('2026-08-26')
        ->and($alumno->grupos()->count())->toBe(1)
        ->and($alumno->user->hasRole('Alumno'))->toBeTrue();

    $inscripcion = Inscripcion::first();

    expect($inscripcion)->not->toBeNull()
        ->and($inscripcion->alumno_id)->toBe($alumno->id)
        ->and($inscripcion->grupo_id)->toBe(Grupo::first()->id)
        ->and($inscripcion->periodo_escolar_id)->toBe(PeriodoEscolar::first()->id)
        ->and($inscripcion->status)->toBe('Inscrito')
        ->and($inscripcion->fecha_inscripcion->format('Y-m-d'))->toBe('2026-08-26');
});

it('does not duplicate the inscription when the same student is imported again', function () {
    Carrera::create(['clave' => 'BAC-TEC', 'nombre' => 'Bachillerato Tecnológico']);
    $generacion = Generacion::create(['clave' => '2026-2029', 'anio_inicio' => 2026, 'anio_fin' => 2029]);
    $carrera = Carrera::first();

    Grupo::create([
        'nombre' => '1 "A"',
        'semestre_id' => 'Primero',
        'carrera_id' => $carrera->id,
        'generacion_id' => $generacion->id,
        'turno' => 'Matutino',
        'capacidad' => 45,
        'activo' => true,
    ]);

    PeriodoEscolar::create([
        'clave' => '2026-B',
        'fecha_inicio' => '2026-08-01',
        'fecha_fin' => '2027-01-15',
        'activo' => true,
    ]);

    $row = [
        'email' => 'alumno@cbta254.edu.mx',
        'name' => 'Juan Pérez López',
        'password' => 'password123',
        'curp' => 'AAGJ831208HPLLLS02',
        'telefono' => '2221234567',
        'fecha_nacimiento' => '15/03/2008',
        'direccion' => 'Calle Falsa 123',
        'matricula' => '2026-0001',
        'generacion' => '2026-2029',
        'carrera' => 'Bachillerato Tecnológico',
        'semestre_actual_id' => '1',
        'fecha_ingreso' => '26/08/2026',
        'grupo' => '1 "A"',
    ];

    runAlumnoImport($row);
    runAlumnoImport($row);

    expect(Inscripcion::count())->toBe(1)
        ->and(Alumno::count())->toBe(1);
});
