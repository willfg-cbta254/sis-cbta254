<?php

namespace App\Filament\Profesor\Resources;

use App\Filament\Profesor\Resources\TutoriaIncidenciaRecibidaResource\Pages;
use App\Models\TutoriaIncidencia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class TutoriaIncidenciaRecibidaResource extends Resource
{
    protected static ?string $model = TutoriaIncidencia::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static string|UnitEnum|null $navigationGroup = 'Tutorías';

    protected static ?string $modelLabel = 'Incidencia Recibida';

    protected static ?string $pluralModelLabel = 'Incidencias Recibidas';

    protected static ?string $navigationLabel = 'Incidencias Recibidas';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return static::esTutor();
    }

    public static function getNavigationBadge(): ?string
    {
        if (! static::esTutor()) {
            return null;
        }

        $count = static::getEloquentQuery()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function esTutor(): bool
    {
        $profesor = auth()->user()?->profesor;

        if (! $profesor) {
            return false;
        }

        return $profesor->grupoTutor()->exists()
            || $profesor->tutoriaTutor()->where('activo', true)->exists();
    }

    public static function getEloquentQuery(): Builder
    {
        $profesorId = auth()->user()?->profesor?->id;

        return parent::getEloquentQuery()
            ->where('tutor_id', $profesorId)
            ->where(fn (Builder $query) => $query
                ->whereNull('registrado_por_id')
                ->orWhere('registrado_por_id', '!=', $profesorId));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('grupo.nombre')
                    ->label('Grupo')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('registradoPor.user.name')
                    ->label('Reportada por')
                    ->description(fn (TutoriaIncidencia $record): ?string => $record->periodoEscolar?->clave)
                    ->searchable(),
                TextColumn::make('tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'grupal' => 'info',
                        'individual' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('alumnos.user.name')
                    ->label('Alumnos')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->placeholder('Grupo completo'),
                TextColumn::make('descripcion')
                    ->wrap()
                    ->limit(80),
                TextColumn::make('created_at')
                    ->label('Recibida')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('grupo_id')
                    ->label('Grupo')
                    ->relationship('grupo', 'nombre'),
                SelectFilter::make('tipo')
                    ->options([
                        'grupal' => 'Grupal',
                        'individual' => 'Individual',
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTutoriaIncidenciasRecibidas::route('/'),
        ];
    }
}
