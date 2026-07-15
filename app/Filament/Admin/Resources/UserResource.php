<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Datos de acceso')->schema([
                FileUpload::make('avatar')->image()->disk('public')->directory('avatars')->maxSize(2048)->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])->preventFilePathTampering()->label('Avatar'),
                TextInput::make('name')->required()->maxLength(255)->label('Nombre completo'),
                TextInput::make('email')->required()->email()->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()->revealable()
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Select::make('roles')
                    ->multiple()->preload()->searchable()
                    ->relationship('roles', 'name')
                    ->label('Roles'),
            ])->columns(2),
            Section::make('Datos personales')->schema([
                TextInput::make('curp')->maxLength(18)->unique(ignoreRecord: true),
                TextInput::make('telefono')->tel()->maxLength(15),
                DatePicker::make('fecha_nacimiento'),
                Textarea::make('direccion')->columnSpanFull(),
                Toggle::make('activo')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')->label('Avatar')->circular(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable()->copyable(),
                TextColumn::make('curp')->searchable()->toggleable(),
                IconColumn::make('activo')->boolean()->sortable(),
                TextColumn::make('roles.name')->badge()->label('Roles'),
                TextColumn::make('created_at')->dateTime()->toggleable()->sortable(),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
