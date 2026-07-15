<?php

namespace App\Filament\Profesor\Resources;

use App\Filament\Profesor\Resources\AsistenciaResource\Pages;
use App\Models\Alumno;
use App\Models\Asistencia;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AsistenciaResource extends Resource
{
    protected static ?string $model = Asistencia::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $modelLabel = 'Asistencia';

    protected static ?string $pluralModelLabel = 'Asistencias';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('profesor_id', auth()->user()?->profesor?->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Hidden::make('profesor_id')
                ->default(fn () => auth()->user()?->profesor?->id),
            Select::make('grupo_id')
                ->relationship(
                    'grupo',
                    'nombre',
                    modifyQueryUsing: fn (Builder $query) => $query->whereHas('cargaAcademica', fn (Builder $q) => $q->where('profesor_id', auth()->user()?->profesor?->id)),
                )
                ->searchable()->preload()->live()->required()->label('Grupo'),
            Select::make('materia_id')
                ->relationship(
                    'materia',
                    'nombre',
                    modifyQueryUsing: fn (Builder $query) => $query->whereHas('cargaAcademica', fn (Builder $q) => $q->where('profesor_id', auth()->user()?->profesor?->id)),
                )
                ->searchable()->required()->label('Materia'),
            Select::make('alumno_id')
                ->options(fn ($get) => Alumno::whereHas('inscripciones', fn (Builder $q) => $q->where('grupo_id', $get('grupo_id')))->get()->mapWithKeys(fn (Alumno $a) => [$a->id => "{$a->matricula} - {$a->user->name}"]))
                ->searchable()
                ->required()
                ->label('Alumno'),
            DatePicker::make('fecha')->required()->default(now()),
            Select::make('estado')
                ->options(['Presente' => 'Presente', 'Ausente' => 'Ausente', 'Justificado' => 'Justificado'])
                ->required(),
            Textarea::make('observaciones')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alumno.matricula')->searchable()->sortable(),
                TextColumn::make('alumno.user.name')->searchable()->label('Alumno'),
                TextColumn::make('materia.nombre')->badge()->searchable(),
                TextColumn::make('grupo.nombre')->badge()->label('Grupo'),
                TextColumn::make('fecha')->date()->sortable(),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Presente' => 'success',
                        'Ausente' => 'danger',
                        'Justificado' => 'warning',
                    }),
            ])
            ->filters([
                SelectFilter::make('grupo_id')
                    ->relationship(
                        'grupo',
                        'nombre',
                        modifyQueryUsing: fn (Builder $query) => $query->whereHas('cargaAcademica', fn (Builder $q) => $q->where('profesor_id', auth()->user()?->profesor?->id)),
                    )->label('Grupo'),
                SelectFilter::make('materia_id')
                    ->relationship(
                        'materia',
                        'nombre',
                        modifyQueryUsing: fn (Builder $query) => $query->whereHas('cargaAcademica', fn (Builder $q) => $q->where('profesor_id', auth()->user()?->profesor?->id)),
                    ),
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
