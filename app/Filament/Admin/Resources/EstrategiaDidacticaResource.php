<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EstrategiaDidacticaResource\Pages;
use App\Models\EstrategiaDidactica;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class EstrategiaDidacticaResource extends Resource
{
    protected static ?string $model = EstrategiaDidactica::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?string $modelLabel = 'Estrategia Didáctica';

    protected static ?string $pluralModelLabel = 'Estrategias Didácticas';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('profesor_id')
                ->relationship('profesor', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->numero_empleado} - {$record->user->name}")
                ->searchable()
                ->preload()
                ->required()
                ->label('Profesor'),
            Select::make('materia_id')
                ->relationship('materia', 'nombre')
                ->searchable()
                ->preload()
                ->required()
                ->label('Materia'),
            Select::make('periodo_escolar_id')
                ->relationship('periodoEscolar', 'clave')
                ->searchable()
                ->preload()
                ->required()
                ->label('Periodo Escolar'),
            TextInput::make('titulo')
                ->required()
                ->maxLength(255)
                ->label('Título'),
            Textarea::make('descripcion')
                ->maxLength(65535)
                ->label('Descripción')
                ->columnSpanFull(),
            FileUpload::make('archivo')
                ->required()
                ->disk('public')
                ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->maxSize(10240)
                ->directory('estrategias-didacticas')
                ->label('Archivo (PDF o Word)')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('profesor.user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Profesor'),
                TextColumn::make('titulo')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->label('Título'),
                TextColumn::make('materia.nombre')
                    ->badge()
                    ->label('Materia'),
                TextColumn::make('periodoEscolar.clave')
                    ->badge()
                    ->label('Periodo'),
                IconColumn::make('archivo')
                    ->icon(fn (string $state): string => str_ends_with($state, '.pdf') ? 'heroicon-o-document' : 'heroicon-o-document-text')
                    ->color('primary')
                    ->url(fn (EstrategiaDidactica $record): string => route('estrategias.stream', $record))
                    ->openUrlInNewTab()
                    ->label('Archivo'),
                TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->label('Subida'),
            ])
            ->filters([
                SelectFilter::make('profesor_id')
                    ->relationship('profesor', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->numero_empleado} - {$record->user->name}")
                    ->searchable()
                    ->preload()
                    ->label('Profesor'),
                SelectFilter::make('materia_id')
                    ->relationship('materia', 'nombre')
                    ->label('Materia'),
                SelectFilter::make('periodo_escolar_id')
                    ->relationship('periodoEscolar', 'clave')
                    ->label('Periodo'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstrategiaDidacticas::route('/'),
            'create' => Pages\CreateEstrategiaDidactica::route('/create'),
            'edit' => Pages\EditEstrategiaDidactica::route('/{record}/edit'),
        ];
    }
}
