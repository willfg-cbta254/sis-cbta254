<?php

namespace App\Filament\Alumno\Widgets;

use App\Models\Alumno;
use App\Models\Asistencia;
use App\Models\Calificacion;
use App\Models\HoraLaborSocial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AlumnoStatsWidget extends BaseWidget
{
    protected ?string $pollingInterval = '5s';

    protected function getStats(): array
    {
        $alumnoId = auth()->user()?->alumno?->id;

        $totalAsistencias = Asistencia::where('alumno_id', $alumnoId)->count();
        $presentes = Asistencia::where('alumno_id', $alumnoId)->where('estado', 'Presente')->count();
        $porcentaje = $totalAsistencias > 0 ? round(($presentes / $totalAsistencias) * 100, 1) : 0;

        $promedio = Calificacion::where('alumno_id', $alumnoId)->whereNotNull('promedio')->avg('promedio');

        $horasPendientes = HoraLaborSocial::where('alumno_id', $alumnoId)->whereIn('estado', ['pendiente', 'en_progreso'])->sum('horas_asignadas');
        $horasCumplidas = HoraLaborSocial::where('alumno_id', $alumnoId)->sum('horas_cumplidas');

        return [
            Stat::make('Materias Cursando', Alumno::find($alumnoId)?->materias()->count() ?? 0)
                ->icon('heroicon-o-book-open')
                ->color('info'),
            Stat::make('Asistencia', "{$porcentaje}%")
                ->description("{$presentes} de {$totalAsistencias} días presentes")
                ->icon('heroicon-o-check-circle')
                ->color($porcentaje >= 80 ? 'success' : 'danger'),
            Stat::make('Promedio General', $promedio ? number_format($promedio, 1) : 'N/A')
                ->description($promedio && $promedio >= 60 ? 'Aprobatorio' : 'En riesgo')
                ->icon('heroicon-o-star')
                ->color($promedio && $promedio >= 60 ? 'success' : 'danger'),
            Stat::make('Horas Labor Social', number_format($horasCumplidas, 1).'h')
                ->description("{$horasPendientes}h pendientes")
                ->icon('heroicon-o-clock')
                ->color($horasPendientes > 0 ? 'warning' : 'success'),
        ];
    }
}
