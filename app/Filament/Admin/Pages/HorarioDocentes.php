<?php

namespace App\Filament\Admin\Pages;

use App\Models\Horario;
use App\Models\Profesor;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class HorarioDocentes extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $title = 'Horario de Docentes';

    protected static ?string $navigationLabel = 'Horario de Docentes';

    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?int $navigationSort = 15;

    protected string $view = 'filament.admin.pages.horario-docentes';

    public ?int $profesor_id = null;

    public array $horarioPorDia = [];

    public array $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('profesor_id')
                    ->label('Seleccionar Docente')
                    ->options(
                        Profesor::whereHas('user', fn (Builder $q) => $q->where('activo', true))
                            ->with('user')
                            ->get()
                            ->mapWithKeys(fn (Profesor $p) => [$p->id => "{$p->numero_empleado} - {$p->user->name}"])
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder('Elige un docente para ver su horario')
                    ->afterStateUpdated(function ($state): void {
                        $this->profesor_id = $state;
                        $this->loadHorario();
                    })
                    ->live(),
            ]);
    }

    public function loadHorario(): void
    {
        if (! $this->profesor_id) {
            $this->horarioPorDia = [];

            return;
        }

        $horarios = Horario::whereHas('cargaAcademica', fn ($q) => $q->where('profesor_id', $this->profesor_id))
            ->with(['cargaAcademica.materia', 'cargaAcademica.grupo', 'cargaAcademica.periodoEscolar'])
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy('dia')
            ->toArray();

        $this->horarioPorDia = $horarios;
    }
}
