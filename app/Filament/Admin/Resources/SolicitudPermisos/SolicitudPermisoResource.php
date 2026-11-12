<?php

namespace App\Filament\Admin\Resources\SolicitudPermisos;

use App\Filament\Admin\Resources\SolicitudPermisos\Pages\ListSolicitudPermisos;
use App\Filament\Admin\Resources\SolicitudPermisos\Tables\SolicitudPermisosTable;
use App\Models\SolicitudPermiso;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use UnitEnum;

class SolicitudPermisoResource extends Resource
{
    protected static ?string $model = SolicitudPermiso::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    protected static ?string $modelLabel = 'Solicitud de permiso';

    protected static ?string $pluralModelLabel = 'Solicitudes de permiso';

    protected static ?string $navigationLabel = 'Permisos de docentes';

    protected static ?int $navigationSort = 20;

    public static function getNavigationBadge(): ?string
    {
        $pendientes = SolicitudPermiso::query()->pendiente()->count();

        return $pendientes > 0 ? (string) $pendientes : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return SolicitudPermisosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSolicitudPermisos::route('/'),
        ];
    }
}
