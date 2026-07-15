<?php

namespace App\Filament\Admin\Resources\GrupoResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AlumnosRelationManager extends RelationManager
{
    protected static string $relationship = 'alumnos';

    protected static ?string $title = 'Alumnos inscritos';

    protected static ?string $modelLabel = 'Alumno';

    protected static ?string $pluralModelLabel = 'Alumnos';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('matricula')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label('Nombre')
                    ->sortable(),
                TextColumn::make('user.curp')
                    ->label('CURP')
                    ->toggleable()
                    ->copyable(),
                TextColumn::make('generacion.clave')
                    ->badge()
                    ->label('Generación'),
                TextColumn::make('pivot.status')
                    ->badge()
                    ->label('Estado')
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'baja' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([])
            ->defaultSort('matricula');
    }
}
