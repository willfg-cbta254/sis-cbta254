<?php

namespace App\Filament\Admin\Resources\WelcomeContents;

use App\Filament\Admin\Resources\WelcomeContents\Pages\CreateWelcomeContent;
use App\Filament\Admin\Resources\WelcomeContents\Pages\EditWelcomeContent;
use App\Filament\Admin\Resources\WelcomeContents\Pages\ListWelcomeContents;
use App\Filament\Admin\Resources\WelcomeContents\Schemas\WelcomeContentForm;
use App\Filament\Admin\Resources\WelcomeContents\Tables\WelcomeContentsTable;
use App\Models\WelcomeContent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WelcomeContentResource extends Resource
{
    protected static ?string $model = WelcomeContent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static string|UnitEnum|null $navigationGroup = 'Pagina';

    protected static ?string $modelLabel = 'Página Principal';

    protected static ?string $pluralModelLabel = 'Página Principal';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return WelcomeContentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WelcomeContentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWelcomeContents::route('/'),
            'create' => CreateWelcomeContent::route('/create'),
            'edit' => EditWelcomeContent::route('/{record}/edit'),
        ];
    }
}
