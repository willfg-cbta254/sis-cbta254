<?php

namespace App\Filament\Profesor\Resources\EstrategiaDidacticaResource\Pages;

use App\Filament\Profesor\Resources\EstrategiaDidacticaResource;
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
