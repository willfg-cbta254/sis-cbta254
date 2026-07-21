<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\Semestre;
use App\Models\Alumno;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AlumnosPorSemestreChart extends ChartWidget
{
    protected ?string $pollingInterval = '5s';

    protected ?string $heading = 'Alumnos por semestre';

    protected function getData(): array
    {
        $data = Alumno::select('semestre_actual_id', DB::raw('count(*) as total'))
            ->groupBy('semestre_actual_id')
            ->get();

        $labels = collect(Semestre::cases())->map(fn ($s) => $s->name)->toArray();
        $values = [];

        foreach (Semestre::cases() as $semestre) {
            $row = $data->firstWhere('semestre_actual_id', $semestre->value);
            $values[] = $row?->total ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Alumnos',
                    'data' => $values,
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
