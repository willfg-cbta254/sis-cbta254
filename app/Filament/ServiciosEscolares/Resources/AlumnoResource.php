<?php

namespace App\Filament\ServiciosEscolares\Resources;

use App\Enums\Semestre;
use App\Filament\ServiciosEscolares\Resources\AlumnoResource\Pages;
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

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Datos del usuario')->schema([
                TextInput::make('user.name')->required()->maxLength(255)->label('Nombre completo'),
                TextInput::make('user.email')->required()->email()->rule(fn ($component) => Rule::unique('users', 'email')->ignore($component->getRecord()?->user?->id)),
                TextInput::make('user.password')
                    ->password()->revealable()
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->label('Contraseña'),
                TextInput::make('user.curp')->required()->maxLength(18)->rule(fn ($component) => Rule::unique('users', 'curp')->ignore($component->getRecord()?->user?->id)),
                TextInput::make('user.telefono')->tel()->maxLength(15),
                DatePicker::make('user.fecha_nacimiento')->required(),
                Textarea::make('user.direccion')->columnSpanFull(),
            ])->columns(2),
            Section::make('Datos escolares')->schema([
                TextInput::make('matricula')->required()->unique(ignoreRecord: true)->maxLength(20),
                Select::make('generacion_id')->relationship('generacion', 'clave')->required(),
                Select::make('carrera_id')->relationship('carrera', 'nombre')->required(),
                Select::make('semestre_actual_id')
                    ->options(Semestre::class)
                    ->label('Semestre')->required(),
                DatePicker::make('fecha_ingreso')->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('matricula')->sortable()->copyable(),
                TextColumn::make('user.name')->sortable()->label('Nombre'),
                TextColumn::make('user.curp')->label('CURP')->toggleable(),
                TextColumn::make('carrera.nombre')->badge()->sortable(),
                TextColumn::make('semestre_actual_id')->badge()->label('Semestre')->sortable()->formatStateUsing(fn ($state) => $state?->value ? ucfirst($state->value) : ''),
                TextColumn::make('generacion.clave')->badge()->toggleable(),
                TextColumn::make('created_at')->dateTime()->toggleable()->sortable(),
            ])
            ->searchable()
            ->searchUsing(fn (Builder $query, string $search): Builder => $query
                ->where('matricula', 'like', "%{$search}%")
                ->orWhereHas('user', fn (Builder $q) => $q
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('curp', 'like', "%{$search}%")
                ))
            ->filters([
                SelectFilter::make('carrera_id')->relationship('carrera', 'nombre'),
                SelectFilter::make('semestre_actual_id')->options(Semestre::class),
                SelectFilter::make('generacion_id')->relationship('generacion', 'clave'),
            ])
            ->actions([
                EditAction::make(),
                Action::make('imprimir_boleta')
                    ->label('Boleta')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('boletas.download', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
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
