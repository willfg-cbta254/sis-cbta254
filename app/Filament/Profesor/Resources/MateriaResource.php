<?php

namespace App\Filament\Profesor\Resources;

use App\Enums\TipoMateria;
use App\Filament\Profesor\Resources\MateriaResource\Pages;
use App\Models\CargaAcademica;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class MateriaResource extends Resource
{
    protected static ?string $model = CargaAcademica::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $modelLabel = 'Materia';

    protected static ?string $pluralModelLabel = 'Mis Materias';

    public static function getEloquentQuery(): Builder
    {
        $profesorId = auth()->user()?->profesor?->id;

        return parent::getEloquentQuery()
            ->where('profesor_id', $profesorId)
            ->with(['materia', 'grupo']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('materia.clave')
                    ->label('Clave')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('materia.nombre')
                    ->label('Materia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('grupo.nombre')
                    ->label('Grupo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('materia.tipo')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (TipoMateria $state): string => $state->value),
                TextColumn::make('materia.semestre_id')
                    ->label('Semestre')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state?->value ? ucfirst($state->value) : ''),
                TextColumn::make('materia.horas_semana')
                    ->label('Horas')
                    ->sortable(),
            ])
            ->contentFooter(fn (): View => view('filament.materias-footer', [
                'totalHoras' => CargaAcademica::query()
                    ->where('profesor_id', auth()->user()?->profesor?->id)
                    ->join('materias', 'materias.id', '=', 'carga_academica.materia_id')
                    ->sum('materias.horas_semana'),
            ]))
            ->paginated(true);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterias::route('/'),
        ];
    }
}
