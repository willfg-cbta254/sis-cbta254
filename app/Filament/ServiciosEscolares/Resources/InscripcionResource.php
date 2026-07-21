<?php

namespace App\Filament\ServiciosEscolares\Resources;

use App\Filament\ServiciosEscolares\Resources\InscripcionResource\Pages;
use App\Models\Inscripcion;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class InscripcionResource extends Resource
{
    protected static ?string $model = Inscripcion::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?string $modelLabel = 'Inscripción';

    protected static ?string $pluralModelLabel = 'Inscripciones';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('alumno_id')
                ->relationship('alumno', 'matricula')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->matricula} - {$record->user?->name}")
                ->searchable(['matricula'])->preload()->required()->label('Alumno'),
            Select::make('grupo_id')
                ->relationship('grupo', 'nombre')
                ->searchable()->preload()->required(),
            Select::make('periodo_escolar_id')
                ->relationship('periodoEscolar', 'clave')
                ->searchable()->required()
                ->default(fn () => PeriodoEscolar::where('activo', true)->first()?->id),
            DatePicker::make('fecha_inscripcion')->required()->default(now()),
            Select::make('status')
                ->options(['Inscrito' => 'Inscrito', 'Baja' => 'Baja', 'Egresado' => 'Egresado'])
                ->required()->default('Inscrito'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alumno.matricula')->searchable()->sortable(),
                TextColumn::make('alumno.user.name')->searchable()->label('Alumno'),
                TextColumn::make('grupo.nombre')->badge()->sortable(),
                TextColumn::make('periodoEscolar.clave')->badge()->sortable(),
                TextColumn::make('fecha_inscripcion')->date()->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Inscrito' => 'success',
                        'Baja' => 'danger',
                        'Egresado' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('periodo_escolar_id')->relationship('periodoEscolar', 'clave'),
                SelectFilter::make('status')->options(['Inscrito' => 'Inscrito', 'Baja' => 'Baja', 'Egresado' => 'Egresado']),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInscripcions::route('/'),
            'create' => Pages\CreateInscripcion::route('/create'),
            'edit' => Pages\EditInscripcion::route('/{record}/edit'),
        ];
    }
}
