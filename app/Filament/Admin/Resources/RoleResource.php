<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RoleResource\Pages;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?string $modelLabel = 'Rol';

    protected static ?string $pluralModelLabel = 'Roles';

    public static function getPermissionGroups(): array
    {
        return [
            'Periodos Escolares' => 'periodos',
            'Generaciones' => 'generaciones',

            'Carreras' => 'carreras',
            'Materias' => 'materias',
            'Grupos' => 'grupos',
            'Usuarios' => 'usuarios',
            'Profesores' => 'profesores',
            'Alumnos' => 'alumnos',
            'Inscripciones' => 'inscripciones',
            'Asistencias' => 'asistencias',
            'Calificaciones' => 'calificaciones',
            'Blog' => 'blog',
            'Faltas Reglamentarias' => 'faltas',
            'Horas Labor Social' => 'horas labor social',
            'Conceptos de Pago' => 'conceptos_pago',
            'Pagos' => 'pagos',
            'Horarios' => 'horarios',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        $sections = [];

        foreach (static::getPermissionGroups() as $label => $entity) {
            $key = 'perm_'.Str::slug($entity, '_');

            $sections[] = Section::make($label)
                ->icon('heroicon-o-lock-closed')
                ->compact()
                ->collapsible()
                ->schema([
                    CheckboxList::make($key)
                        ->label('')
                        ->options(fn () => Permission::where('name', 'like', "% {$entity}")->pluck('name', 'id'))
                        ->columns(2)
                        ->bulkToggleable(),
                ]);
        }

        return $schema
            ->columns(2)
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label('Nombre del Rol')
                    ->columnSpanFull(),

                Section::make('Permisos')
                    ->description('Selecciona los permisos que tendrá este rol, agrupados por módulo')
                    ->icon('heroicon-o-key')
                    ->columnSpanFull()
                    ->compact()
                    ->collapsible()
                    ->schema([
                        ...$sections,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('permissions_count')->counts('permissions')->label('Permisos'),
                TextColumn::make('created_at')->dateTime()->toggleable()->sortable(),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
