<?php

namespace App\Filament\Admin\Resources\TutoriaTutors;

use App\Filament\Admin\Resources\TutoriaTutors\Pages\CreateTutoriaTutor;
use App\Filament\Admin\Resources\TutoriaTutors\Pages\EditTutoriaTutor;
use App\Filament\Admin\Resources\TutoriaTutors\Pages\ListTutoriaTutors;
use App\Filament\Admin\Resources\TutoriaTutors\Schemas\TutoriaTutorForm;
use App\Filament\Admin\Resources\TutoriaTutors\Tables\TutoriaTutorsTable;
use App\Models\TutoriaTutor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TutoriaTutorResource extends Resource
{
    protected static ?string $model = TutoriaTutor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'Tutorías';

    protected static ?string $modelLabel = 'Tutor Académico';

    protected static ?string $pluralModelLabel = 'Tutores Académicos';

    public static function form(Schema $schema): Schema
    {
        return TutoriaTutorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TutoriaTutorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTutoriaTutors::route('/'),
            'create' => CreateTutoriaTutor::route('/create'),
            'edit' => EditTutoriaTutor::route('/{record}/edit'),
        ];
    }
}
