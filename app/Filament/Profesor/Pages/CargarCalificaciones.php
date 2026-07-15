<?php

namespace App\Filament\Profesor\Pages;

use App\Models\Alumno;
use App\Models\Calificacion;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class CargarCalificaciones extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $title = 'Cargar Calificaciones';

    protected static ?string $navigationLabel = 'Cargar Calificaciones';

    protected static ?string $slug = 'cargar-calificaciones';

    protected string $view = 'filament.pages.cargar-calificaciones';

    public ?int $grupo_id = null;

    public ?int $materia_id = null;

    public array $calificaciones = [];

    public function mount(): void
    {
        $this->grupo_id = request()->query('grupo_id');
        $this->materia_id = request()->query('materia_id');

        if ($this->grupo_id) {
            $this->loadAlumnos();
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('grupo_id')
                    ->label('Grupo')
                    ->options(fn () => Grupo::whereHas('cargaAcademica', fn (Builder $q) => $q->where('profesor_id', auth()->user()?->profesor?->id))->pluck('nombre', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn () => $this->loadAlumnos())
                    ->required(),
                Select::make('materia_id')
                    ->label('Materia')
                    ->options(fn () => Materia::whereHas('cargaAcademica', fn (Builder $q) => $q
                        ->where('profesor_id', auth()->user()?->profesor?->id)
                        ->when($this->grupo_id, fn (Builder $q, $id) => $q->where('grupo_id', $id))
                    )->pluck('nombre', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn () => $this->loadAlumnos())
                    ->required(),
            ])
            ->model(Calificacion::class)
            ->statePath('');
    }

    public function loadAlumnos(): void
    {
        $this->calificaciones = [];

        if (blank($this->grupo_id) || blank($this->materia_id)) {
            return;
        }

        $profesorId = auth()->user()?->profesor?->id;
        $periodo = PeriodoEscolar::where('activo', true)->first();

        $alumnos = Alumno::whereHas('inscripciones', fn (Builder $q) => $q->where('grupo_id', $this->grupo_id))
            ->with('user')
            ->get();

        foreach ($alumnos as $alumno) {
            $existente = Calificacion::where('alumno_id', $alumno->id)
                ->where('materia_id', $this->materia_id)
                ->where('profesor_id', $profesorId)
                ->where('grupo_id', $this->grupo_id)
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

    public function updatedMateriaId(): void
    {
        $this->loadAlumnos();
    }

    public function save(): void
    {
        $this->validate();

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
                    'materia_id' => $this->materia_id,
                    'profesor_id' => $profesorId,
                    'grupo_id' => $this->grupo_id,
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
}
