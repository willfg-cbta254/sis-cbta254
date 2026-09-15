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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
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

    protected static ?string $recordTitleAttribute = 'title';

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
            Section::make('Información principal')
                ->description('Título visible y URL amigable de la entrada')
                ->icon(Heroicon::OutlinedDocumentText)
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Título')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon(Heroicon::OutlinedPencilSquare)
                        ->placeholder('Ej. Jornada de Puertas Abiertas 2026')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state)))
                        ->columnSpan(2),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->prefixIcon(Heroicon::OutlinedLink)
                        ->prefix(' /blog/')
                        ->helperText('Se genera automático desde el título. Úsalo para SEO.')
                        ->columnSpan(2),
                ]),

            Section::make('Contenido')
                ->description('Cuerpo del artículo y resumen para listados y SEO')
                ->icon(Heroicon::OutlinedPencil)
                ->schema([
                    RichEditor::make('content')
                        ->label('Contenido')
                        ->required()
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike', 'h2', 'h3', 'blockquote', 'bulletList', 'orderedList', 'link', 'codeBlock', 'undo', 'redo',
                        ]),
                    Textarea::make('excerpt')
                        ->label('Extracto')
                        ->placeholder('Resumen de 1-2 líneas que aparece en tarjetas y redes sociales...')
                        ->maxLength(500)
                        ->rows(3)
                        ->helperText(fn ($state): string => mb_strlen((string) $state).' / 500')
                        ->columnSpanFull(),
                ]),

            Grid::make(2)->schema([
                Section::make('Imagen destacada')
                    ->description('Se muestra en portada y al compartir')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Imagen')
                            ->hiddenLabel()
                            ->image()
                            ->imageEditor()
                            ->imagePreviewHeight('220')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->preventFilePathTampering()
                            ->directory('blog-images')
                            ->helperText('JPG, PNG o WEBP · Máx 5MB · Recomendado 1200×630'),
                    ]),
                Section::make('Publicación')
                    ->description('Control de visibilidad y autoría')
                    ->icon(Heroicon::OutlinedMegaphone)
                    ->schema([
                        Select::make('author_id')
                            ->label('Autor')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required()
                            ->prefixIcon(Heroicon::OutlinedUser),
                        Toggle::make('is_published')
                            ->label('Publicado')
                            ->helperText('Si está apagado la entrada queda como borrador')
                            ->onColor('success')
                            ->offColor('gray')
                            ->onIcon(Heroicon::OutlinedEye)
                            ->offIcon(Heroicon::OutlinedEyeSlash)
                            ->inline(false),
                        DateTimePicker::make('published_at')
                            ->label('Fecha de publicación')
                            ->native(false)
                            ->prefixIcon(Heroicon::OutlinedCalendarDays)
                            ->displayFormat('d/m/Y H:i')
                            ->helperText('Vacío = ahora'),
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('')
                    ->circular(false)
                    ->square()
                    ->size(56)
                    ->defaultImageUrl(fn (): string => 'https://ui-avatars.com/api/?name=Blog&background=0D8ABC&color=fff')
                    ->toggleable(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn (BlogPost $record): string => Str::limit(strip_tags($record->excerpt ?? ''), 60) ?: Str::limit($record->slug, 40))
                    ->limit(42)
                    ->wrap()
                    ->icon(Heroicon::OutlinedDocumentText),
                TextColumn::make('author.name')
                    ->label('Autor')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->icon(Heroicon::OutlinedUser)
                    ->toggleable(),
                IconColumn::make('is_published')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedCheckCircle)
                    ->falseIcon(Heroicon::OutlinedXCircle)
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Publicado el')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->placeholder('Borrador')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50])
            ->emptyStateHeading('Aún no hay entradas')
            ->emptyStateDescription('Crea tu primera noticia para la página principal.')
            ->emptyStateIcon(Heroicon::OutlinedNewspaper)
            ->searchPlaceholder('Buscar por título o autor…')
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Publicado')
                    ->placeholder('Todos')
                    ->trueLabel('Solo publicados')
                    ->falseLabel('Solo borradores'),
                Filter::make('published_at')
                    ->label('Con fecha')
                    ->toggle()
                    ->query(fn ($q) => $q->whereNotNull('published_at')),
            ])
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
