<?php

namespace App\Filament\Profesor\Resources\SolicitudPermisos;

use App\Filament\Profesor\Resources\SolicitudPermisos\Pages\CreateSolicitudPermiso;
use App\Filament\Profesor\Resources\SolicitudPermisos\Pages\EditSolicitudPermiso;
use App\Filament\Profesor\Resources\SolicitudPermisos\Pages\ListSolicitudPermisos;
use App\Filament\Profesor\Resources\SolicitudPermisos\Schemas\SolicitudPermisoForm;
use App\Filament\Profesor\Resources\SolicitudPermisos\Tables\SolicitudPermisosTable;
use App\Models\SolicitudPermiso;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class SolicitudPermisoResource extends Resource
{
    protected static ?string $model = SolicitudPermiso::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|UnitEnum|null $navigationGroup = 'Solicitudes';

    protected static ?string $modelLabel = 'Solicitud de permiso';

    protected static ?string $pluralModelLabel = 'Mis solicitudes de permiso';

    protected static ?string $navigationLabel = 'Solicitudes de permiso';

    protected static ?int $navigationSort = 10;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('profesor_id', auth()->user()?->profesor?->id);
    }

    public static function canEdit(Model $record): bool
    {
        return $record->estado === 'pendiente';
    }

    public static function canDelete(Model $record): bool
    {
        return $record->estado === 'pendiente';
    }

    public static function form(Schema $schema): Schema
    {
        return SolicitudPermisoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SolicitudPermisosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSolicitudPermisos::route('/'),
            'create' => CreateSolicitudPermiso::route('/create'),
            'edit' => EditSolicitudPermiso::route('/{record}/edit'),
        ];
    }
}
