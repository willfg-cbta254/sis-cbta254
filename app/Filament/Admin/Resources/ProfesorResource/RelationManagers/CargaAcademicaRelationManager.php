<?php

namespace App\Filament\Admin\Resources\ProfesorResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CargaAcademicaRelationManager extends RelationManager
{
    protected static string $relationship = 'cargaAcademica';

    protected static ?string $title = 'Carga Académica';

    protected static ?string $modelLabel = 'Asignación';

    protected static ?string $pluralModelLabel = 'Asignaciones';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('materia.semestre_id')
                    ->label('Semestre')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => $state?->value ?? ''),
                TextColumn::make('materia.nombre')
                    ->label('Materia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('grupo.nombre')
                    ->label('Grupo')
                    ->badge()
                    ->sortable(),
                TextColumn::make('periodoEscolar.clave')
                    ->label('Periodo')
                    ->badge()
                    ->sortable(),
            ])
            ->defaultSort('materia.semestre_id');
    }
}
