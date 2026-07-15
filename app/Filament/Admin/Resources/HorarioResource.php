<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HorarioResource\Pages;
use App\Models\CargaAcademica;
use App\Models\Horario;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class HorarioResource extends Resource
{
    protected static ?string $model = Horario::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?string $modelLabel = 'Horario';

    protected static ?string $pluralModelLabel = 'Horarios';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('carga_academica_id')
                ->relationship('cargaAcademica', 'id')
                ->getOptionLabelFromRecordUsing(fn (CargaAcademica $record) => "{$record->profesor->user->name} - {$record->materia->nombre} - {$record->grupo->nombre}")
                ->searchable(['materia.nombre', 'grupo.nombre'])
                ->preload()
                ->optionsLimit(100)
                ->required()
                ->label('Asignación Académica'),
            Select::make('dia')
                ->options([
                    'Lunes' => 'Lunes',
                    'Martes' => 'Martes',
                    'Miércoles' => 'Miércoles',
                    'Jueves' => 'Jueves',
                    'Viernes' => 'Viernes',
                ])
                ->required()
                ->label('Día'),
            TimePicker::make('hora_inicio')
                ->required()
                ->label('Hora de Inicio'),
            TimePicker::make('hora_fin')
                ->required()
                ->label('Hora de Fin')
                ->after('hora_inicio'),
            TextInput::make('aula')
                ->maxLength(50)
                ->label('Aula/Salón'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cargaAcademica.profesor.user.name')
                    ->searchable()->sortable()->label('Profesor'),
                TextColumn::make('cargaAcademica.materia.nombre')
                    ->badge()->searchable()->sortable()->label('Materia'),
                TextColumn::make('cargaAcademica.grupo.nombre')
                    ->badge()->searchable()->sortable()->label('Grupo'),
                TextColumn::make('dia')
                    ->badge()->searchable()->sortable()->label('Día'),
                TextColumn::make('hora_inicio')
                    ->time('H:i')->sortable()->label('Inicio'),
                TextColumn::make('hora_fin')
                    ->time('H:i')->sortable()->label('Fin'),
                TextColumn::make('aula')
                    ->searchable()->label('Aula'),
            ])
            ->filters([
                SelectFilter::make('dia')
                    ->options([
                        'Lunes' => 'Lunes',
                        'Martes' => 'Martes',
                        'Miércoles' => 'Miércoles',
                        'Jueves' => 'Jueves',
                        'Viernes' => 'Viernes',
                    ])
                    ->label('Día'),
                SelectFilter::make('carga_academica_id')
                    ->relationship('cargaAcademica', 'id')
                    ->getOptionLabelFromRecordUsing(fn (CargaAcademica $record) => "{$record->profesor->user->name} - {$record->materia->nombre} - {$record->grupo->nombre}")
                    ->searchable()->preload()->label('Asignación'),
            ])
            ->defaultSort('dia')
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHorarios::route('/'),
            'create' => Pages\CreateHorario::route('/create'),
            'edit' => Pages\EditHorario::route('/{record}/edit'),
        ];
    }
}
