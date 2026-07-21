<?php

namespace App\Filament\ServiciosEscolares\Resources\CargaAcademicaResource\Pages;

use App\Filament\ServiciosEscolares\Resources\CargaAcademicaResource;
use App\Models\Grupo;
use App\Models\PeriodoEscolar;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ListCargaAcademicas extends ListRecords
{
    protected static string $resource = CargaAcademicaResource::class;

    public ?int $selectedPeriodoId = null;

    public string $selectedPeriodoName = '';

    public ?int $selectedGroupId = null;

    public string $selectedGroupName = '';

    protected function getHeaderActions(): array
    {
        if ($this->selectedGroupId && $this->selectedPeriodoId) {
            return [
                CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => [
                        ...$data,
                        'grupo_id' => $this->selectedGroupId,
                        'periodo_escolar_id' => $this->selectedPeriodoId,
                    ]),
            ];
        }

        return [];
    }

    public function selectPeriodo(int $periodoId): void
    {
        $this->selectedPeriodoId = $periodoId;
        $this->selectedPeriodoName = PeriodoEscolar::find($periodoId)?->clave ?? '';
        $this->selectedGroupId = null;
        $this->selectedGroupName = '';

        $this->resetTable();
    }

    public function backToPeriodos(): void
    {
        $this->selectedPeriodoId = null;
        $this->selectedPeriodoName = '';
        $this->selectedGroupId = null;
        $this->selectedGroupName = '';

        $this->resetTable();
    }

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

        if ($this->selectedPeriodoId) {
            $query->where('periodo_escolar_id', $this->selectedPeriodoId);
        }

        return $query;
    }

    public function getTitle(): Htmlable|string
    {
        if ($this->selectedGroupId) {
            return "Carga Académica - {$this->selectedPeriodoName} - {$this->selectedGroupName}";
        }

        if ($this->selectedPeriodoId) {
            return "Carga Académica - {$this->selectedPeriodoName}";
        }

        return 'Carga Académica';
    }

    public function getHeading(): string
    {
        if ($this->selectedGroupId) {
            return "Carga Académica - {$this->selectedPeriodoName} - {$this->selectedGroupName}";
        }

        if ($this->selectedPeriodoId) {
            return "Carga Académica - {$this->selectedPeriodoName}";
        }

        return 'Carga Académica';
    }

    private function getPeriodos(): Collection
    {
        return PeriodoEscolar::query()
            ->orderBy('clave', 'desc')
            ->get();
    }

    private function getGroups(): Collection
    {
        if (! $this->selectedPeriodoId) {
            return collect();
        }

        return Grupo::whereHas('cargaAcademica', function (Builder $q): void {
            $q->where('periodo_escolar_id', $this->selectedPeriodoId);
        })
            ->withCount(['alumnos', 'cargaAcademica'])
            ->orderBy('nombre')
            ->get();
    }

    public function render(): View
    {
        return view('filament.servicios-escolares.pages.list-carga-academica', [
            'periodos' => $this->getPeriodos(),
            'groups' => $this->getGroups(),
            'icon' => 'heroicon-o-academic-cap',
            'emptyMessage' => 'No hay grupos con carga académica en este periodo.',
        ])->layout($this->getLayout(), [
            'livewire' => $this,
            'maxContentWidth' => $this->getMaxContentWidth(),
            ...$this->getLayoutData(),
        ]);
    }
}
