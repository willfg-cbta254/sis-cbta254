<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Semestre;
use App\Filament\Admin\Resources\AlumnoResource\Pages;
use App\Models\Alumno;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use UnitEnum;

class AlumnoResource extends Resource
{
    protected static ?string $model = Alumno::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?string $modelLabel = 'Alumno';

    protected static ?string $pluralModelLabel = 'Alumnos';

    protected static ?string $recordTitleAttribute = 'matricula';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Identidad del estudiante')
                ->description('Datos personales vinculados al usuario del sistema')
                ->icon(Heroicon::OutlinedUserCircle)
                ->columns(2)
                ->schema([
                    TextInput::make('user.name')
                        ->label('Nombre completo')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon(Heroicon::OutlinedUser)
                        ->placeholder('Ej. Juan Pérez Hernández'),
                    TextInput::make('user.email')
                        ->label('Correo electrónico')
                        ->required()
                        ->email()
                        ->prefixIcon(Heroicon::OutlinedEnvelope)
                        ->placeholder('alumno@cbta254.edu.mx')
                        ->rule(fn ($component) => Rule::unique('users', 'email')->ignore($component->getRecord()?->user?->id)),
                    TextInput::make('user.password')
                        ->label('Contraseña')
                        ->password()
                        ->revealable()
                        ->prefixIcon(Heroicon::OutlinedKey)
                        ->dehydrated(fn ($state): bool => filled($state))
                        ->required(fn (string $context): bool => $context === 'create')
                        ->helperText(fn (string $context): ?string => $context === 'edit' ? 'Déjalo vacío para conservar la actual' : null),
                    TextInput::make('user.curp')
                        ->label('CURP')
                        ->required()
                        ->maxLength(18)
                        ->prefixIcon(Heroicon::OutlinedIdentification)
                        ->placeholder('18 caracteres')
                        ->rule(fn ($component) => Rule::unique('users', 'curp')->ignore($component->getRecord()?->user?->id))
                        ->extraInputAttributes(['style' => 'text-transform:uppercase'])
                        ->formatStateUsing(fn ($state) => $state ? mb_strtoupper((string) $state) : $state),
                    TextInput::make('user.telefono')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(15)
                        ->prefixIcon(Heroicon::OutlinedPhone)
                        ->placeholder('741 123 4567'),
                    DatePicker::make('user.fecha_nacimiento')
                        ->label('Fecha de nacimiento')
                        ->required()
                        ->native(false)
                        ->prefixIcon(Heroicon::OutlinedCalendarDays)
                        ->displayFormat('d/m/Y'),
                    Textarea::make('user.direccion')
                        ->label('Dirección')
                        ->rows(2)
                        ->placeholder('Calle, colonia, municipio...')
                        ->columnSpanFull(),
                ]),

            Section::make('Trayectoria académica')
                ->description('Matrícula y ubicación curricular del alumno')
                ->icon(Heroicon::OutlinedAcademicCap)
                ->columns(2)
                ->schema([
                    TextInput::make('matricula')
                        ->label('Matrícula')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20)
                        ->prefixIcon(Heroicon::OutlinedHashtag)
                        ->placeholder('Ej. 2024-0015'),
                    Select::make('generacion_id')
                        ->label('Generación')
                        ->relationship('generacion', 'clave')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->required()
                        ->prefixIcon(Heroicon::OutlinedCalendar),
                    Select::make('carrera_id')
                        ->label('Carrera')
                        ->relationship('carrera', 'nombre')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->required()
                        ->prefixIcon(Heroicon::OutlinedBriefcase),
                    Select::make('semestre_actual_id')
                        ->label('Semestre')
                        ->options(Semestre::class)
                        ->native(false)
                        ->required()
                        ->prefixIcon(Heroicon::OutlinedChartBar),
                    DatePicker::make('fecha_ingreso')
                        ->label('Fecha de ingreso')
                        ->required()
                        ->native(false)
                        ->prefixIcon(Heroicon::OutlinedCalendarDays)
                        ->displayFormat('d/m/Y')
                        ->default(now()),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('matricula')
                    ->label('Matrícula')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Copiado')
                    ->weight('bold')
                    ->icon(Heroicon::OutlinedHashtag)
                    ->iconColor('gray'),
                TextColumn::make('user.name')
                    ->label('Alumno')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn (Alumno $record): ?string => $record->user?->email)
                    ->icon(Heroicon::OutlinedUser)
                    ->wrap(),
                TextColumn::make('user.curp')
                    ->label('CURP')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable()
                    ->fontFamily('mono')
                    ->searchable(),
                TextColumn::make('carrera.nombre')
                    ->label('Carrera')
                    ->badge()
                    ->color('info')
                    ->icon(Heroicon::OutlinedBriefcase)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('semestre_actual_id')
                    ->label('Semestre')
                    ->badge()
                    ->sortable()
                    ->color(fn ($state): string => match ($state?->value) {
                        'Primero', 'Segundo' => 'success',
                        'Tercero', 'Cuarto' => 'warning',
                        'Quinto', 'Sexto' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state?->value ? ucfirst($state->value) : '—')
                    ->icon(Heroicon::OutlinedChartBar),
                TextColumn::make('generacion.clave')
                    ->label('Gen.')
                    ->badge()
                    ->color('gray')
                    ->icon(Heroicon::OutlinedCalendar)
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('grupos.nombre')
                    ->label('Grupo')
                    ->badge()
                    ->color('primary')
                    ->icon(Heroicon::OutlinedUsers)
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->toggleable(),
                TextColumn::make('fecha_ingreso')
                    ->label('Ingreso')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon(Heroicon::OutlinedCalendarDays),
                TextColumn::make('created_at')
                    ->label('Alta')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->searchable()
            ->searchPlaceholder('Buscar por matrícula, nombre o CURP…')
            ->searchUsing(fn (Builder $query, string $search): Builder => $query
                ->where('matricula', 'like', "%{$search}%")
                ->orWhereHas('user', fn (Builder $q) => $q
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('curp', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                ))
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50])
            ->emptyStateHeading('Sin alumnos registrados')
            ->emptyStateDescription('Crea el primer expediente o importa desde CSV.')
            ->emptyStateIcon(Heroicon::OutlinedUserGroup)
            ->filters([
                SelectFilter::make('carrera_id')
                    ->label('Carrera')
                    ->relationship('carrera', 'nombre')
                    ->multiple()
                    ->preload()
                    ->indicator('Carrera'),
                SelectFilter::make('semestre_actual_id')
                    ->label('Semestre')
                    ->options(Semestre::class)
                    ->multiple(),
                SelectFilter::make('generacion_id')
                    ->label('Generación')
                    ->relationship('generacion', 'clave')
                    ->multiple()
                    ->preload(),
            ])
            ->filtersTriggerAction(fn ($action) => $action->button()->label('Filtros'))
            ->actions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Editar'),
                Action::make('imprimir_boleta')
                    ->label('Boleta')
                    ->icon(Heroicon::OutlinedPrinter)
                    ->color('success')
                    ->button()
                    ->url(fn (Alumno $record): string => route('boletas.download', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListAlumnos::route('/'),
            'create' => Pages\CreateAlumno::route('/create'),
            'edit' => Pages\EditAlumno::route('/{record}/edit'),
        ];
    }
}
