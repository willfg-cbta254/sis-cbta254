<?php

namespace App\Filament\Profesor\Resources;

use App\Filament\Profesor\Resources\EstrategiaDidacticaResource\Pages;
use App\Models\EstrategiaDidactica;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EstrategiaDidacticaResource extends Resource
{
    protected static ?string $model = EstrategiaDidactica::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Estrategia Didáctica';

    protected static ?string $pluralModelLabel = 'Estrategias Didácticas';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('profesor_id', auth()->user()?->profesor?->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('materia_id')
                ->relationship(
                    'materia',
                    'nombre',
                    modifyQueryUsing: fn (Builder $query) => $query->whereHas('cargaAcademica', fn (Builder $q) => $q->where('profesor_id', auth()->user()?->profesor?->id)),
                )
                ->searchable()
                ->preload()
                ->required()
                ->label('Materia'),
            Select::make('periodo_escolar_id')
                ->relationship('periodoEscolar', 'clave')
                ->searchable()
                ->preload()
                ->required()
                ->label('Periodo Escolar')
                ->default(fn () => PeriodoEscolar::where('activo', true)->first()?->id),
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
            ->defaultSort('created_at', 'desc');
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
