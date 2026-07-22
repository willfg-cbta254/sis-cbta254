<?php

namespace App\Filament\Admin\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Imagen')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Imagen')
                            ->image()
                            ->disk('public')
                            ->required()
                            ->maxSize(10240)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->directory('gallery')
                            ->columnSpanFull(),
                        TextInput::make('title')
                            ->label('Título')
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),
                Section::make('Configuración')
                    ->columns(2)
                    ->schema([
                        Select::make('periodo_escolar_id')
                            ->label('Periodo Escolar')
                            ->relationship('periodoEscolar', 'clave')
                            ->required(),
                        Select::make('category')
                            ->label('Categoría')
                            ->options([
                                'instalaciones' => 'Instalaciones',
                                'eventos' => 'Eventos',
                                'actividades' => 'Actividades',
                                'otras' => 'Otras',
                            ])
                            ->placeholder('Seleccionar categoría'),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ]),
            ]);
    }
}
