<?php

namespace App\Filament\Profesor\Pages;

use App\Models\Horario;
use BackedEnum;
use Filament\Pages\Page;

class MiHorario extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $title = 'Mi Horario';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.mi-horario-profesor';

    protected ?string $pollingInterval = '5s';

    public function getViewData(): array
    {
        $profesorId = auth()->user()?->profesor?->id;

        if (! $profesorId) {
            return ['horarioPorDia' => collect(), 'dias' => []];
        }

        $horarios = Horario::whereHas('cargaAcademica', fn ($q) => $q->where('profesor_id', $profesorId))
            ->with(['cargaAcademica.materia', 'cargaAcademica.grupo', 'cargaAcademica.periodoEscolar'])
            ->orderBy('hora_inicio')
            ->get();

        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        $horarioPorDia = $horarios->groupBy('dia');

        return compact('horarioPorDia', 'dias');
    }
}
