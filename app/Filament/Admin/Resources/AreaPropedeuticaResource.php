<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Semestre;
use App\Filament\Admin\Resources\AreaPropedeuticaResource\Pages;
use App\Models\AreaPropedeutica;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class AreaPropedeuticaResource extends Resource
{
    protected static ?string $model = AreaPropedeutica::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?string $modelLabel = 'Área Propedéutica';

    protected static ?string $pluralModelLabel = 'Áreas Propedéuticas';

    protected static ?string $navigationLabel = 'Áreas Propedéuticas';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('clave')->required()->unique(ignoreRecord: true)->maxLength(30),
            TextInput::make('nombre')->required()->maxLength(120),
            Select::make('semestre')
                ->label('Semestre en que se imparte')
                ->options([
                    Semestre::Quinto->value => 'Quinto semestre',
                    Semestre::Sexto->value => 'Sexto semestre',
                ])
                ->required(),
            Textarea::make('descripcion')->rows(3)->columnSpanFull(),
            TextInput::make('capacidad')
                ->label('Capacidad de alumnos')
                ->required()
                ->numeric()
                ->minValue(1)
                ->default(30),
            Toggle::make('activo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount(['selecciones', 'materias']))
            ->columns([
                TextColumn::make('clave')->searchable()->sortable(),
                TextColumn::make('nombre')->searchable()->sortable(),
                TextColumn::make('semestre')->label('Semestre')->badge()->sortable(),
                TextColumn::make('capacidad')->label('Capacidad')->sortable(),
                TextColumn::make('selecciones_count')->label('Inscritos')->badge()->sortable(),
                TextColumn::make('lugares_disponibles')
                    ->label('Disponibles')
                    ->state(fn (AreaPropedeutica $record): int => $record->lugaresDisponibles())
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger'),
                TextColumn::make('materias_count')->label('Materias')->badge()->color('gray'),
                IconColumn::make('activo')->boolean(),
            ])
            ->filters([
                TernaryFilter::make('activo')->label('Activo'),
            ])
            ->defaultSort('nombre')
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAreasPropedeuticas::route('/'),
            'create' => Pages\CreateAreaPropedeutica::route('/create'),
            'edit' => Pages\EditAreaPropedeutica::route('/{record}/edit'),
        ];
    }
}
