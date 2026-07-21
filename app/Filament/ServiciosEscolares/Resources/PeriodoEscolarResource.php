<?php

namespace App\Filament\ServiciosEscolares\Resources;

use App\Filament\ServiciosEscolares\Resources\PeriodoEscolarResource\Pages;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use UnitEnum;

class PeriodoEscolarResource extends Resource
{
    protected static ?string $model = PeriodoEscolar::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static string|UnitEnum|null $navigationGroup = 'Catálogos';

    protected static ?string $modelLabel = 'Periodo Escolar';

    protected static ?string $pluralModelLabel = 'Periodos Escolares';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('clave')->required()->unique(ignoreRecord: true)->maxLength(9),
            DatePicker::make('fecha_inicio')->required(),
            DatePicker::make('fecha_fin')->required(),
            Toggle::make('activo')
                ->label('Periodo activo')
                ->default(fn () => ! PeriodoEscolar::where('activo', true)->exists()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('clave')->searchable()->sortable(),
                TextColumn::make('fecha_inicio')->date()->sortable(),
                TextColumn::make('fecha_fin')->date()->sortable(),
                IconColumn::make('activo')->boolean()->sortable(),
                TextColumn::make('created_at')->dateTime()->toggleable()->sortable(),
            ])
            ->filters([
                Filter::make('activo')->toggle()->query(fn ($q) => $q->where('activo', true)),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeriodoEscolars::route('/'),
            'create' => Pages\CreatePeriodoEscolar::route('/create'),
            'edit' => Pages\EditPeriodoEscolar::route('/{record}/edit'),
        ];
    }
}
