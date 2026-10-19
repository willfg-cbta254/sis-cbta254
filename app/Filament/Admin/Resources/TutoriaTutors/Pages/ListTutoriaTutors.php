<?php

namespace App\Filament\Admin\Resources\TutoriaTutors\Pages;

use App\Filament\Admin\Resources\TutoriaTutors\TutoriaTutorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTutoriaTutors extends ListRecords
{
    protected static string $resource = TutoriaTutorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
