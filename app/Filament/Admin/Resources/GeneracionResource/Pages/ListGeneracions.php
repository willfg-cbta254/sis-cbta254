<?php

namespace App\Filament\Admin\Resources\GeneracionResource\Pages;

use App\Filament\Admin\Resources\GeneracionResource;
use App\Filament\Imports\GeneracionImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListGeneracions extends ListRecords
{
    protected static string $resource = GeneracionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(GeneracionImporter::class)
                ->label('Importar')
                ->icon('heroicon-o-arrow-up-tray'),
            CreateAction::make(),
        ];
    }
}
