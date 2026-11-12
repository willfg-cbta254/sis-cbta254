<?php

namespace App\Filament\Profesor\Resources\SolicitudPermisos\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class SolicitudPermisoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('fecha_inicio')
                    ->label('Fecha de inicio')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::calcularDias($set, $get)),
                DatePicker::make('fecha_fin')
                    ->label('Fecha de fin')
                    ->required()
                    ->afterOrEqual('fecha_inicio')
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::calcularDias($set, $get)),
                TextInput::make('dias_solicitados')
                    ->label('Días solicitados')
                    ->disabled()
                    ->dehydrated(false)
                    ->suffix('días')
                    ->helperText('Se calcula automáticamente a partir de las fechas.'),
                Textarea::make('motivo')
                    ->label('Motivo del permiso')
                    ->required()
                    ->rows(5)
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    private static function calcularDias(Set $set, Get $get): void
    {
        $inicio = $get('fecha_inicio');
        $fin = $get('fecha_fin');

        if (blank($inicio) || blank($fin)) {
            $set('dias_solicitados', null);

            return;
        }

        $inicio = Carbon::parse($inicio);
        $fin = Carbon::parse($fin);

        if ($fin->lessThan($inicio)) {
            $set('dias_solicitados', null);

            return;
        }

        $set('dias_solicitados', (int) $inicio->diffInDays($fin) + 1);
    }
}
