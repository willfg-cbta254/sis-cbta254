<?php

namespace App\Filament\Admin\Resources\CalificacionResource\Pages;

use App\Filament\Admin\Resources\CalificacionResource;
use App\Models\Grupo;
use App\Models\Materia;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ListCalificacions extends ListRecords
{
    protected static string $resource = CalificacionResource::class;

    public ?int $selectedGroupId = null;

    public string $selectedGroupName = '';

    public ?int $selectedMateriaId = null;

    public string $selectedMateriaName = '';

    protected function getHeaderActions(): array
    {
        if ($this->selectedGroupId) {
            return [
                CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => [
                        ...$data,
                        'grupo_id' => $this->selectedGroupId,
                        'materia_id' => $this->selectedMateriaId,
                    ]),
            ];
        }

        return [];
    }

    public function selectGroup(int $groupId): void
    {
        $this->selectedGroupId = $groupId;
        $this->selectedGroupName = Grupo::find($groupId)?->nombre ?? '';
        $this->selectedMateriaId = null;
        $this->selectedMateriaName = '';

        $this->resetTable();
    }

    public function backToGroups(): void
    {
        $this->selectedGroupId = null;
        $this->selectedGroupName = '';
        $this->selectedMateriaId = null;
        $this->selectedMateriaName = '';

        $this->resetTable();
    }

    public function selectMateria(int $materiaId): void
    {
        $this->selectedMateriaId = $materiaId;
        $this->selectedMateriaName = Materia::find($materiaId)?->nombre ?? '';

        $this->resetTable();
    }

    public function backToMaterias(): void
    {
        $this->selectedMateriaId = null;
        $this->selectedMateriaName = '';

        $this->resetTable();
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if ($this->selectedGroupId) {
            $query->where('grupo_id', $this->selectedGroupId);
        }

        if ($this->selectedMateriaId) {
            $query->where('materia_id', $this->selectedMateriaId);
        }

        return $query;
    }

    public function getTitle(): Htmlable|string
    {
        if ($this->selectedGroupId && $this->selectedMateriaId) {
            return "Calificaciones - {$this->selectedGroupName} - {$this->selectedMateriaName}";
        }

        if ($this->selectedGroupId) {
            return "Calificaciones - {$this->selectedGroupName}";
        }

        return 'Calificaciones';
    }

    public function getHeading(): string
    {
        if ($this->selectedGroupId && $this->selectedMateriaId) {
            return "Calificaciones - {$this->selectedGroupName} - {$this->selectedMateriaName}";
        }

        if ($this->selectedGroupId) {
            return "Calificaciones - {$this->selectedGroupName}";
        }

        return 'Calificaciones';
    }

    private function getGroups(): Collection
    {
        return Grupo::query()
            ->withCount('alumnos')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }

    private function getMaterias(): Collection
    {
        if (! $this->selectedGroupId) {
            return collect();
        }

        return Materia::whereHas('cargaAcademica', function (Builder $q): void {
            $q->where('grupo_id', $this->selectedGroupId);
        })
            ->orderBy('nombre')
            ->get();
    }

    public function render(): View
    {
        return view('filament.admin.pages.list-grupos', [
            'groups' => $this->getGroups(),
            'materias' => $this->getMaterias(),
            'icon' => 'heroicon-o-star',
            'emptyMessage' => 'Crea un grupo para comenzar a registrar calificaciones.',
        ])->layout($this->getLayout(), [
            'livewire' => $this,
            'maxContentWidth' => $this->getMaxContentWidth(),
            ...$this->getLayoutData(),
        ]);
    }
}
