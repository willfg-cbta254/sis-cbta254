<?php

namespace App\Filament\Admin\Resources\TutoriaIncidencias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TutoriaIncidenciasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('grupo.nombre')
                    ->label('Grupo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tutor.user.name')
                    ->label('Tutor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'grupal' => 'info',
                        'individual' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('periodoEscolar.clave')
                    ->label('Periodo'),
                TextColumn::make('registradoPor.user.name')
                    ->label('Registrado por')
                    ->limit(30),
                TextColumn::make('descripcion')
                    ->wrap()
                    ->limit(60),
                TextColumn::make('alumnos_count')
                    ->counts('alumnos')
                    ->label('Alumnos'),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->options([
                        'grupal' => 'Grupal',
                        'individual' => 'Individual',
                    ]),
                SelectFilter::make('grupo_id')
                    ->relationship('grupo', 'nombre'),
                SelectFilter::make('tutor_id')
                    ->relationship('tutor.user', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
