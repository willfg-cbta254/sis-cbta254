<?php

use App\Filament\Profesor\Resources\TutoriaIncidenciaRecibidaResource;
use App\Filament\Profesor\Resources\TutoriaIncidenciaRecibidaResource\Pages\ListTutoriaIncidenciasRecibidas;
use App\Filament\Profesor\Resources\TutoriaIncidenciaResource;
use App\Filament\Profesor\Resources\TutoriaIncidenciaResource\Pages\CreateTutoriaIncidencia;
use App\Filament\Profesor\Resources\TutoriaIncidenciaResource\Pages\ListTutoriaIncidencias;
use App\Models\Alumno;
use App\Models\CargaAcademica;
use App\Models\Carrera;
use App\Models\Generacion;
use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Profesor;
use App\Models\TutoriaIncidencia;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function tutoriaProfesor(): Profesor
{
    return Profesor::create([
        'user_id' => User::factory()->create()->id,
        'numero_empleado' => 'EMP-'.fake()->unique()->numerify('####'),
    ]);
}

function tutoriaGrupo(?Profesor $tutor = null): Grupo
{
    $carrera = Carrera::firstOrCreate(['clave' => 'BAC-TEC'], ['nombre' => 'Bachillerato Tecnológico']);
    $generacion = Generacion::firstOrCreate(['clave' => '2026-2029'], ['anio_inicio' => 2026, 'anio_fin' => 2029]);

    return Grupo::create([
        'nombre' => '1 "A" '.fake()->unique()->numerify('##'),
        'semestre_id' => 'Primero',
        'carrera_id' => $carrera->id,
        'generacion_id' => $generacion->id,
        'turno' => 'Matutino',
        'capacidad' => 45,
        'activo' => true,
        'tutor_id' => $tutor?->id,
    ]);
}

function tutoriaPeriodo(): PeriodoEscolar
{
    return PeriodoEscolar::firstOrCreate(['clave' => '2026-1'], [
        'fecha_inicio' => now()->startOfYear(),
        'fecha_fin' => now()->endOfYear(),
        'activo' => true,
    ]);
}

function tutoriaMateria(): Materia
{
    return Materia::firstOrCreate(['clave' => 'MAT-EDU'], [
        'nombre' => 'Matemáticas',
        'semestre_id' => 'Primero',
        'horas_semana' => 3,
    ]);
}

function tutoriaCargaAcademica(Profesor $profesor, Grupo $grupo): void
{
    CargaAcademica::create([
        'profesor_id' => $profesor->id,
        'grupo_id' => $grupo->id,
        'materia_id' => tutoriaMateria()->id,
        'periodo_escolar_id' => tutoriaPeriodo()->id,
    ]);
}

function tutoriaAlumno(Grupo $grupo): Alumno
{
    $carrera = Carrera::firstOrCreate(['clave' => 'BAC-TEC'], ['nombre' => 'Bachillerato Tecnológico']);
    $generacion = Generacion::firstOrCreate(['clave' => '2026-2029'], ['anio_inicio' => 2026, 'anio_fin' => 2029]);

    $alumno = Alumno::create([
        'user_id' => User::factory()->create()->id,
        'matricula' => 'MAT-'.fake()->unique()->numerify('#####'),
        'generacion_id' => $generacion->id,
        'carrera_id' => $carrera->id,
        'semestre_actual_id' => 'Primero',
        'fecha_ingreso' => now()->toDateString(),
    ]);

    Inscripcion::create([
        'alumno_id' => $alumno->id,
        'grupo_id' => $grupo->id,
        'periodo_escolar_id' => tutoriaPeriodo()->id,
        'fecha_inscripcion' => now()->toDateString(),
        'status' => 'Inscrito',
    ]);

    return $alumno;
}

function tutoriaIncidencia(Grupo $grupo, Profesor $tutor, ?Profesor $registradoPor = null): TutoriaIncidencia
{
    $periodo = tutoriaPeriodo();

    return TutoriaIncidencia::create([
        'grupo_id' => $grupo->id,
        'tutor_id' => $tutor->id,
        'tipo' => 'grupal',
        'descripcion' => 'Incidencia de prueba',
        'periodo_escolar_id' => $periodo->id,
        'registrado_por_id' => $registradoPor?->id,
    ]);
}

it('solo lista las incidencias reportadas por el profesor autenticado', function () {
    $docente = tutoriaProfesor();
    $otroDocente = tutoriaProfesor();
    $tutor = tutoriaProfesor();
    $grupo = tutoriaGrupo($tutor);

    $propia = tutoriaIncidencia($grupo, $tutor, $docente);
    tutoriaIncidencia($grupo, $tutor, $otroDocente);

    $this->actingAs($docente->user);

    expect(TutoriaIncidenciaResource::getEloquentQuery()->pluck('id')->all())
        ->toBe([$propia->id]);
});

it('no permite ver las incidencias recibidas a un profesor que no es tutor', function () {
    $docente = tutoriaProfesor();

    $this->actingAs($docente->user);

    expect(TutoriaIncidenciaRecibidaResource::canViewAny())->toBeFalse();
});

it('permite ver las incidencias recibidas a un profesor asignado como tutor de un grupo', function () {
    $tutor = tutoriaProfesor();
    tutoriaGrupo($tutor);

    $this->actingAs($tutor->user);

    expect(TutoriaIncidenciaRecibidaResource::canViewAny())->toBeTrue();
});

it('lista al tutor las incidencias que le enviaron otros docentes del grupo', function () {
    $tutor = tutoriaProfesor();
    $docente = tutoriaProfesor();
    $grupo = tutoriaGrupo($tutor);

    $recibida = tutoriaIncidencia($grupo, $tutor, $docente);
    $propia = tutoriaIncidencia($grupo, $tutor, $tutor);
    $ajena = tutoriaIncidencia(tutoriaGrupo(tutoriaProfesor()), tutoriaProfesor(), $docente);

    $this->actingAs($tutor->user);

    expect(TutoriaIncidenciaRecibidaResource::getEloquentQuery()->pluck('id')->all())
        ->toBe([$recibida->id])
        ->not->toContain($propia->id, $ajena->id);
});

it('renderiza el listado de incidencias reportadas por el profesor', function () {
    Filament::setCurrentPanel(Filament::getPanel('profesor'));

    $docente = tutoriaProfesor();
    $tutor = tutoriaProfesor();
    $grupo = tutoriaGrupo($tutor);
    $propia = tutoriaIncidencia($grupo, $tutor, $docente);

    $this->actingAs($docente->user);

    Livewire::test(ListTutoriaIncidencias::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$propia]);
});

it('renderiza el listado de incidencias recibidas para el tutor', function () {
    Filament::setCurrentPanel(Filament::getPanel('profesor'));

    $tutor = tutoriaProfesor();
    $docente = tutoriaProfesor();
    $grupo = tutoriaGrupo($tutor);
    $recibida = tutoriaIncidencia($grupo, $tutor, $docente);

    $this->actingAs($tutor->user);

    Livewire::test(ListTutoriaIncidenciasRecibidas::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$recibida]);
});

it('permite al profesor reportar una incidencia individual con alumnos del grupo', function () {
    Filament::setCurrentPanel(Filament::getPanel('profesor'));

    $docente = tutoriaProfesor();
    $tutor = tutoriaProfesor();
    $grupo = tutoriaGrupo($tutor);
    $alumno = tutoriaAlumno($grupo);
    tutoriaCargaAcademica($docente, $grupo);

    $this->actingAs($docente->user);

    Livewire::test(CreateTutoriaIncidencia::class)
        ->fillForm([
            'grupo_id' => $grupo->id,
            'periodo_escolar_id' => tutoriaPeriodo()->id,
            'tipo' => 'individual',
            'alumnos' => [$alumno->id],
            'descripcion' => 'El alumno interrumpió la clase.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $incidencia = TutoriaIncidencia::where('registrado_por_id', $docente->id)->first();

    expect($incidencia)->not->toBeNull()
        ->and($incidencia->tutor_id)->toBe($tutor->id)
        ->and($incidencia->tipo)->toBe('individual')
        ->and($incidencia->alumnos()->pluck('alumnos.id')->all())->toBe([$alumno->id]);
});
