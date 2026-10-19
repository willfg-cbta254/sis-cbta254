<?php

namespace App\Filament\Alumno\Pages;

use App\Models\Alumno;
use App\Models\AlumnoAreaPropedeutica;
use App\Models\AreaPropedeutica;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class SeleccionarAreaPropedeutica extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $title = 'Área Propedéutica';

    protected static ?string $navigationLabel = 'Área Propedéutica';

    protected static ?string $slug = 'area-propedeutica';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.seleccionar-area-propedeutica';

    public static function shouldRegisterNavigation(): bool
    {
        return (bool) auth()->user()?->alumno?->puedeSeleccionarArea();
    }

    public function getAlumno(): ?Alumno
    {
        return auth()->user()?->alumno;
    }

    /**
     * @return Collection<int, AreaPropedeutica>
     */
    public function getAreas(): Collection
    {
        return AreaPropedeutica::query()
            ->where('activo', true)
            ->withCount('selecciones')
            ->with('materias')
            ->orderBy('nombre')
            ->get();
    }

    public function getAreaSeleccionada(): ?AreaPropedeutica
    {
        return $this->getAlumno()?->areaPropedeutica;
    }

    public function seleccionarArea(int $areaId): void
    {
        $alumno = $this->getAlumno();

        if (! $alumno) {
            Notification::make()->danger()->title('No se encontró tu perfil de alumno.')->send();

            return;
        }

        if (! $alumno->puedeSeleccionarArea()) {
            Notification::make()
                ->danger()
                ->title('No disponible')
                ->body('Solo los alumnos de quinto semestre pueden seleccionar un área propedéutica.')
                ->send();

            return;
        }

        if ($alumno->seleccionArea()->exists()) {
            Notification::make()
                ->warning()
                ->title('Ya tienes un área asignada')
                ->body('Acude a Servicios Escolares para modificarla.')
                ->send();

            return;
        }

        $area = AreaPropedeutica::where('activo', true)->find($areaId);

        if (! $area) {
            Notification::make()->danger()->title('El área seleccionada no está disponible.')->send();

            return;
        }

        if (! $area->tieneCupo()) {
            Notification::make()
                ->danger()
                ->title('Área sin cupo')
                ->body("El área {$area->nombre} ya alcanzó su capacidad máxima.")
                ->send();

            return;
        }

        AlumnoAreaPropedeutica::create([
            'alumno_id' => $alumno->id,
            'area_propedeutica_id' => $area->id,
            'periodo_escolar_id' => PeriodoEscolar::where('activo', true)->first()?->id,
        ]);

        Notification::make()
            ->success()
            ->title('Área propedéutica asignada')
            ->body("Fuiste asignado al área {$area->nombre}.")
            ->send();
    }
}
