<?php

namespace App\Filament\Admin\Pages;

use App\Models\PeriodoEscolar;
use App\Models\Profesor;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use UnitEnum;

class EstrategiasPorDocente extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $title = 'Estrategias por Docente';

    protected static ?string $navigationLabel = 'Por Docente';

    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.admin.pages.estrategias-por-docente';

    public ?int $periodo_id = null;

    protected ?string $pollingInterval = '5s';

    public function mount(): void
    {
        $this->periodo_id = PeriodoEscolar::where('activo', true)->first()?->id;
    }

    public function getPeriodos(): Collection
    {
        return PeriodoEscolar::orderBy('clave', 'desc')->get();
    }

    public function getDocentes(): Collection
    {
        $query = Profesor::with(['user', 'estrategiasDidacticas' => function ($q) {
            if ($this->periodo_id) {
                $q->where('periodo_escolar_id', $this->periodo_id);
            }
            $q->with('materia');
        }])
            ->whereHas('estrategiasDidacticas', function ($q) {
                if ($this->periodo_id) {
                    $q->where('periodo_escolar_id', $this->periodo_id);
                }
            })
            ->orderBy('numero_empleado');

        return $query->get()->map(function (Profesor $profesor) {
            $estrategias = $profesor->estrategiasDidacticas;

            $materias = $estrategias->groupBy(fn ($e) => $e->materia->nombre)
                ->sortKeys()
                ->map(fn (Collection $items, string $materiaNombre) => [
                    'nombre' => $materiaNombre,
                    'items' => $items,
                ])
                ->values();

            return [
                'id' => $profesor->id,
                'numero_empleado' => $profesor->numero_empleado,
                'nombre' => $profesor->user->name,
                'email' => $profesor->user->email,
                'total_estrategias' => $estrategias->count(),
                'materias' => $materias,
            ];
        });
    }

    public function getTitle(): string
    {
        $periodo = $this->periodo_id ? PeriodoEscolar::find($this->periodo_id) : null;

        if ($periodo) {
            return "Estrategias por Docente - {$periodo->clave}";
        }

        return 'Estrategias por Docente';
    }

    public function getHeading(): string
    {
        return $this->getTitle();
    }
}
