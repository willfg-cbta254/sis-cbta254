<?php

namespace App\Filament\Admin\Resources\CargaAcademicaResource\Pages;

use App\Filament\Admin\Resources\CargaAcademicaResource;
use App\Filament\Imports\CargaAcademicaImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListCargaAcademicas extends ListRecords
{
    protected static string $resource = CargaAcademicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(CargaAcademicaImporter::class)
                ->label('Importar CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success'),
            CreateAction::make(),
        ];
    }
}
