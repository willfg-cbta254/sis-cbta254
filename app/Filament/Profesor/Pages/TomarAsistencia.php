<?php

namespace App\Filament\Profesor\Pages;

use App\Models\Alumno;
use App\Models\Asistencia;
use App\Models\Grupo;
use App\Models\Materia;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class TomarAsistencia extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $title = 'Tomar Asistencia';

    protected static ?string $navigationLabel = 'Tomar Asistencia';

    protected static ?string $slug = 'tomar-asistencia';

    protected string $view = 'filament.pages.tomar-asistencia';

    public ?int $grupo_id = null;

    public ?int $materia_id = null;

    public ?string $fecha = null;

    public array $asistencias = [];

    protected ?string $pollingInterval = '5s';

    public function mount(): void
    {
        $this->fecha = now()->toDateString();
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
                DatePicker::make('fecha')
                    ->label('Fecha')
                    ->required()
                    ->default(now()),
            ])
            ->model(Asistencia::class)
            ->statePath('');
    }

    public function loadAlumnos(): void
    {
        $this->asistencias = [];

        if (blank($this->grupo_id)) {
            return;
        }

        $profesorId = auth()->user()?->profesor?->id;

        $alumnos = Alumno::whereHas('inscripciones', fn (Builder $q) => $q->where('grupo_id', $this->grupo_id))
            ->with('user')
            ->get();

        foreach ($alumnos as $alumno) {
            $existente = Asistencia::where('alumno_id', $alumno->id)
                ->where('materia_id', $this->materia_id)
                ->where('profesor_id', $profesorId)
                ->where('fecha', $this->fecha)
                ->first();

            $this->asistencias[] = [
                'alumno_id' => $alumno->id,
                'matricula' => $alumno->matricula,
                'nombre' => $alumno->user->name,
                'presente' => $existente?->estado === 'Presente' || $existente === null,
                'ausente' => $existente?->estado === 'Ausente',
                'permiso' => $existente?->estado === 'Permiso',
            ];
        }
    }

    public function toggleAsistencia(int $index, string $field): void
    {
        $this->asistencias[$index][$field] = ! $this->asistencias[$index][$field];

        if ($this->asistencias[$index][$field]) {
            foreach (['presente', 'ausente', 'permiso'] as $f) {
                if ($f !== $field) {
                    $this->asistencias[$index][$f] = false;
                }
            }
        }
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

        if (blank($this->asistencias)) {
            Notification::make()
                ->warning()
                ->title('Sin alumnos')
                ->body('Selecciona un grupo con alumnos registrados.')
                ->send();

            return;
        }

        $created = 0;
        $updated = 0;

        foreach ($this->asistencias as $data) {
            $estado = match (true) {
                $data['permiso'] => 'Permiso',
                $data['ausente'] => 'Ausente',
                default => 'Presente',
            };

            $asistencia = Asistencia::updateOrCreate(
                [
                    'alumno_id' => $data['alumno_id'],
                    'materia_id' => $this->materia_id,
                    'profesor_id' => $profesorId,
                    'fecha' => $this->fecha,
                ],
                [
                    'grupo_id' => $this->grupo_id,
                    'estado' => $estado,
                ],
            );

            if ($asistencia->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        $parts = collect();
        if ($created) {
            $parts->push("{$created} registradas");
        }
        if ($updated) {
            $parts->push("{$updated} actualizadas");
        }

        Notification::make()
            ->success()
            ->title('Asistencia guardada')
            ->body($parts->implode(', ').' correctamente.')
            ->send();

        $this->loadAlumnos();
    }
}
