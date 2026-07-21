<?php

namespace App\Filament\ServiciosEscolares\Resources;

use App\Filament\ServiciosEscolares\Resources\GeneracionResource\Pages;
use App\Models\Generacion;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class GeneracionResource extends Resource
{
    protected static ?string $model = Generacion::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = 'Catálogos';

    protected static ?string $modelLabel = 'Generación';

    protected static ?string $pluralModelLabel = 'Generaciones';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('clave')->required()->unique(ignoreRecord: true)->maxLength(9),
            TextInput::make('anio_inicio')->required()->numeric()->minValue(2000)->maxValue(2100),
            TextInput::make('anio_fin')->required()->numeric()->minValue(2000)->maxValue(2100),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('clave')->searchable()->sortable(),
                TextColumn::make('anio_inicio')->sortable(),
                TextColumn::make('anio_fin')->sortable(),
                TextColumn::make('alumnos_count')->counts('alumnos')->label('Alumnos')->sortable(),
                TextColumn::make('created_at')->dateTime()->toggleable()->sortable(),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeneracions::route('/'),
            'create' => Pages\CreateGeneracion::route('/create'),
            'edit' => Pages\EditGeneracion::route('/{record}/edit'),
        ];
    }
}
