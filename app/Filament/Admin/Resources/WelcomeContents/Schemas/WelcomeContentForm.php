<?php

namespace App\Filament\Admin\Resources\WelcomeContents\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class WelcomeContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Hero')
                        ->description('Diapositivas del carrusel principal')
                        ->schema([
                            Repeater::make('hero_slides')
                                ->label('Diapositivas')
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('badge')
                                            ->label('Etiqueta / Badge')
                                            ->maxLength(255),
                                        TextInput::make('title')
                                            ->label('Título')
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        Textarea::make('description')
                                            ->label('Descripción')
                                            ->maxLength(1000)
                                            ->columnSpanFull(),
                                        FileUpload::make('image')
                                            ->label('Imagen de fondo')
                                            ->image()
                                            ->disk('public')
                                            ->maxSize(5120)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->directory('welcome')
                                            ->columnSpanFull(),
                                        FileUpload::make('video')
                                            ->label('Video de fondo')
                                            ->disk('public')
                                            ->maxSize(102400)
                                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                                            ->directory('welcome')
                                            ->columnSpanFull(),
                                        TextInput::make('btn_text')
                                            ->label('Texto botón principal')
                                            ->maxLength(255),
                                        TextInput::make('btn_secondary_text')
                                            ->label('Texto botón secundario')
                                            ->maxLength(255),
                                    ]),
                                ])
                                ->defaultItems(1)
                                ->minItems(0)
                                ->maxItems(10)
                                ->addable()
                                ->reorderable()
                                ->deletable()
                                ->columnSpanFull(),
                        ]),

                    Step::make('Convocatorias')
                        ->description('Avisos y convocatorias')
                        ->schema([
                            Repeater::make('announcements')
                                ->label('Convocatorias')
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('title')
                                            ->label('Título')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('badge')
                                            ->label('Etiqueta')
                                            ->maxLength(255),
                                        Select::make('badge_color')
                                            ->label('Color de etiqueta')
                                            ->options([
                                                'green' => 'Verde',
                                                'blue' => 'Azul',
                                                'amber' => 'Ámbar',
                                            ]),
                                        TextInput::make('date')
                                            ->label('Fecha')
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->label('Descripción')
                                            ->required()
                                            ->maxLength(1000),
                                    ]),
                                ])
                                ->defaultItems(0)
                                ->minItems(0)
                                ->maxItems(10)
                                ->addable()
                                ->reorderable()
                                ->deletable(),
                        ]),

                    Step::make('Contacto')
                        ->description('Información de contacto')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('contact_address')
                                    ->label('Dirección')
                                    ->maxLength(500)
                                    ->columnSpanFull(),
                                TextInput::make('contact_email')
                                    ->label('Correo electrónico')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('contact_phone')
                                    ->label('Teléfono')
                                    ->maxLength(255),
                                Textarea::make('footer_description')
                                    ->label('Descripción pie de página')
                                    ->maxLength(500),
                            ]),
                        ]),

                    Step::make('SEO')
                        ->description('Optimización para buscadores')
                        ->schema([
                            TextInput::make('meta_title')
                                ->label('Título SEO')
                                ->maxLength(255),
                            Textarea::make('meta_description')
                                ->label('Descripción SEO')
                                ->maxLength(500),
                        ]),
                ])->skippable()->columnSpanFull(),
            ]);
    }
}
