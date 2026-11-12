<?php

namespace App\Filament\Profesor\Resources\SolicitudPermisos\Tables;

use App\Models\SolicitudPermiso;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SolicitudPermisosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('dias_solicitados')
                    ->label('Días')
                    ->suffix(' días')
                    ->badge()
                    ->sortable(),
                TextColumn::make('motivo')
                    ->label('Motivo')
                    ->wrap()
                    ->limit(60)
                    ->tooltip(fn (SolicitudPermiso $record): string => $record->motivo),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendiente' => 'Pendiente',
                        'aprobada' => 'Aprobada',
                        'rechazada' => 'Rechazada',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'aprobada' => 'success',
                        'rechazada' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('respuesta')
                    ->label('Respuesta de administración')
                    ->wrap()
                    ->limit(60)
                    ->placeholder('Sin respuesta')
                    ->tooltip(fn (SolicitudPermiso $record): ?string => $record->respuesta),
                TextColumn::make('respondido_en')
                    ->label('Respondida')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Solicitada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobada' => 'Aprobada',
                        'rechazada' => 'Rechazada',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (SolicitudPermiso $record): bool => $record->estado === 'pendiente'),
                DeleteAction::make()
                    ->visible(fn (SolicitudPermiso $record): bool => $record->estado === 'pendiente'),
            ]);
    }
}
