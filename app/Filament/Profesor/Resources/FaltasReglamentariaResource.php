<?php

namespace App\Filament\Profesor\Resources;

use App\Filament\Profesor\Resources\FaltasReglamentariaResource\Pages;
use App\Models\FaltasReglamentaria;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class FaltasReglamentariaResource extends Resource
{
    protected static ?string $model = FaltasReglamentaria::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static string|UnitEnum|null $navigationGroup = 'Prefectura';

    protected static ?string $modelLabel = 'Falta Reglamentaria';

    protected static ?string $pluralModelLabel = 'Faltas Reglamentarias';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('Prefecto') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('nombre')
                ->required()
                ->maxLength(255)
                ->label('Nombre'),
            Textarea::make('descripcion')
                ->maxLength(65535)
                ->columnSpanFull()
                ->label('Descripción'),
            TextInput::make('horas_asignadas')
                ->required()
                ->numeric()
                ->minValue(0)
                ->step(0.5)
                ->suffix('horas')
                ->label('Horas asignadas'),
            Toggle::make('activo')
                ->default(true)
                ->label('Activo'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('horas_asignadas')
                    ->numeric(1)
                    ->sortable()
                    ->suffix(' h')
                    ->weight('bold')
                    ->label('Horas'),
                ToggleColumn::make('activo')
                    ->label('Activa'),
                TextColumn::make('horas_labor_social_count')
                    ->counts('horasLaborSocial')
                    ->label('Registros'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Creado'),
            ])
            ->defaultSort('nombre')
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaltasReglamentarias::route('/'),
            'create' => Pages\CreateFaltasReglamentaria::route('/create'),
            'edit' => Pages\EditFaltasReglamentaria::route('/{record}/edit'),
        ];
    }
}
