<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CargaAcademicaResource\Pages;
use App\Models\CargaAcademica;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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

    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Docente y materia')
                ->description('Asigna una materia a un profesor. Evita duplicar la misma materia en el mismo grupo y periodo.')
                ->icon(Heroicon::OutlinedUserGroup)
                ->columns(2)
                ->schema([
                    Select::make('profesor_id')
                        ->label('Profesor')
                        ->relationship('profesor', 'id')
                        ->getOptionLabelFromRecordUsing(fn ($record): string => "{$record->numero_empleado} — {$record->user->name}")
                        ->searchable(['numero_empleado', 'user.name'])
                        ->preload()
                        ->native(false)
                        ->required()
                        ->prefixIcon(Heroicon::OutlinedUser)
                        ->placeholder('Selecciona docente'),
                    Select::make('materia_id')
                        ->label('Materia')
                        ->relationship('materia', 'nombre')
                        ->getOptionLabelFromRecordUsing(fn ($record): string => ($record->semestre_id?->value ? $record->semestre_id->value.' · ' : '').$record->nombre.' — '.$record->clave)
                        ->searchable()
                        ->preload()
                        ->optionsLimit(100)
                        ->native(false)
                        ->required()
                        ->prefixIcon(Heroicon::OutlinedBookOpen)
                        ->placeholder('Ej. Tercero — Matemáticas III')
                        ->helperText('Validado: no se repite materia en mismo grupo/periodo')
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
                ]),

            Grid::make(2)->schema([
                Section::make('Grupo asignado')
                    ->description('Curso donde impartirá la materia')
                    ->icon(Heroicon::OutlinedUsers)
                    ->schema([
                        Select::make('grupo_id')
                            ->label('Grupo')
                            ->relationship('grupo', 'nombre')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->nombre.' — '.$record->semestre_id->value.' · '.$record->carrera->nombre)
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required()
                            ->prefixIcon(Heroicon::OutlinedUsers)
                            ->placeholder('Ej. 5A OFI'),
                    ]),
                Section::make('Periodo escolar')
                    ->description('Ciclo donde aplica la carga')
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->schema([
                        Select::make('periodo_escolar_id')
                            ->label('Periodo')
                            ->relationship('periodoEscolar', 'clave')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required()
                            ->prefixIcon(Heroicon::OutlinedCalendar)
                            ->default(fn (): ?int => PeriodoEscolar::where('activo', true)->first()?->id)
                            ->helperText(fn (): ?string => PeriodoEscolar::where('activo', true)->first()?->clave ? 'Activo: '.PeriodoEscolar::where('activo', true)->first()->clave : null),
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('profesor.user.name')
                    ->label('Profesor')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->icon(Heroicon::OutlinedUser)
                    ->description(fn (CargaAcademica $record): string => $record->profesor->numero_empleado ?? '')
                    ->wrap(),
                TextColumn::make('materia.nombre')
                    ->label('Materia')
                    ->badge()
                    ->color('info')
                    ->icon(Heroicon::OutlinedBookOpen)
                    ->searchable()
                    ->sortable()
                    ->description(fn (CargaAcademica $record): ?string => $record->materia->clave ?? null),
                TextColumn::make('materia.semestre_id')
                    ->label('Sem.')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state): string => $state?->value ?? '—')
                    ->toggleable(),
                TextColumn::make('grupo.nombre')
                    ->label('Grupo')
                    ->badge()
                    ->color('primary')
                    ->icon(Heroicon::OutlinedUsers)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('periodoEscolar.clave')
                    ->label('Periodo')
                    ->badge()
                    ->color(fn ($record): string => $record->periodoEscolar?->activo ? 'success' : 'gray')
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Asignado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable()
                    ->icon(Heroicon::OutlinedClock),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50])
            ->emptyStateHeading('Sin cargas académicas')
            ->emptyStateDescription('Asigna materias a docentes por grupo y periodo.')
            ->emptyStateIcon(Heroicon::OutlinedAcademicCap)
            ->searchPlaceholder('Buscar profesor, materia o grupo…')
            ->filters([
                SelectFilter::make('periodo_escolar_id')
                    ->label('Periodo')
                    ->relationship('periodoEscolar', 'clave')
                    ->multiple()
                    ->preload()
                    ->indicator('Periodo'),
                SelectFilter::make('profesor_id')
                    ->label('Profesor')
                    ->relationship('profesor', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record): string => "{$record->numero_empleado} — {$record->user->name}")
                    ->searchable()
                    ->preload()
                    ->multiple(),
                SelectFilter::make('grupo_id')
                    ->label('Grupo')
                    ->relationship('grupo', 'nombre')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('materia_id')
                    ->label('Materia')
                    ->relationship('materia', 'nombre')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ])
            ->filtersTriggerAction(fn ($action) => $action->button()->label('Filtros'))
            ->actions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Editar'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
