<?php

namespace App\Filament\Admin\Resources\SolicitudPermisos\Tables;

use App\Models\SolicitudPermiso;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SolicitudPermisosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with([
                'profesor' => fn ($profesorQuery) => $profesorQuery
                    ->with('user')
                    ->withSum('solicitudesPermiso as dias_permiso_total', 'dias_solicitados')
                    ->withSum(['solicitudesPermiso as dias_permiso_aprobados' => fn (Builder $aprobadasQuery) => $aprobadasQuery->where('estado', 'aprobada')], 'dias_solicitados'),
            ]))
            ->columns([
                TextColumn::make('profesor.user.name')
                    ->label('Docente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('profesor.numero_empleado')
                    ->label('No. empleado')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('dias_solicitados')
                    ->label('Días solicitados')
                    ->suffix(' días')
                    ->badge()
                    ->sortable(),
                TextColumn::make('profesor.dias_permiso_total')
                    ->label('Total del docente')
                    ->suffix(' días')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('profesor.dias_permiso_aprobados')
                    ->label('Aprobados del docente')
                    ->suffix(' días')
                    ->badge()
                    ->color('success'),
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
                    ->label('Respuesta')
                    ->wrap()
                    ->limit(60)
                    ->placeholder('Sin respuesta')
                    ->tooltip(fn (SolicitudPermiso $record): ?string => $record->respuesta),
                TextColumn::make('respondidoPor.name')
                    ->label('Respondió')
                    ->placeholder('—'),
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
                SelectFilter::make('profesor_id')
                    ->label('Docente')
                    ->relationship('profesor', 'numero_empleado')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (SolicitudPermiso $record): bool => $record->estado === 'pendiente')
                    ->schema([
                        Textarea::make('respuesta')
                            ->label('Observaciones para el docente (opcional)')
                            ->maxLength(65535),
                    ])
                    ->action(function (SolicitudPermiso $record, array $data): void {
                        $record->update([
                            'estado' => 'aprobada',
                            'respuesta' => $data['respuesta'] ?? null,
                            'respondido_por_id' => auth()->id(),
                            'respondido_en' => now(),
                        ]);

                        Notification::make()
                            ->title('Solicitud aprobada')
                            ->success()
                            ->send();
                    }),
                Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (SolicitudPermiso $record): bool => $record->estado === 'pendiente')
                    ->schema([
                        Textarea::make('respuesta')
                            ->label('Motivo del rechazo')
                            ->required()
                            ->maxLength(65535),
                    ])
                    ->action(function (SolicitudPermiso $record, array $data): void {
                        $record->update([
                            'estado' => 'rechazada',
                            'respuesta' => $data['respuesta'] ?? null,
                            'respondido_por_id' => auth()->id(),
                            'respondido_en' => now(),
                        ]);

                        Notification::make()
                            ->title('Solicitud rechazada')
                            ->danger()
                            ->send();
                    }),
            ]);
    }
}
