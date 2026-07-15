<?php

namespace App\Filament\Admin\Resources\ProfesorResource\Pages;

use App\Filament\Admin\Resources\ProfesorResource;
use App\Filament\Imports\ProfesorImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListProfesors extends ListRecords
{
    protected static string $resource = ProfesorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(ProfesorImporter::class)
                ->label('Importar')
                ->icon('heroicon-o-arrow-up-tray'),
            CreateAction::make(),
        ];
    }
}
