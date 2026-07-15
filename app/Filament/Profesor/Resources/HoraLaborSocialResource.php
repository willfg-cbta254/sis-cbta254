<?php

namespace App\Filament\Profesor\Resources;

use App\Filament\Profesor\Resources\HoraLaborSocialResource\Pages;
use App\Models\FaltasReglamentaria;
use App\Models\HoraLaborSocial;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class HoraLaborSocialResource extends Resource
{
    protected static ?string $model = HoraLaborSocial::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static string|UnitEnum|null $navigationGroup = 'Prefectura';

    protected static ?string $modelLabel = 'Registro de Horas';

    protected static ?string $pluralModelLabel = 'Horas de Labor Social';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('Prefecto') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('alumno_id')
                ->relationship('alumno', 'matricula')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->matricula} - {$record->user?->name}")
                ->searchable(['matricula'])
                ->preload()
                ->required()
                ->label('Alumno'),
            Select::make('falta_reglamentaria_id')
                ->relationship('faltaReglamentaria', 'nombre', modifyQueryUsing: fn (Builder $query) => $query->where('activo', true))
                ->searchable()
                ->preload()
                ->required()
                ->label('Falta')
                ->live()
                ->afterStateUpdated(function (Set $set, $state): void {
                    if ($state) {
                        $falta = FaltasReglamentaria::find($state);
                        if ($falta) {
                            $set('horas_asignadas', $falta->horas_asignadas);
                        }
                    } else {
                        $set('horas_asignadas', null);
                    }
                }),
            TextInput::make('horas_asignadas')
                ->required()
                ->numeric()
                ->minValue(0)
                ->step(0.5)
                ->suffix('horas')
                ->label('Horas asignadas'),
            TextInput::make('horas_cumplidas')
                ->required()
                ->numeric()
                ->minValue(0)
                ->step(0.5)
                ->suffix('horas')
                ->default(0)
                ->label('Horas cumplidas'),
            DatePicker::make('fecha_asignacion')
                ->required()
                ->default(now())
                ->label('Fecha de asignación'),
            Select::make('estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'en_progreso' => 'En Progreso',
                    'completada' => 'Completada',
                ])
                ->required()
                ->default('pendiente')
                ->label('Estado'),
            Textarea::make('observaciones')
                ->maxLength(65535)
                ->columnSpanFull()
                ->label('Observaciones'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alumno.matricula')
                    ->searchable()
                    ->sortable()
                    ->label('Matrícula'),
                TextColumn::make('alumno.user.name')
                    ->searchable()
                    ->label('Alumno'),
                TextColumn::make('faltaReglamentaria.nombre')
                    ->badge()
                    ->searchable()
                    ->label('Falta'),
                TextColumn::make('horas_asignadas')
                    ->numeric(1)
                    ->suffix(' h')
                    ->sortable()
                    ->label('Asignadas'),
                TextColumn::make('horas_cumplidas')
                    ->numeric(1)
                    ->suffix(' h')
                    ->sortable()
                    ->label('Cumplidas')
                    ->color(fn ($record): string => $record->horas_cumplidas >= $record->horas_asignadas ? 'success' : 'warning'),
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
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_progreso' => 'En Progreso',
                        'completada' => 'Completada',
                    ]),
                SelectFilter::make('falta_reglamentaria_id')
                    ->relationship('faltaReglamentaria', 'nombre')
                    ->label('Falta'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHorasLaborSocial::route('/'),
            'create' => Pages\CreateHoraLaborSocial::route('/create'),
            'edit' => Pages\EditHoraLaborSocial::route('/{record}/edit'),
        ];
    }
}
