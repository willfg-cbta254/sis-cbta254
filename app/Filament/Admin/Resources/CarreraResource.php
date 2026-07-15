<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CarreraResource\Pages;
use App\Models\Carrera;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CarreraResource extends Resource
{
    protected static ?string $model = Carrera::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|UnitEnum|null $navigationGroup = 'Catálogos';

    protected static ?string $modelLabel = 'Carrera';

    protected static ?string $pluralModelLabel = 'Carreras';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('clave')->required()->unique(ignoreRecord: true)->maxLength(20),
            TextInput::make('nombre')->required()->maxLength(120),
            Textarea::make('descripcion')->columnSpanFull(),
            Toggle::make('activo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('clave')->searchable()->sortable(),
                TextColumn::make('nombre')->searchable()->limit(50),
                IconColumn::make('activo')->boolean()->sortable(),
                TextColumn::make('alumnos_count')->counts('alumnos')->label('Alumnos'),
                TextColumn::make('created_at')->dateTime()->toggleable()->sortable(),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarreras::route('/'),
            'create' => Pages\CreateCarrera::route('/create'),
            'edit' => Pages\EditCarrera::route('/{record}/edit'),
        ];
    }
}
