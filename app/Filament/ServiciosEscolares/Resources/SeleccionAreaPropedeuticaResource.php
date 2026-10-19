<?php

namespace App\Filament\ServiciosEscolares\Resources;

use App\Filament\ServiciosEscolares\Resources\SeleccionAreaPropedeuticaResource\Pages;
use App\Models\Alumno;
use App\Models\AlumnoAreaPropedeutica;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class SeleccionAreaPropedeuticaResource extends Resource
{
    protected static ?string $model = AlumnoAreaPropedeutica::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|UnitEnum|null $navigationGroup = 'Catálogos';

    protected static ?string $modelLabel = 'Selección de Área';

    protected static ?string $pluralModelLabel = 'Selecciones de Área';

    protected static ?string $navigationLabel = 'Selecciones de Área';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('alumno_id')
                ->label('Alumno')
                ->relationship('alumno', 'matricula')
                ->getOptionLabelFromRecordUsing(fn (Alumno $record): string => $record->user?->name ?? $record->matricula)
                ->searchable(['matricula'])
                ->preload()
                ->required()
                ->unique(ignoreRecord: true),
            Select::make('area_propedeutica_id')
                ->label('Área propedéutica')
                ->relationship('areaPropedeutica', 'nombre')
                ->searchable()
                ->preload()
                ->required(),
            Select::make('periodo_escolar_id')
                ->label('Periodo escolar')
                ->relationship('periodoEscolar', 'clave')
                ->searchable()
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alumno.user.name')->label('Alumno')->searchable()->sortable(),
                TextColumn::make('alumno.matricula')->label('Matrícula')->searchable()->toggleable(),
                TextColumn::make('areaPropedeutica.nombre')->label('Área propedéutica')->badge()->color('info')->sortable(),
                TextColumn::make('periodoEscolar.clave')->label('Periodo')->sortable(),
                TextColumn::make('updated_at')->label('Actualizado')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('area_propedeutica_id')
                    ->label('Área propedéutica')
                    ->relationship('areaPropedeutica', 'nombre'),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeleccionAreasPropedeuticas::route('/'),
            'create' => Pages\CreateSeleccionAreaPropedeutica::route('/create'),
            'edit' => Pages\EditSeleccionAreaPropedeutica::route('/{record}/edit'),
        ];
    }
}
