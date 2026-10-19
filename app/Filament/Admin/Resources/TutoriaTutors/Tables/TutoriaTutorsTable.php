<?php

namespace App\Filament\Admin\Resources\TutoriaTutors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TutoriaTutorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('profesor.user.name')
                    ->label('Docente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('profesor.numero_empleado')
                    ->label('No. Empleado')
                    ->searchable(),
                TextColumn::make('profesor.grupoTutor.nombre')
                    ->label('Grupo Tutor')
                    ->default('-')
                    ->badge()
                    ->color('primary'),
                IconColumn::make('activo')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('observaciones')
                    ->limit(50),
            ])
            ->filters([
                //
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
