<?php

namespace App\Filament\Admin\Resources\TutoriaIncidencias;

use App\Filament\Admin\Resources\TutoriaIncidencias\Pages\CreateTutoriaIncidencia;
use App\Filament\Admin\Resources\TutoriaIncidencias\Pages\EditTutoriaIncidencia;
use App\Filament\Admin\Resources\TutoriaIncidencias\Pages\ListTutoriaIncidencias;
use App\Filament\Admin\Resources\TutoriaIncidencias\Schemas\TutoriaIncidenciaForm;
use App\Filament\Admin\Resources\TutoriaIncidencias\Tables\TutoriaIncidenciasTable;
use App\Models\TutoriaIncidencia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TutoriaIncidenciaResource extends Resource
{
    protected static ?string $model = TutoriaIncidencia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|UnitEnum|null $navigationGroup = 'Tutorías';

    protected static ?string $modelLabel = 'Incidencia de Tutoría';

    protected static ?string $pluralModelLabel = 'Incidencias de Tutoría';

    public static function form(Schema $schema): Schema
    {
        return TutoriaIncidenciaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TutoriaIncidenciasTable::configure($table);
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
            'index' => ListTutoriaIncidencias::route('/'),
            'create' => CreateTutoriaIncidencia::route('/create'),
            'edit' => EditTutoriaIncidencia::route('/{record}/edit'),
        ];
    }
}
