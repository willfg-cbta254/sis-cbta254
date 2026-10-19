<?php

use App\Enums\Semestre;
use App\Filament\Alumno\Pages\SeleccionarAreaPropedeutica;
use App\Filament\Profesor\Pages\CargarCalificaciones;
use App\Filament\ServiciosEscolares\Resources\AreaPropedeuticaResource\Pages\CreateAreaPropedeutica;
use App\Models\Alumno;
use App\Models\AlumnoAreaPropedeutica;
use App\Models\AreaPropedeutica;
use App\Models\CargaAcademica;
use App\Models\Carrera;
use App\Models\Generacion;
use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Profesor;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('Alumno');
    Role::findOrCreate('Profesor');
});

function areaPeriodo(): PeriodoEscolar
{
    return PeriodoEscolar::firstOrCreate(['clave' => '2026-1'], [
        'fecha_inicio' => now()->startOfYear(),
        'fecha_fin' => now()->endOfYear(),
        'activo' => true,
    ]);
}

function areaCarrera(): Carrera
{
    return Carrera::firstOrCreate(['clave' => 'BAC-TEC'], ['nombre' => 'Bachillerato Tecnológico']);
}

function areaGeneracion(): Generacion
{
    return Generacion::firstOrCreate(['clave' => '2026-2029'], ['anio_inicio' => 2026, 'anio_fin' => 2029]);
}

function areaAlumno(string $semestre = 'Quinto'): Alumno
{
    $user = User::factory()->create();
    $user->assignRole('Alumno');

    return Alumno::create([
        'user_id' => $user->id,
        'matricula' => 'MAT-'.fake()->unique()->numerify('#####'),
        'generacion_id' => areaGeneracion()->id,
        'carrera_id' => areaCarrera()->id,
        'semestre_actual_id' => $semestre,
        'fecha_ingreso' => now()->toDateString(),
    ]);
}

function areaPropedeutica(int $capacidad = 30, Semestre $semestre = Semestre::Quinto): AreaPropedeutica
{
    return AreaPropedeutica::create([
        'clave' => 'AREA-'.fake()->unique()->numerify('##'),
        'nombre' => 'Área '.fake()->unique()->word(),
        'semestre' => $semestre,
        'capacidad' => $capacidad,
        'activo' => true,
    ]);
}

function areaGrupo(): Grupo
{
    return Grupo::create([
        'nombre' => '5 "A" '.fake()->unique()->numerify('##'),
        'semestre_id' => 'Quinto',
        'carrera_id' => areaCarrera()->id,
        'generacion_id' => areaGeneracion()->id,
        'turno' => 'Matutino',
        'capacidad' => 45,
        'activo' => true,
    ]);
}

function areaInscribir(Alumno $alumno, Grupo $grupo): void
{
    Inscripcion::create([
        'alumno_id' => $alumno->id,
        'grupo_id' => $grupo->id,
        'periodo_escolar_id' => areaPeriodo()->id,
        'fecha_inscripcion' => now()->toDateString(),
        'status' => 'Inscrito',
    ]);
}

it('permite a un alumno de quinto seleccionar un área propedéutica con cupo', function () {
    Filament::setCurrentPanel(Filament::getPanel('alumno'));
    areaPeriodo();

    $alumno = areaAlumno('Quinto');
    $area = areaPropedeutica(2);

    $this->actingAs($alumno->user);

    Livewire::test(SeleccionarAreaPropedeutica::class)
        ->call('seleccionarArea', $area->id)
        ->assertOk();

    expect(AlumnoAreaPropedeutica::where('alumno_id', $alumno->id)
        ->where('area_propedeutica_id', $area->id)
        ->where('periodo_escolar_id', areaPeriodo()->id)
        ->exists())->toBeTrue();
});

it('bloquea la selección cuando el área alcanzó su capacidad máxima', function () {
    Filament::setCurrentPanel(Filament::getPanel('alumno'));
    areaPeriodo();

    $area = areaPropedeutica(1);
    $otro = areaAlumno('Quinto');

    AlumnoAreaPropedeutica::create([
        'alumno_id' => $otro->id,
        'area_propedeutica_id' => $area->id,
    ]);

    $alumno = areaAlumno('Quinto');
    $this->actingAs($alumno->user);

    Livewire::test(SeleccionarAreaPropedeutica::class)
        ->call('seleccionarArea', $area->id);

    expect(AlumnoAreaPropedeutica::where('alumno_id', $alumno->id)->exists())->toBeFalse();
});

it('no permite seleccionar área a alumnos que no están en quinto semestre', function () {
    Filament::setCurrentPanel(Filament::getPanel('alumno'));
    areaPeriodo();

    $alumno = areaAlumno('Cuarto');
    $area = areaPropedeutica(2);

    $this->actingAs($alumno->user);

    Livewire::test(SeleccionarAreaPropedeutica::class)
        ->call('seleccionarArea', $area->id);

    expect(AlumnoAreaPropedeutica::where('alumno_id', $alumno->id)->exists())->toBeFalse();
});

it('no permite cambiar el área una vez seleccionada', function () {
    Filament::setCurrentPanel(Filament::getPanel('alumno'));
    areaPeriodo();

    $alumno = areaAlumno('Quinto');
    $areaOriginal = areaPropedeutica(30);
    $areaNueva = areaPropedeutica(30);

    AlumnoAreaPropedeutica::create([
        'alumno_id' => $alumno->id,
        'area_propedeutica_id' => $areaOriginal->id,
    ]);

    $this->actingAs($alumno->user);

    Livewire::test(SeleccionarAreaPropedeutica::class)
        ->call('seleccionarArea', $areaNueva->id);

    expect(AlumnoAreaPropedeutica::where('alumno_id', $alumno->id)->value('area_propedeutica_id'))
        ->toBe($areaOriginal->id);
});

it('calcula los lugares disponibles y el cupo del área', function () {
    areaPeriodo();

    $area = areaPropedeutica(2);

    expect($area->lugaresDisponibles())->toBe(2)
        ->and($area->tieneCupo())->toBeTrue();

    AlumnoAreaPropedeutica::create([
        'alumno_id' => areaAlumno('Quinto')->id,
        'area_propedeutica_id' => $area->id,
    ]);

    AlumnoAreaPropedeutica::create([
        'alumno_id' => areaAlumno('Quinto')->id,
        'area_propedeutica_id' => $area->id,
    ]);

    $area->refresh();

    expect($area->inscritosCount())->toBe(2)
        ->and($area->lugaresDisponibles())->toBe(0)
        ->and($area->tieneCupo())->toBeFalse();
});

it('solo muestra los alumnos del área al cargar calificaciones de una materia optativa', function () {
    Filament::setCurrentPanel(Filament::getPanel('profesor'));
    $periodo = areaPeriodo();

    $profesorUser = User::factory()->create();
    $profesorUser->assignRole('Profesor');
    $profesor = Profesor::create([
        'user_id' => $profesorUser->id,
        'numero_empleado' => 'EMP-'.fake()->unique()->numerify('####'),
    ]);

    $grupo = areaGrupo();
    $area = areaPropedeutica(30);

    $materiaOptativa = Materia::create([
        'nombre' => 'Física',
        'clave' => 'FIS-'.fake()->unique()->numerify('##'),
        'semestre_id' => 'Quinto',
        'area_propedeutica_id' => $area->id,
        'horas_semana' => 3,
    ]);

    CargaAcademica::create([
        'profesor_id' => $profesor->id,
        'materia_id' => $materiaOptativa->id,
        'grupo_id' => $grupo->id,
        'periodo_escolar_id' => $periodo->id,
    ]);

    $enArea = areaAlumno('Quinto');
    $fueraArea = areaAlumno('Quinto');

    areaInscribir($enArea, $grupo);
    areaInscribir($fueraArea, $grupo);

    AlumnoAreaPropedeutica::create([
        'alumno_id' => $enArea->id,
        'area_propedeutica_id' => $area->id,
    ]);

    $this->actingAs($profesorUser);

    $component = Livewire::test(CargarCalificaciones::class)
        ->set('selectedGroupId', $grupo->id)
        ->set('selectedMateriaId', $materiaOptativa->id)
        ->call('loadAlumnos');

    $calificaciones = $component->get('calificaciones');

    expect($calificaciones)->toHaveCount(1)
        ->and($calificaciones[0]['alumno_id'])->toBe($enArea->id);
});

it('muestra a todos los alumnos cuando la materia no es optativa', function () {
    Filament::setCurrentPanel(Filament::getPanel('profesor'));
    $periodo = areaPeriodo();

    $profesorUser = User::factory()->create();
    $profesorUser->assignRole('Profesor');
    $profesor = Profesor::create([
        'user_id' => $profesorUser->id,
        'numero_empleado' => 'EMP-'.fake()->unique()->numerify('####'),
    ]);

    $grupo = areaGrupo();

    $materia = Materia::create([
        'nombre' => 'Matemáticas',
        'clave' => 'MAT-'.fake()->unique()->numerify('##'),
        'semestre_id' => 'Quinto',
        'horas_semana' => 3,
    ]);

    CargaAcademica::create([
        'profesor_id' => $profesor->id,
        'materia_id' => $materia->id,
        'grupo_id' => $grupo->id,
        'periodo_escolar_id' => $periodo->id,
    ]);

    areaInscribir(areaAlumno('Quinto'), $grupo);
    areaInscribir(areaAlumno('Quinto'), $grupo);

    $this->actingAs($profesorUser);

    $component = Livewire::test(CargarCalificaciones::class)
        ->set('selectedGroupId', $grupo->id)
        ->set('selectedMateriaId', $materia->id)
        ->call('loadAlumnos');

    expect($component->get('calificaciones'))->toHaveCount(2);
});

it('almacena el semestre al crear un área propedéutica', function () {
    Filament::setCurrentPanel(Filament::getPanel('servicios-escolares'));
    Role::findOrCreate('Servicios Escolares');

    $user = User::factory()->create();
    $user->assignRole('Servicios Escolares');

    $this->actingAs($user);

    Livewire::test(CreateAreaPropedeutica::class)
        ->fillForm([
            'clave' => 'AREA-Q',
            'nombre' => 'Área Químico Biológicas',
            'semestre' => Semestre::Sexto->value,
            'capacidad' => 25,
            'activo' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $area = AreaPropedeutica::where('clave', 'AREA-Q')->first();

    expect($area)->not->toBeNull()
        ->and($area->semestre)->toBe(Semestre::Sexto);
});
