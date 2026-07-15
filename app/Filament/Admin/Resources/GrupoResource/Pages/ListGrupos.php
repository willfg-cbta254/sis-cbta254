<?php

namespace App\Filament\Admin\Resources\GrupoResource\Pages;

use App\Filament\Admin\Resources\GrupoResource;
use App\Filament\Imports\GrupoImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListGrupos extends ListRecords
{
    protected static string $resource = GrupoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(GrupoImporter::class)
                ->label('Importar')
                ->icon('heroicon-o-arrow-up-tray'),
            CreateAction::make(),
        ];
    }
}
