<?php

namespace App\Filament\Admin\Resources\FichaResource;

use App\Filament\Admin\Resources\FichaResource\Pages\CreateFicha;
use App\Filament\Admin\Resources\FichaResource\Pages\EditFicha;
use App\Filament\Admin\Resources\FichaResource\Pages\ListFichas;
use App\Filament\Admin\Resources\FichaResource\Pages\ViewFicha;
use App\Filament\Admin\Resources\FichaResource\Schemas\FichaForm;
use App\Filament\Admin\Resources\FichaResource\Tables\FichasTable;
use App\Models\Ficha;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FichaResource extends Resource
{
    protected static ?string $model = Ficha::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?string $navigationLabel = 'Fichas de Nuevo Ingreso';

    protected static ?string $modelLabel = 'Ficha';

    protected static ?string $pluralModelLabel = 'Fichas de Nuevo Ingreso';

    public static function form(Schema $schema): Schema
    {
        return FichaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FichasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFichas::route('/'),
            'create' => CreateFicha::route('/create'),
            'view' => ViewFicha::route('/{record}'),
            'edit' => EditFicha::route('/{record}/edit'),
        ];
    }
}
