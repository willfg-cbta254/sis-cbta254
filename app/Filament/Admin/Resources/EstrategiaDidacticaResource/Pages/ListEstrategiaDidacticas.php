<?php

namespace App\Filament\Admin\Resources\EstrategiaDidacticaResource\Pages;

use App\Filament\Admin\Pages\EstrategiasPorDocente;
use App\Filament\Admin\Resources\EstrategiaDidacticaResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEstrategiaDidacticas extends ListRecords
{
    protected static string $resource = EstrategiaDidacticaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('verPorDocente')
                ->label('Ver por Docente')
                ->icon('heroicon-o-users')
                ->color('gray')
                ->url(fn (): string => EstrategiasPorDocente::getUrl()),
        ];
    }
}
