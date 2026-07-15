<?php

namespace App\Filament\Admin\Resources\MateriaResource\Pages;

use App\Filament\Admin\Resources\MateriaResource;
use App\Filament\Imports\MateriaImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListMaterias extends ListRecords
{
    protected static string $resource = MateriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(MateriaImporter::class)
                ->label('Importar')
                ->icon('heroicon-o-arrow-up-tray'),
            CreateAction::make(),
        ];
    }
}
