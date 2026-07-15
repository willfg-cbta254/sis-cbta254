<?php

namespace App\Filament\Admin\Resources\AsistenciaResource\Pages;

use App\Filament\Admin\Resources\AsistenciaResource;
use App\Models\Grupo;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class ListAsistencias extends ListRecords
{
    protected static string $resource = AsistenciaResource::class;

    public ?int $selectedGroupId = null;

    public string $selectedGroupName = '';

    public ?int $selectedMateriaId = null;

    protected function getHeaderActions(): array
    {
        if ($this->selectedGroupId) {
            return [
                CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => [
                        ...$data,
                        'grupo_id' => $this->selectedGroupId,
                    ]),
            ];
        }

        return [];
    }

    #[On('select-group')]
    public function selectGroup(int $groupId): void
    {
        $this->selectedGroupId = $groupId;
        $this->selectedGroupName = Grupo::find($groupId)?->nombre ?? '';

        $this->resetTable();
    }

    public function backToGroups(): void
    {
        $this->selectedGroupId = null;
        $this->selectedGroupName = '';

        $this->resetTable();
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if ($this->selectedGroupId) {
            $query->where('grupo_id', $this->selectedGroupId);
        }

        return $query;
    }

    public function getTitle(): Htmlable|string
    {
        if ($this->selectedGroupId) {
            return "Asistencias - {$this->selectedGroupName}";
        }

        return 'Asistencias';
    }

    public function getHeading(): string
    {
        if ($this->selectedGroupId) {
            return "Asistencias - {$this->selectedGroupName}";
        }

        return 'Asistencias';
    }

    private function getGroups(): Collection
    {
        return Grupo::query()
            ->withCount('alumnos')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }

    public function render(): View
    {
        return view('filament.admin.pages.list-grupos', [
            'groups' => $this->getGroups(),
            'materias' => collect(),
            'icon' => 'heroicon-o-check-circle',
            'emptyMessage' => 'Crea un grupo para comenzar a registrar asistencias.',
        ])->layout($this->getLayout(), [
            'livewire' => $this,
            'maxContentWidth' => $this->getMaxContentWidth(),
            ...$this->getLayoutData(),
        ]);
    }
}
