<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Alumno;
use App\Models\Asistencia;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Profesor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $periodoActivo = PeriodoEscolar::where('activo', true)->first();

        return [
            Stat::make('Total Alumnos', Alumno::count())
                ->description('Inscritos en el sistema')
                ->icon('heroicon-o-users')
                ->color('info'),
            Stat::make('Profesores', Profesor::count())
                ->icon('heroicon-o-academic-cap')
                ->color('success'),
            Stat::make('Materias', Materia::count())
                ->description('En el plan de estudios')
                ->icon('heroicon-o-book-open')
                ->color('warning'),
            Stat::make('Grupos Activos', Grupo::where('activo', true)->count())
                ->icon('heroicon-o-rectangle-group')
                ->color('gray'),
            Stat::make('Asistencias Hoy', Asistencia::whereDate('fecha', today())->count())
                ->description(Asistencia::whereDate('fecha', today())->where('estado', 'Presente')->count().' presentes')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Periodo Activo', $periodoActivo?->clave ?? 'Ninguno')
                ->description($periodoActivo
                    ? "{$periodoActivo->fecha_inicio->format('d/m/Y')} - {$periodoActivo->fecha_fin->format('d/m/Y')}"
                    : 'Sin periodo activo')
                ->icon('heroicon-o-calendar')
                ->color('danger'),
        ];
    }
}
