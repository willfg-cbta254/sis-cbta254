<?php

namespace App\Filament\Admin\Resources\EstrategiaDidacticaResource\Pages;

use App\Filament\Admin\Resources\EstrategiaDidacticaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEstrategiaDidactica extends EditRecord
{
    protected static string $resource = EstrategiaDidacticaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
