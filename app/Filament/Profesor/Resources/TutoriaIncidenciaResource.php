<?php

namespace App\Filament\Profesor\Resources;

use App\Filament\Profesor\Resources\TutoriaIncidenciaResource\Pages;
use App\Models\Alumno;
use App\Models\Grupo;
use App\Models\PeriodoEscolar;
use App\Models\TutoriaIncidencia;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class TutoriaIncidenciaResource extends Resource
{
    protected static ?string $model = TutoriaIncidencia::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string|UnitEnum|null $navigationGroup = 'Tutorías';

    protected static ?string $modelLabel = 'Incidencia';

    protected static ?string $pluralModelLabel = 'Mis Incidencias';

    protected static ?string $navigationLabel = 'Incidencias';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('registrado_por_id', auth()->user()?->profesor?->id);
    }

    /**
     * @return array<int, string>
     */
    public static function grupoOptions(): array
    {
        $profesorId = auth()->user()?->profesor?->id;

        return Grupo::query()
            ->whereNotNull('tutor_id')
            ->whereHas('cargaAcademica', fn (Builder $query) => $query->where('profesor_id', $profesorId))
            ->with('tutor.user')
            ->get()
            ->mapWithKeys(fn (Grupo $grupo): array => [
                $grupo->id => $grupo->nombre.' - Tutor: '.($grupo->tutor?->user?->name ?? 'Sin asignar'),
            ])
            ->all();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('grupo_id')
                ->label('Grupo')
                ->options(fn (): array => static::grupoOptions())
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function ($state, callable $set): void {
                    $set('tutor_id', $state ? Grupo::find($state)?->tutor_id : null);
                })
                ->required(),
            Hidden::make('tutor_id'),
            Select::make('periodo_escolar_id')
                ->label('Periodo Escolar')
                ->relationship('periodoEscolar', 'clave')
                ->default(fn () => PeriodoEscolar::where('activo', true)->first()?->id)
                ->searchable()
                ->preload()
                ->required(),
            ToggleButtons::make('tipo')
                ->label('Tipo de incidencia')
                ->options([
                    'grupal' => 'Grupal',
                    'individual' => 'Individual',
                ])
                ->colors([
                    'grupal' => 'info',
                    'individual' => 'warning',
                ])
                ->icons([
                    'grupal' => 'heroicon-o-user-group',
                    'individual' => 'heroicon-o-user',
                ])
                ->inline()
                ->default('grupal')
                ->live()
                ->required(),
            Select::make('alumnos')
                ->label('Alumnos involucrados')
                ->multiple()
                ->options(function ($get): array {
                    if (blank($get('grupo_id'))) {
                        return [];
                    }

                    return Alumno::query()
                        ->whereHas('inscripciones', fn (Builder $query) => $query->where('grupo_id', $get('grupo_id')))
                        ->with('user')
                        ->get()
                        ->mapWithKeys(fn (Alumno $alumno): array => [
                            $alumno->id => $alumno->matricula.' - '.$alumno->user?->name,
                        ])
                        ->all();
                })
                ->searchable()
                ->preload()
                ->visible(fn ($get): bool => $get('tipo') === 'individual')
                ->required(fn ($get): bool => $get('tipo') === 'individual'),
            Textarea::make('descripcion')
                ->label('Descripción de la incidencia')
                ->required()
                ->rows(4)
                ->columnSpanFull(),
        ]);
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
                TextColumn::make('tutor.user.name')
                    ->label('Tutor que recibe')
                    ->searchable(),
                TextColumn::make('tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'grupal' => 'info',
                        'individual' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('periodoEscolar.clave')
                    ->label('Periodo')
                    ->badge(),
                TextColumn::make('descripcion')
                    ->wrap()
                    ->limit(60),
                TextColumn::make('alumnos_count')
                    ->counts('alumnos')
                    ->label('Alumnos')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Enviada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('grupo_id')
                    ->label('Grupo')
                    ->options(fn (): array => static::grupoOptions()),
                SelectFilter::make('tipo')
                    ->options([
                        'grupal' => 'Grupal',
                        'individual' => 'Individual',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTutoriaIncidencias::route('/'),
            'create' => Pages\CreateTutoriaIncidencia::route('/create'),
            'edit' => Pages\EditTutoriaIncidencia::route('/{record}/edit'),
        ];
    }
}
