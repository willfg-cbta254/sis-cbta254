<?php

namespace App\Filament\Profesor\Widgets;

use App\Models\Asistencia;
use App\Models\Calificacion;
use App\Models\Profesor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProfesorStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $profesorId = auth()->user()?->profesor?->id;

        return [
            Stat::make('Mis Materias', Profesor::find($profesorId)?->cargaAcademica()->distinct('materia_id')->count() ?? 0)
                ->icon('heroicon-o-book-open')
                ->color('success'),
            Stat::make('Asistencias Hoy', Asistencia::where('profesor_id', $profesorId)
                ->whereDate('fecha', today())->count())
                ->icon('heroicon-o-check-circle')
                ->color('info'),
            Stat::make('Calificaciones Registradas', Calificacion::where('profesor_id', $profesorId)->count())
                ->icon('heroicon-o-star')
                ->color('warning'),
        ];
    }
}
