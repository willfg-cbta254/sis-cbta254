<?php

namespace App\Filament\Alumno\Pages;

use App\Models\CargaAcademica;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class MisMaterias extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $title = 'Mis Materias';

    protected string $view = 'filament.pages.mis-materias';

    public function table(Table $table): Table
    {
        $alumno = auth()->user()?->alumno;

        return $table
            ->query(
                CargaAcademica::whereHas('grupo.inscripciones', fn ($q) => $q->where('alumno_id', $alumno?->id)->where('status', 'Inscrito'))
                    ->whereHas('periodoEscolar', fn ($q) => $q->where('activo', true))
                    ->with(['materia', 'profesor.user'])
            )
            ->columns([
                TextColumn::make('profesor.user.name')
                    ->label('Profesor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('materia.nombre')
                    ->label('Materia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('materia.horas_semana')
                    ->label('Horas')
                    ->sortable(),

            ])
            ->defaultSort('materia.nombre');
    }
}
