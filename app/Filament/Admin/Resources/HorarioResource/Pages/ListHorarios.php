<?php

namespace App\Filament\Admin\Resources\HorarioResource\Pages;

use App\Filament\Admin\Resources\HorarioResource;
use App\Models\Profesor;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ListHorarios extends ListRecords
{
    protected static string $resource = HorarioResource::class;

    public ?int $selectedProfesorId = null;

    public string $selectedProfesorName = '';

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }

    public function selectProfesor(int $profesorId): void
    {
        $this->selectedProfesorId = $profesorId;
        $this->selectedProfesorName = Profesor::find($profesorId)?->user->name ?? '';

        $this->resetTable();
    }

    public function backToProfesors(): void
    {
        $this->selectedProfesorId = null;
        $this->selectedProfesorName = '';

        $this->resetTable();
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if ($this->selectedProfesorId) {
            $query->whereHas('cargaAcademica', fn (Builder $q) => $q->where('profesor_id', $this->selectedProfesorId));
        }

        return $query;
    }

    public function getTitle(): Htmlable|string
    {
        if ($this->selectedProfesorId) {
            return "Horario - {$this->selectedProfesorName}";
        }

        return 'Horarios';
    }

    public function getHeading(): string
    {
        if ($this->selectedProfesorId) {
            return "Horario - {$this->selectedProfesorName}";
        }

        return 'Horarios';
    }

    private function getProfesors(): Collection
    {
        return Profesor::whereHas('cargaAcademica.horarios')
            ->with(['user', 'cargaAcademica' => fn ($q) => $q->with('materia', 'grupo')])
            ->withCount(['cargaAcademica as materias_count' => fn ($q) => $q->select(DB::raw('COUNT(DISTINCT materia_id)'))])
            ->orderBy(DB::raw('(SELECT name FROM users WHERE users.id = profesores.user_id)'))
            ->get()
            ->map(fn (Profesor $profesor) => [
                'id' => $profesor->id,
                'name' => $profesor->user->name,
                'numero_empleado' => $profesor->numero_empleado,
                'materias_count' => $profesor->materias_count,
                'horarios_count' => $profesor->cargaAcademica->sum(fn ($ca) => $ca->horarios->count()),
                'asignaciones' => $profesor->cargaAcademica
                    ->groupBy(fn ($ca) => "{$ca->materia->nombre} - {$ca->grupo->nombre}")
                    ->keys()
                    ->values(),
            ]);
    }

    public function render(): View
    {
        return view('filament.admin.pages.list-horarios', [
            'profesors' => $this->getProfesors(),
            'icon' => 'heroicon-o-clock',
        ])->layout($this->getLayout(), [
            'livewire' => $this,
            'maxContentWidth' => $this->getMaxContentWidth(),
            ...$this->getLayoutData(),
        ]);
    }
}
