<?php

namespace App\Filament\ServiciosEscolares\Resources;

use App\Filament\ServiciosEscolares\Resources\CargaAcademicaResource\Pages;
use App\Models\CargaAcademica;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use UnitEnum;

class CargaAcademicaResource extends Resource
{
    protected static ?string $model = CargaAcademica::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?string $modelLabel = 'Carga Académica';

    protected static ?string $pluralModelLabel = 'Cargas Académicas';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('profesor_id')
                ->relationship('profesor', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->numero_empleado} - {$record->user->name}")
                ->searchable(['numero_empleado', 'user.name'])->preload()->required()
                ->label('Profesor'),
            Select::make('materia_id')
                ->relationship('materia', 'nombre')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->semestre_id?->value} - {$record->nombre}")
                ->searchable()->preload()->optionsLimit(100)->required()
                ->label('Materia')
                ->rule(function (Get $get, ?CargaAcademica $record): Unique {
                    $rule = Rule::unique('carga_academica', 'materia_id')
                        ->where('grupo_id', $get('grupo_id'))
                        ->where('periodo_escolar_id', $get('periodo_escolar_id'));

                    if ($record) {
                        $rule->ignore($record->id);
                    }

                    return $rule;
                })
                ->validationMessages([
                    'unique' => 'Esta materia ya fue asignada a otro docente en el mismo grupo y periodo.',
                ]),
            Select::make('grupo_id')
                ->relationship('grupo', 'nombre')
                ->searchable()->preload()->required()
                ->label('Grupo'),
            Select::make('periodo_escolar_id')
                ->relationship('periodoEscolar', 'clave')
                ->searchable()->required()
                ->default(fn () => PeriodoEscolar::where('activo', true)->first()?->id)
                ->label('Periodo Escolar'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('profesor.user.name')
                    ->searchable()->sortable()->label('Profesor'),
                TextColumn::make('materia.nombre')
                    ->badge()->searchable()->sortable()->label('Materia'),
                TextColumn::make('grupo.nombre')
                    ->badge()->searchable()->sortable()->label('Grupo'),
                TextColumn::make('periodoEscolar.clave')
                    ->badge()->sortable()->label('Periodo'),
                TextColumn::make('created_at')
                    ->dateTime()->toggleable()->sortable()->label('Asignado'),
            ])
            ->filters([
                SelectFilter::make('periodo_escolar_id')
                    ->relationship('periodoEscolar', 'clave')->label('Periodo'),
                SelectFilter::make('profesor_id')
                    ->relationship('profesor', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->numero_empleado} - {$record->user->name}")
                    ->searchable()->preload()->label('Profesor'),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCargaAcademicas::route('/'),
            'create' => Pages\CreateCargaAcademica::route('/create'),
            'edit' => Pages\EditCargaAcademica::route('/{record}/edit'),
        ];
    }
}
