<?php

namespace App\Filament\Admin\Resources\CargaAcademicaResource\Pages;

use App\Filament\Admin\Resources\CargaAcademicaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCargaAcademicas extends ListRecords
{
    protected static string $resource = CargaAcademicaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
