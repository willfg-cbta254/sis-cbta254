<?php

use App\Filament\Admin\Resources\TutoriaTutors\Schemas\TutoriaTutorForm;
use App\Models\Carrera;
use App\Models\Generacion;
use App\Models\Grupo;
use App\Models\Profesor;
use App\Models\TutoriaTutor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createGrupoConCarrera(string $nombre, Carrera $carrera, Generacion $generacion, ?Profesor $tutor = null): Grupo
{
    return Grupo::create([
        'nombre' => $nombre,
        'semestre_id' => 'Primero',
        'carrera_id' => $carrera->id,
        'generacion_id' => $generacion->id,
        'turno' => 'Matutino',
        'capacidad' => 45,
        'activo' => true,
        'tutor_id' => $tutor?->id,
    ]);
}

function createProfesor(): Profesor
{
    return Profesor::create([
        'user_id' => User::factory()->create()->id,
        'numero_empleado' => 'EMP-'.fake()->unique()->numerify('####'),
    ]);
}

it('muestra el grupo y la carrera en las opciones del grupo asignado', function () {
    $carrera = Carrera::create(['clave' => 'BAC-TEC', 'nombre' => 'Bachillerato Tecnológico']);
    $generacion = Generacion::create(['clave' => '2026-2029', 'anio_inicio' => 2026, 'anio_fin' => 2029]);
    $grupo = createGrupoConCarrera('1 "A"', $carrera, $generacion);

    expect(TutoriaTutorForm::grupoOptions())
        ->toBe([$grupo->id => '1 "A" - Bachillerato Tecnológico']);
});

it('no muestra grupos que ya tienen tutor asignado', function () {
    $carrera = Carrera::create(['clave' => 'BAC-TEC', 'nombre' => 'Bachillerato Tecnológico']);
    $generacion = Generacion::create(['clave' => '2026-2029', 'anio_inicio' => 2026, 'anio_fin' => 2029]);

    createGrupoConCarrera('1 "A"', $carrera, $generacion, createProfesor());
    $libre = createGrupoConCarrera('1 "B"', $carrera, $generacion);

    $options = TutoriaTutorForm::grupoOptions();

    expect($options)->toHaveCount(1)
        ->and($options)->toHaveKey($libre->id);
});

it('incluye el grupo ya asignado al docente al editar un tutor', function () {
    $carrera = Carrera::create(['clave' => 'BAC-TEC', 'nombre' => 'Bachillerato Tecnológico']);
    $generacion = Generacion::create(['clave' => '2026-2029', 'anio_inicio' => 2026, 'anio_fin' => 2029]);

    $profesor = createProfesor();
    $asignado = createGrupoConCarrera('1 "A"', $carrera, $generacion, $profesor);
    createGrupoConCarrera('1 "B"', $carrera, $generacion);

    $tutoriaTutor = TutoriaTutor::create(['profesor_id' => $profesor->id, 'activo' => true]);

    $options = TutoriaTutorForm::grupoOptions($tutoriaTutor);

    expect($options)->toHaveCount(2)
        ->and($options)->toHaveKey($asignado->id);
});
