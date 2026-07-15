<?php

namespace App\Filament\Alumno\Pages;

use App\Models\Horario;
use BackedEnum;
use Filament\Pages\Page;

class MiHorario extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $title = 'Mi Horario';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.mi-horario-alumno';

    public function getViewData(): array
    {
        $alumno = auth()->user()?->alumno;

        if (! $alumno) {
            return ['horarioPorDia' => collect(), 'dias' => []];
        }

        $grupoId = $alumno->inscripciones()
            ->whereHas('periodoEscolar', fn ($q) => $q->where('activo', true))
            ->where('status', 'Inscrito')
            ->first()?->grupo_id;

        if (! $grupoId) {
            return ['horarioPorDia' => collect(), 'dias' => []];
        }

        $horarios = Horario::whereHas('cargaAcademica', fn ($q) => $q->where('grupo_id', $grupoId))
            ->with(['cargaAcademica.materia', 'cargaAcademica.profesor.user', 'cargaAcademica.periodoEscolar'])
            ->orderBy('hora_inicio')
            ->get();

        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        $horarioPorDia = $horarios->groupBy('dia');

        return compact('horarioPorDia', 'dias');
    }
}
