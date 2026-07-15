<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CalificacionResource\Pages;
use App\Models\Calificacion;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CalificacionResource extends Resource
{
    protected static ?string $model = Calificacion::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?string $modelLabel = 'Calificación';

    protected static ?string $pluralModelLabel = 'Calificaciones';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('alumno_id')
                ->relationship('alumno', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->matricula} - {$record->user->name}")
                ->searchable(['matricula', 'user.name'])->required()->label('Alumno')
                ->extraInputAttributes([
                    'style' => 'width: 300px; padding: 10px; font-size: 1.2rem;',
                ]),
            Select::make('materia_id')
                ->relationship('materia', 'nombre')->searchable()->required(),
            Select::make('profesor_id')
                ->relationship('profesor', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->numero_empleado} - {$record->user->name}")
                ->searchable()->required(),
            Select::make('grupo_id')
                ->relationship('grupo', 'nombre')
                ->searchable()->preload()->required()->label('Grupo'),
            Select::make('periodo_escolar_id')
                ->relationship('periodoEscolar', 'clave')->required()
                ->default(fn () => PeriodoEscolar::where('activo', true)->first()?->id),
            TextInput::make('parcial1')
                ->label('Parcial 1')->required()->numeric()->minValue(0)->maxValue(100)->step(0.5),
            TextInput::make('parcial2')
                ->label('Parcial 2')->required()->numeric()->minValue(0)->maxValue(100)->step(0.5),
            TextInput::make('parcial3')
                ->label('Parcial 3')->required()->numeric()->minValue(0)->maxValue(100)->step(0.5),
            TextInput::make('promedio')
                ->label('Promedio')->disabled()->numeric(2),
            TextInput::make('extraordinario')
                ->label('Extraordinario')->numeric()->minValue(0)->maxValue(100)->step(0.5),
            TextInput::make('curso_intersemestral')
                ->label('C. Intersemestral')->numeric()->minValue(0)->maxValue(100)->step(0.5),
            Toggle::make('recursamiento')->label('Recursamiento')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alumno.matricula')->searchable()->sortable(),
                TextColumn::make('alumno.user.name')->searchable()->label('Alumno'),
                TextColumn::make('materia.nombre')->badge()->sortable(),
                TextColumn::make('grupo.nombre')->badge()->sortable()->label('Grupo'),
                TextColumn::make('parcial1')->numeric(2)->sortable(),
                TextColumn::make('parcial2')->numeric(2)->sortable(),
                TextColumn::make('parcial3')->numeric(2)->sortable(),
                TextColumn::make('promedio')->numeric(2)->sortable()
                    ->color(fn (?float $state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 6 => 'success',
                        default => 'danger',
                    }),
                TextColumn::make('extraordinario')->numeric(2)->placeholder('-'),
                TextColumn::make('curso_intersemestral')->numeric(2)->placeholder('-'),
                BooleanColumn::make('recursamiento'),
            ])
            ->filters([
                SelectFilter::make('materia_id')->relationship('materia', 'nombre'),
                SelectFilter::make('grupo_id')->relationship('grupo', 'nombre')->label('Grupo'),
                SelectFilter::make('periodo_escolar_id')->relationship('periodoEscolar', 'clave'),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalificacions::route('/'),
            'create' => Pages\CreateCalificacion::route('/create'),
            'edit' => Pages\EditCalificacion::route('/{record}/edit'),
        ];
    }
}
