<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Asistencia;
use Filament\Widgets\ChartWidget;

class AsistenciasHoyChart extends ChartWidget
{
    protected ?string $heading = 'Asistencias del día';

    protected function getData(): array
    {
        $hoy = today();
        $presentes = Asistencia::whereDate('fecha', $hoy)->where('estado', 'Presente')->count();
        $ausentes = Asistencia::whereDate('fecha', $hoy)->where('estado', 'Ausente')->count();
        $justificados = Asistencia::whereDate('fecha', $hoy)->where('estado', 'Justificado')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Asistencias',
                    'data' => [$presentes, $ausentes, $justificados],
                    'backgroundColor' => ['#22c55e', '#ef4444', '#f59e0b'],
                ],
            ],
            'labels' => ['Presentes', 'Ausentes', 'Justificados'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
