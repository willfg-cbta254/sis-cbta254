<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BlogResource\Pages;
use App\Models\BlogPost;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class BlogResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static string|UnitEnum|null $navigationGroup = 'Pagina';

    protected static ?string $modelLabel = 'Entrada de Blog';

    protected static ?string $pluralModelLabel = 'Entradas del Blog';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->live()
                ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            RichEditor::make('content')
                ->required()
                ->label('Contenido')
                ->columnSpanFull(),
            Textarea::make('excerpt')
                ->label('Extracto')
                ->maxLength(500)
                ->columnSpanFull(),
            FileUpload::make('featured_image')
                ->label('Imagen destacada')
                ->image()
                ->maxSize(5120)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->preventFilePathTampering()
                ->directory('blog-images')
                ->columnSpanFull(),
            Select::make('author_id')
                ->required()
                ->label('Autor')
                ->relationship('author', 'name'),
            Toggle::make('is_published')
                ->label('Publicado'),
            DateTimePicker::make('published_at')
                ->label('Fecha de publicación'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Título')
                    ->limit(50),
                TextColumn::make('author.name')
                    ->label('Autor')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->boolean()
                    ->sortable()
                    ->label('Publicado'),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Publicado el'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable()
                    ->sortable()
                    ->label('Creado'),
            ])
            ->filters([
                Filter::make('is_published')
                    ->label('Publicados')
                    ->toggle()
                    ->query(fn ($q) => $q->where('is_published', true)),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
