<?php

namespace App\Filament\Admin\Resources\FichaResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FichaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del Estudiante')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nombre_completo')
                            ->label('Nombre Completo')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('fecha_nacimiento')
                            ->label('Fecha de Nacimiento')
                            ->required(),
                        TextInput::make('edad')
                            ->label('Edad')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        Select::make('genero')
                            ->label('Género')
                            ->required()
                            ->options([
                                'femenino' => 'Femenino',
                                'masculino' => 'Masculino',
                            ]),
                        Textarea::make('direccion')
                            ->label('Dirección')
                            ->required(),
                        TextInput::make('num_celular')
                            ->label('Núm. Celular')
                            ->required()
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('correo_electronico')
                            ->label('Correo Electrónico')
                            ->required()
                            ->email()
                            ->maxLength(255),
                        TextInput::make('localidad')
                            ->label('Localidad')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('municipio')
                            ->label('Municipio')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Datos de Secundaria')
                    ->columns(2)
                    ->schema([
                        TextInput::make('secundaria_procedencia')
                            ->label('Secundaria de Procedencia')
                            ->required()
                            ->maxLength(255),
                        Select::make('tipo_secundaria')
                            ->label('Tipo de Secundaria')
                            ->required()
                            ->options([
                                'tecnica' => 'Técnica',
                                'telesecundaria' => 'Telesecundaria',
                            ]),
                        TextInput::make('localidad_secundaria')
                            ->label('Localidad de la Escuela de Procedencia')
                            ->required()
                            ->maxLength(255),
                        Select::make('turno')
                            ->label('Turno')
                            ->required()
                            ->options([
                                'matutino' => 'Matutino',
                                'vespertino' => 'Vespertino',
                            ]),
                        TextInput::make('promedio')
                            ->label('Promedio')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01),
                    ]),

                Section::make('Datos del Tutor Legal')
                    ->columns(2)
                    ->schema([
                        TextInput::make('tutor_nombre_completo')
                            ->label('Nombre Completo')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tutor_num_celular')
                            ->label('Núm. Celular')
                            ->required()
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('tutor_correo_electronico')
                            ->label('Correo Electrónico')
                            ->required()
                            ->email()
                            ->maxLength(255),
                    ]),
            ]);
    }
}
