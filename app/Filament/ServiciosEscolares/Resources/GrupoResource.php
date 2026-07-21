<?php

namespace App\Filament\ServiciosEscolares\Resources;

use App\Enums\Semestre;
use App\Filament\ServiciosEscolares\Resources\GrupoResource\Pages;
use App\Filament\ServiciosEscolares\Resources\GrupoResource\RelationManagers\AlumnosRelationManager;
use App\Models\Grupo;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class GrupoResource extends Resource
{
    protected static ?string $model = Grupo::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-group';

    protected static string|UnitEnum|null $navigationGroup = 'Catálogos';

    protected static ?string $modelLabel = 'Grupo';

    protected static ?string $pluralModelLabel = 'Grupos';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('nombre')
                ->required()
                ->maxLength(10),
            Select::make('semestre_id')
                ->label('Semestre')
                ->options(Semestre::class)
                ->required(),
            Select::make('carrera_id')
                ->relationship('carrera', 'nombre')
                ->required(),
            Select::make('generacion_id')
                ->relationship('generacion', 'clave')
                ->required(),
            Select::make('turno')
                ->options(['Matutino' => 'Matutino', 'Vespertino' => 'Vespertino'])
                ->required(),
            TextInput::make('capacidad')
                ->required()
                ->numeric()
                ->default(40)
                ->minValue(1),
            Toggle::make('activo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('semestre_id')
                    ->label('Semestre')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state?->value ? ucfirst($state->value) : ''),
                TextColumn::make('carrera.nombre')
                    ->badge(),
                TextColumn::make('generacion.clave')
                    ->badge(),
                TextColumn::make('turno')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Matutino' ? 'warning' : 'info'),
                TextColumn::make('alumnos_count')
                    ->counts('alumnos')
                    ->label('Inscritos'),
                TextColumn::make('capacidad'),
                IconColumn::make('activo')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('semestre_id')->options(Semestre::class),
                SelectFilter::make('carrera_id')->relationship('carrera', 'nombre'),
                SelectFilter::make('generacion_id')->relationship('generacion', 'clave'),
                SelectFilter::make('turno')->options(['Matutino' => 'Matutino', 'Vespertino' => 'Vespertino']),
            ])
            ->actions([ViewAction::make(), EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [
            AlumnosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrupos::route('/'),
            'create' => Pages\CreateGrupo::route('/create'),
            'view' => Pages\ViewGrupo::route('/{record}'),
            'edit' => Pages\EditGrupo::route('/{record}/edit'),
        ];
    }
}
