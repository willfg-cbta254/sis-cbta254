<?php

namespace App\Filament\Admin\Resources\Announcements\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contenido')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->label('Tipo')
                            ->required()
                            ->options([
                                'community' => 'Comunidad (todos)',
                                'group' => 'Grupo(s) específico(s)',
                                'student' => 'Alumno(s) específico(s)',
                            ])
                            ->live()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('grupos', []) || $set('alumnos', [])),
                        RichEditor::make('content')
                            ->label('Contenido')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Destinatarios')
                    ->columns(2)
                    ->schema([
                        Select::make('grupos')
                            ->label('Grupos')
                            ->multiple()
                            ->relationship('grupos', 'nombre')
                            ->visible(fn ($get) => $get('type') === 'group')
                            ->required(fn ($get) => $get('type') === 'group')
                            ->columnSpanFull(),
                        Select::make('alumnos')
                            ->label('Alumnos')
                            ->multiple()
                            ->relationship('alumnos', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->user?->name} ({$record->matricula})")
                            ->searchable(['matricula'])
                            ->visible(fn ($get) => $get('type') === 'student')
                            ->required(fn ($get) => $get('type') === 'student')
                            ->columnSpanFull(),
                    ]),

                Section::make('Publicación')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Publicado'),
                        DateTimePicker::make('published_at')
                            ->label('Fecha de publicación')
                            ->default(now()),
                    ]),
            ]);
    }
}
