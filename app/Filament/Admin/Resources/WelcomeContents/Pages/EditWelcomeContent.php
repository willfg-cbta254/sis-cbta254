<?php

namespace App\Filament\Admin\Resources\WelcomeContents\Pages;

use App\Filament\Admin\Resources\WelcomeContents\WelcomeContentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWelcomeContent extends EditRecord
{
    protected static string $resource = WelcomeContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
