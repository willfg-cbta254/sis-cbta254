<?php

namespace App\Filament\Profesor\Resources;

use App\Filament\Profesor\Pages\CargarCalificaciones;
use App\Filament\Profesor\Resources\GrupoResource\Pages;
use App\Models\Grupo;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GrupoResource extends Resource
{
    protected static ?string $model = Grupo::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Grupo';

    protected static ?string $pluralModelLabel = 'Mis Grupos';

    public static function getEloquentQuery(): Builder
    {
        $profesorId = auth()->user()?->profesor?->id;

        return parent::getEloquentQuery()
            ->whereHas('cargaAcademica', fn (Builder $q) => $q->where('profesor_id', $profesorId));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Stack::make([
                    TextColumn::make('nombre')
                        ->size('lg')
                        ->weight('bold')
                        ->color('primary'),
                    TextColumn::make('semestre_id')
                        ->icon('heroicon-m-academic-cap')
                        ->badge()
                        ->formatStateUsing(fn ($state) => $state?->value ? ucfirst($state->value) : ''),
                    TextColumn::make('carrera.nombre')
                        ->icon('heroicon-m-presentation-chart-bar')
                        ->badge(),
                    TextColumn::make('alumnos_count')
                        ->label('Alumnos')
                        ->counts('alumnos')
                        ->icon('heroicon-m-user-group')
                        ->color('success'),
                ]),
            ])
            ->actions([
                Action::make('calificar')
                    ->label('Cargar Calificaciones')
                    ->icon('heroicon-m-star')
                    ->color('success')
                    ->url(fn (Grupo $record): string => CargarCalificaciones::getUrl(['grupo_id' => $record->id])),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrupos::route('/'),
        ];
    }
}
