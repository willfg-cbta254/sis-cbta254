<?php

namespace App\Filament\Alumno\Pages;

use App\Models\Asistencia;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MisAsistencias extends Page implements HasTable
{
    use InteractsWithTable;

    public static function can(): bool
    {
        return auth()->user()?->can('ver asistencias') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $title = 'Mis Asistencias';

    protected string $view = 'filament.pages.mis-asistencias';

    public function table(Table $table): Table
    {
        return $table
            ->query(Asistencia::where('alumno_id', auth()->user()?->alumno?->id))
            ->columns([
                TextColumn::make('materia.nombre'),
                TextColumn::make('profesor.user.name')->label('Profesor'),
                TextColumn::make('fecha')->date()->sortable(),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Presente' => 'success',
                        'Ausente' => 'danger',
                        'Justificado' => 'warning',
                    }),
                TextColumn::make('observaciones')->limit(50),
            ])
            ->filters([
                SelectFilter::make('materia_id')->relationship('materia', 'nombre'),
                SelectFilter::make('estado')
                    ->options(['Presente' => 'Presente', 'Ausente' => 'Ausente', 'Justificado' => 'Justificado']),
            ]);
    }
}
