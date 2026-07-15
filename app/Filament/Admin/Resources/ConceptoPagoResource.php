<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ConceptoPagoResource\Pages;
use App\Models\ConceptoPago;
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

class ConceptoPagoResource extends Resource
{
    protected static ?string $model = ConceptoPago::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string|UnitEnum|null $navigationGroup = 'Financiero';

    protected static ?string $modelLabel = 'Concepto de Pago';

    protected static ?string $pluralModelLabel = 'Conceptos de Pago';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('nombre')
                ->required()
                ->maxLength(100)
                ->label('Nombre'),
            Textarea::make('descripcion')
                ->maxLength(65535)
                ->label('Descripción')
                ->columnSpanFull(),
            TextInput::make('monto')
                ->numeric()
                ->minValue(0)
                ->step(0.01)
                ->prefix('$')
                ->label('Monto sugerido'),
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
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('monto')
                    ->numeric(2)
                    ->sortable()
                    ->prefix('$')
                    ->placeholder('Variable'),
                IconColumn::make('activo')->boolean(),
                TextColumn::make('pagos_count')
                    ->counts('pagos')
                    ->label('Pagos'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConceptoPagos::route('/'),
            'create' => Pages\CreateConceptoPago::route('/create'),
            'edit' => Pages\EditConceptoPago::route('/{record}/edit'),
        ];
    }
}
