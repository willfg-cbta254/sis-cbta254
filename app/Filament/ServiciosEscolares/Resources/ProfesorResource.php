<?php

namespace App\Filament\ServiciosEscolares\Resources;

use App\Filament\ServiciosEscolares\Resources\ProfesorResource\Pages;
use App\Models\Profesor;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use UnitEnum;

class ProfesorResource extends Resource
{
    protected static ?string $model = Profesor::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?string $modelLabel = 'Profesor';

    protected static ?string $pluralModelLabel = 'Profesores';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Datos de usuario')->schema([
                TextInput::make('user.name')->required()->maxLength(255)->label('Nombre completo'),
                TextInput::make('user.email')->required()->email()->rule(fn ($component) => Rule::unique('users', 'email')->ignore($component->getRecord()?->user?->id)),
                TextInput::make('user.password')
                    ->password()->revealable()
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->label('Contraseña'),
                TextInput::make('user.curp')->maxLength(18),
                TextInput::make('user.telefono')->tel()->maxLength(15),
                DatePicker::make('user.fecha_nacimiento'),
            ])->columns(2),
            Section::make('Datos laborales')->schema([
                TextInput::make('numero_empleado')->required()->unique(ignoreRecord: true)->maxLength(20),
                DatePicker::make('fecha_ingreso'),
                TextInput::make('especialidad')->maxLength(100),
                TextInput::make('horas_semanales')->numeric()->minValue(0)->maxValue(100)->label('Horas semanales'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount([
                'cargaAcademica as materias_count' => fn ($q) => $q->select(DB::raw('COUNT(DISTINCT materia_id)')),
            ]))
            ->columns([
                TextColumn::make('user.name')->searchable()->sortable()->label('Nombre'),
                TextColumn::make('numero_empleado')->searchable()->sortable(),
                TextColumn::make('user.email')->searchable()->copyable(),
                TextColumn::make('especialidad')->badge(),
                TextColumn::make('horas_semanales')->label('Horas')->sortable()->toggleable(),
                TextColumn::make('materias_count')->label('Materias'),
                TextColumn::make('carga_academica_count')->counts('cargaAcademica')->label('Asignaciones'),
                TextColumn::make('created_at')->dateTime()->toggleable()->sortable(),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfesors::route('/'),
            'create' => Pages\CreateProfesor::route('/create'),
            'edit' => Pages\EditProfesor::route('/{record}/edit'),
        ];
    }
}
