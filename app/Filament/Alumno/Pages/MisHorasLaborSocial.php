<?php

namespace App\Filament\Alumno\Pages;

use App\Models\HoraLaborSocial;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MisHorasLaborSocial extends Page implements HasTable
{
    use InteractsWithTable;

    public static function can(): bool
    {
        return auth()->user()?->can('ver horas labor social') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $title = 'Mis Horas de Labor Social';

    protected string $view = 'filament.pages.mis-horas-labor-social';

    public function getViewData(): array
    {
        $alumnoId = auth()->user()?->alumno?->id;
        $totalAsignadas = (float) HoraLaborSocial::where('alumno_id', $alumnoId)->sum('horas_asignadas');
        $totalCumplidas = (float) HoraLaborSocial::where('alumno_id', $alumnoId)->sum('horas_cumplidas');

        return [
            'totalAsignadas' => $totalAsignadas,
            'totalCumplidas' => $totalCumplidas,
            'pendientes' => max(0, $totalAsignadas - $totalCumplidas),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(HoraLaborSocial::where('alumno_id', auth()->user()?->alumno?->id))
            ->columns([
                TextColumn::make('faltaReglamentaria.nombre')
                    ->label('Falta'),
                TextColumn::make('horas_asignadas')
                    ->numeric(1)
                    ->suffix(' h')
                    ->label('Asignadas'),
                TextColumn::make('horas_cumplidas')
                    ->numeric(1)
                    ->suffix(' h')
                    ->label('Cumplidas'),
                TextColumn::make('fecha_asignacion')
                    ->date()
                    ->sortable()
                    ->label('Asignación'),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'danger',
                        'en_progreso' => 'warning',
                        'completada' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendiente' => 'Pendiente',
                        'en_progreso' => 'En Progreso',
                        'completada' => 'Completada',
                        default => $state,
                    }),
                TextColumn::make('observaciones')
                    ->limit(50)
                    ->label('Observaciones'),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_progreso' => 'En Progreso',
                        'completada' => 'Completada',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
