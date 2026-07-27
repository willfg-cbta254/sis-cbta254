<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Calificacion;
use App\Models\Grupo;
use App\Models\PeriodoEscolar;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CalificacionesPorMateriaChart extends ChartWidget
{
    protected ?string $pollingInterval = '10s';

    protected ?string $heading = 'Aprobados y Reprobados por Materia';

    protected function getData(): array
    {
        $periodoActivo = PeriodoEscolar::where('activo', true)->first();
        $grupoId = $this->filter;

        $query = Calificacion::query()
            ->select([
                'calificaciones.materia_id',
                'calificaciones.grupo_id',
                'materias.nombre as materia_nombre',
                'grupos.nombre as grupo_nombre',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN calificaciones.promedio >= 6 THEN 1 ELSE 0 END) as aprobados'),
                DB::raw('SUM(CASE WHEN calificaciones.promedio < 6 THEN 1 ELSE 0 END) as reprobados'),
            ])
            ->join('materias', 'calificaciones.materia_id', '=', 'materias.id')
            ->join('grupos', 'calificaciones.grupo_id', '=', 'grupos.id')
            ->groupBy('calificaciones.materia_id', 'calificaciones.grupo_id', 'materias.nombre', 'grupos.nombre')
            ->orderBy('materias.nombre')
            ->orderBy('grupos.nombre');

        if ($periodoActivo) {
            $query->where('calificaciones.periodo_escolar_id', $periodoActivo->id);
        }

        if ($grupoId) {
            $query->where('calificaciones.grupo_id', $grupoId);
        }

        $data = $query->get();

        $labels = $data->map(fn ($item) => "{$item->materia_nombre} - {$item->grupo_nombre}")->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Aprobados',
                    'data' => $data->pluck('aprobados')->toArray(),
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#16a34a',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Reprobados',
                    'data' => $data->pluck('reprobados')->toArray(),
                    'backgroundColor' => '#ef4444',
                    'borderColor' => '#dc2626',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        $periodoActivo = PeriodoEscolar::where('activo', true)->first();

        $grupos = Grupo::where('activo', true)
            ->whereIn('id', function ($query) use ($periodoActivo) {
                $query->select('grupo_id')
                    ->from('calificaciones')
                    ->when($periodoActivo, fn ($q) => $q->where('periodo_escolar_id', $periodoActivo->id));
            })
            ->pluck('nombre', 'id')
            ->toArray();

        return [null => 'Todos los grupos'] + $grupos;
    }
}
