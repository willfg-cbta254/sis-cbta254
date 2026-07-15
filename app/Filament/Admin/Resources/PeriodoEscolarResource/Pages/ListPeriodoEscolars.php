<?php

namespace App\Filament\Admin\Resources\PeriodoEscolarResource\Pages;

use App\Filament\Admin\Resources\PeriodoEscolarResource;
use App\Filament\Imports\PeriodoEscolarImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListPeriodoEscolars extends ListRecords
{
    protected static string $resource = PeriodoEscolarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(PeriodoEscolarImporter::class)
                ->label('Importar')
                ->icon('heroicon-o-arrow-up-tray'),
            CreateAction::make(),
        ];
    }
}
