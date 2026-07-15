<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AsistenciaResource\Pages;
use App\Models\Asistencia;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class AsistenciaResource extends Resource
{
    protected static ?string $model = Asistencia::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-circle';

    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?string $modelLabel = 'Asistencia';

    protected static ?string $pluralModelLabel = 'Asistencias';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('alumno_id')
                ->relationship('alumno', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->matricula} - {$record->user->name}")
                ->searchable(['matricula', 'user.name'])->required()->label('Alumno'),
            Select::make('materia_id')
                ->relationship('materia', 'nombre')->searchable()->required(),
            Select::make('profesor_id')
                ->relationship('profesor', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->numero_empleado} - {$record->user->name}")
                ->searchable()->required(),
            Select::make('grupo_id')
                ->relationship('grupo', 'nombre')
                ->searchable()->preload()->required()->label('Grupo'),
            DatePicker::make('fecha')->required()->default(now()),
            Select::make('estado')
                ->options([
                    'Presente' => 'Presente',
                    'Ausente' => 'Ausente',
                    'Justificado' => 'Justificado',
                ])->required(),
            Textarea::make('observaciones')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alumno.matricula')->searchable()->sortable(),
                TextColumn::make('alumno.user.name')->searchable()->label('Alumno'),
                TextColumn::make('materia.nombre')->badge()->searchable()->sortable(),
                TextColumn::make('grupo.nombre')->badge()->sortable()->label('Grupo'),
                TextColumn::make('fecha')->date()->sortable(),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Presente' => 'success',
                        'Ausente' => 'danger',
                        'Justificado' => 'warning',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Presente' => 'heroicon-o-check-circle',
                        'Ausente' => 'heroicon-o-x-circle',
                        'Justificado' => 'heroicon-o-clock',
                    }),
            ])
            ->filters([
                SelectFilter::make('materia_id')->relationship('materia', 'nombre'),
                SelectFilter::make('grupo_id')->relationship('grupo', 'nombre')->label('Grupo'),
                SelectFilter::make('estado')
                    ->options(['Presente' => 'Presente', 'Ausente' => 'Ausente', 'Justificado' => 'Justificado']),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsistencias::route('/'),
            'create' => Pages\CreateAsistencia::route('/create'),
            'edit' => Pages\EditAsistencia::route('/{record}/edit'),
        ];
    }
}
