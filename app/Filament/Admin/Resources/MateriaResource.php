<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Semestre;
use App\Enums\TipoMateria;
use App\Filament\Admin\Resources\MateriaResource\Pages;
use App\Models\Materia;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class MateriaResource extends Resource
{
    protected static ?string $model = Materia::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?string $modelLabel = 'Materia';

    protected static ?string $pluralModelLabel = 'Materias';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('nombre')->required()->maxLength(120),
            TextInput::make('clave')->required()->unique(ignoreRecord: true)->maxLength(30),
            Select::make('tipo')
                ->options(array_combine(
                    array_column(TipoMateria::cases(), 'value'),
                    array_column(TipoMateria::cases(), 'value'),
                ))
                ->searchable()->preload()->label('Tipo'),
            Select::make('semestre_id')
                ->label('Semestre')
                ->options(Semestre::class)
                ->searchable()->preload()->required(),
            TextInput::make('horas_semana')->required()->numeric()->default(3)->minValue(1)->maxValue(30),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('clave')->searchable()->sortable(),
                TextColumn::make('nombre')->searchable()->sortable(),
                TextColumn::make('tipo')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (TipoMateria $state): string => $state->value),
                TextColumn::make('semestre_id')->label('Semestre')->sortable()->badge()->formatStateUsing(fn ($state) => $state?->value ? ucfirst($state->value) : ''),
                TextColumn::make('horas_semana')->sortable(),
                TextColumn::make('profesores_count')->counts('profesores')->label('Profesores'),
            ])
            ->filters([
                SelectFilter::make('tipo')->options(array_combine(
                    array_column(TipoMateria::cases(), 'value'),
                    array_column(TipoMateria::cases(), 'value'),
                )),
                SelectFilter::make('semestre_id')->options(Semestre::class),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterias::route('/'),
            'create' => Pages\CreateMateria::route('/create'),
            'edit' => Pages\EditMateria::route('/{record}/edit'),
        ];
    }
}
