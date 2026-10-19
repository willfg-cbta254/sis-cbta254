<?php

namespace App\Filament\Profesor\Pages;

use App\Models\Alumno;
use App\Models\Calificacion;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CargarCalificaciones extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $title = 'Cargar Calificaciones';

    protected static ?string $navigationLabel = 'Cargar Calificaciones';

    protected static ?string $slug = 'cargar-calificaciones';

    protected string $view = 'filament.pages.cargar-calificaciones';

    public ?int $selectedGroupId = null;

    public string $selectedGroupName = '';

    public ?int $selectedMateriaId = null;

    public string $selectedMateriaName = '';

    public array $calificaciones = [];

    protected ?string $pollingInterval = '5s';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public function mount(): void
    {
        $this->selectedGroupId = request()->query('grupo_id');
        $this->selectedMateriaId = request()->query('materia_id');

        if ($this->selectedGroupId) {
            $this->selectedGroupName = Grupo::find($this->selectedGroupId)?->nombre ?? '';
        }

        if ($this->selectedMateriaId) {
            $this->selectedMateriaName = Materia::find($this->selectedMateriaId)?->nombre ?? '';
            $this->loadAlumnos();
        }
    }

    public function selectGroup(int $groupId): void
    {
        $this->selectedGroupId = $groupId;
        $this->selectedGroupName = Grupo::find($groupId)?->nombre ?? '';
        $this->selectedMateriaId = null;
        $this->selectedMateriaName = '';
        $this->calificaciones = [];
    }

    public function backToGroups(): void
    {
        $this->selectedGroupId = null;
        $this->selectedGroupName = '';
        $this->selectedMateriaId = null;
        $this->selectedMateriaName = '';
        $this->calificaciones = [];
    }

    public function selectMateria(int $materiaId): void
    {
        $this->selectedMateriaId = $materiaId;
        $this->selectedMateriaName = Materia::find($materiaId)?->nombre ?? '';
        $this->loadAlumnos();
    }

    public function backToMaterias(): void
    {
        $this->selectedMateriaId = null;
        $this->selectedMateriaName = '';
        $this->calificaciones = [];
    }

    public function loadAlumnos(): void
    {
        $this->calificaciones = [];

        if (blank($this->selectedGroupId) || blank($this->selectedMateriaId)) {
            return;
        }

        $profesorId = auth()->user()?->profesor?->id;
        $periodo = PeriodoEscolar::where('activo', true)->first();
        $materia = Materia::find($this->selectedMateriaId);

        $alumnosQuery = Alumno::whereHas('inscripciones', fn (Builder $q) => $q->where('grupo_id', $this->selectedGroupId))
            ->with('user');

        if ($materia?->esOptativa()) {
            $areaId = $materia->area_propedeutica_id;

            $alumnosQuery->whereHas('seleccionArea', fn (Builder $q) => $q->where('area_propedeutica_id', $areaId));
        }

        $alumnos = $alumnosQuery->get();

        foreach ($alumnos as $alumno) {
            $existente = Calificacion::where('alumno_id', $alumno->id)
                ->where('materia_id', $this->selectedMateriaId)
                ->where('profesor_id', $profesorId)
                ->where('grupo_id', $this->selectedGroupId)
                ->where('periodo_escolar_id', $periodo?->id)
                ->first();

            $this->calificaciones[] = [
                'alumno_id' => $alumno->id,
                'matricula' => $alumno->matricula,
                'alumno_nombre' => $alumno->user->name,
                'parcial1' => $existente?->parcial1,
                'parcial2' => $existente?->parcial2,
                'parcial3' => $existente?->parcial3,
                'promedio' => $existente?->promedio,
                'extraordinario' => $existente?->extraordinario,
                'curso_intersemestral' => $existente?->curso_intersemestral,
                'recursamiento' => $existente?->recursamiento ?? false,
            ];
        }
    }

    public function save(): void
    {
        $profesorId = auth()->user()?->profesor?->id;

        if (blank($profesorId)) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('No se encontró un perfil de profesor asociado a tu cuenta.')
                ->send();

            return;
        }

        if (blank($this->calificaciones)) {
            Notification::make()
                ->warning()
                ->title('Sin alumnos')
                ->body('Selecciona un grupo con alumnos registrados.')
                ->send();

            return;
        }

        $periodo = PeriodoEscolar::where('activo', true)->first();
        $created = 0;
        $updated = 0;

        foreach ($this->calificaciones as $data) {
            $calificacion = Calificacion::updateOrCreate(
                [
                    'alumno_id' => $data['alumno_id'],
                    'materia_id' => $this->selectedMateriaId,
                    'profesor_id' => $profesorId,
                    'grupo_id' => $this->selectedGroupId,
                    'periodo_escolar_id' => $periodo?->id,
                ],
                [
                    'parcial1' => $data['parcial1'] ?? 0,
                    'parcial2' => $data['parcial2'] ?? 0,
                    'parcial3' => $data['parcial3'] ?? 0,
                    'extraordinario' => $data['extraordinario'] ?? null,
                    'curso_intersemestral' => $data['curso_intersemestral'] ?? null,
                    'recursamiento' => ! empty($data['recursamiento']),
                ],
            );

            if ($calificacion->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        $parts = collect();
        if ($created) {
            $parts->push("{$created} creadas");
        }
        if ($updated) {
            $parts->push("{$updated} actualizadas");
        }

        Notification::make()
            ->success()
            ->title('Calificaciones guardadas')
            ->body($parts->implode(', ').' correctamente.')
            ->send();

        $this->loadAlumnos();
    }

    public function getTitle(): string
    {
        if ($this->selectedGroupId && $this->selectedMateriaId) {
            return "Cargar Calificaciones - {$this->selectedGroupName} - {$this->selectedMateriaName}";
        }

        if ($this->selectedGroupId) {
            return "Cargar Calificaciones - {$this->selectedGroupName}";
        }

        return 'Cargar Calificaciones';
    }

    public function getHeading(): string
    {
        if ($this->selectedGroupId && $this->selectedMateriaId) {
            return "Cargar Calificaciones - {$this->selectedGroupName} - {$this->selectedMateriaName}";
        }

        if ($this->selectedGroupId) {
            return "Cargar Calificaciones - {$this->selectedGroupName}";
        }

        return 'Cargar Calificaciones';
    }

    private function getGroups(): Collection
    {
        return Grupo::query()
            ->withCount('alumnos')
            ->where('activo', true)
            ->whereHas('cargaAcademica', fn (Builder $q) => $q->where('profesor_id', auth()->user()?->profesor?->id))
            ->orderBy('nombre')
            ->get();
    }

    private function getMaterias(): Collection
    {
        if (! $this->selectedGroupId) {
            return collect();
        }

        return Materia::whereHas('cargaAcademica', function (Builder $q): void {
            $q->where('profesor_id', auth()->user()?->profesor?->id)
                ->where('grupo_id', $this->selectedGroupId);
        })
            ->orderBy('nombre')
            ->get();
    }
}
