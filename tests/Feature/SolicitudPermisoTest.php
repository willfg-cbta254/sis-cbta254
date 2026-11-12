<?php

use App\Filament\Admin\Resources\SolicitudPermisos\Pages\ListSolicitudPermisos as ListSolicitudesAdmin;
use App\Filament\Profesor\Resources\SolicitudPermisos\Pages\CreateSolicitudPermiso;
use App\Filament\Profesor\Resources\SolicitudPermisos\SolicitudPermisoResource as SolicitudPermisoProfesorResource;
use App\Models\Profesor;
use App\Models\SolicitudPermiso;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function solicitudProfesor(): Profesor
{
    return Profesor::create([
        'user_id' => User::factory()->create()->id,
        'numero_empleado' => 'EMP-'.fake()->unique()->numerify('####'),
    ]);
}

function solicitudPermiso(Profesor $profesor, array $attributes = []): SolicitudPermiso
{
    return SolicitudPermiso::create(array_merge([
        'profesor_id' => $profesor->id,
        'fecha_inicio' => '2026-11-10',
        'fecha_fin' => '2026-11-12',
        'motivo' => 'Trámite personal',
    ], $attributes));
}

it('calcula los días solicitados incluyendo ambas fechas', function () {
    $solicitud = solicitudPermiso(solicitudProfesor());

    expect($solicitud->fresh()->dias_solicitados)->toBe(3);
});

it('solo lista las solicitudes del profesor autenticado', function () {
    $docente = solicitudProfesor();
    $otroDocente = solicitudProfesor();

    $propia = solicitudPermiso($docente);
    solicitudPermiso($otroDocente);

    $this->actingAs($docente->user);

    expect(SolicitudPermisoProfesorResource::getEloquentQuery()->pluck('id')->all())
        ->toBe([$propia->id]);
});

it('no permite editar una solicitud que ya fue respondida', function () {
    $docente = solicitudProfesor();
    $solicitud = solicitudPermiso($docente, ['estado' => 'aprobada']);

    $this->actingAs($docente->user);

    expect(SolicitudPermisoProfesorResource::canEdit($solicitud))->toBeFalse();
});

it('permite al docente crear una solicitud de permiso', function () {
    Filament::setCurrentPanel(Filament::getPanel('profesor'));

    $docente = solicitudProfesor();

    $this->actingAs($docente->user);

    Livewire::test(CreateSolicitudPermiso::class)
        ->fillForm([
            'fecha_inicio' => '2026-11-10',
            'fecha_fin' => '2026-11-12',
            'motivo' => 'Trámite personal',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $solicitud = SolicitudPermiso::where('profesor_id', $docente->id)->first();

    expect($solicitud)->not->toBeNull()
        ->and($solicitud->dias_solicitados)->toBe(3)
        ->and($solicitud->estado)->toBe('pendiente');
});

it('permite a administración aprobar una solicitud de permiso', function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $administrador = User::factory()->create();
    $solicitud = solicitudPermiso(solicitudProfesor());

    $this->actingAs($administrador);

    Livewire::test(ListSolicitudesAdmin::class)
        ->callTableAction('aprobar', $solicitud, ['respuesta' => 'Aprobado por la dirección'])
        ->assertHasNoTableActionErrors();

    $solicitud->refresh();

    expect($solicitud->estado)->toBe('aprobada')
        ->and($solicitud->respuesta)->toBe('Aprobado por la dirección')
        ->and($solicitud->respondido_por_id)->toBe($administrador->id)
        ->and($solicitud->respondido_en)->not->toBeNull();
});

it('permite a administración rechazar una solicitud de permiso', function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $administrador = User::factory()->create();
    $solicitud = solicitudPermiso(solicitudProfesor());

    $this->actingAs($administrador);

    Livewire::test(ListSolicitudesAdmin::class)
        ->callTableAction('rechazar', $solicitud, ['respuesta' => 'No cumple con los requisitos'])
        ->assertHasNoTableActionErrors();

    $solicitud->refresh();

    expect($solicitud->estado)->toBe('rechazada')
        ->and($solicitud->respuesta)->toBe('No cumple con los requisitos')
        ->and($solicitud->respondido_por_id)->toBe($administrador->id);
});
