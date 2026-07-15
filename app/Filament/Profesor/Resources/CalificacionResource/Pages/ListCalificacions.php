<?php

namespace App\Filament\Profesor\Resources\CalificacionResource\Pages;

use App\Filament\Profesor\Pages\CargarCalificaciones;
use App\Filament\Profesor\Resources\CalificacionResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListCalificacions extends ListRecords
{
    protected static string $resource = CalificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cargarCalificaciones')
                ->label('Cargar Calificaciones')
                ->icon('heroicon-o-table-cells')
                ->url(fn (): string => CargarCalificaciones::getUrl())
                ->color('success'),
        ];
    }
}
