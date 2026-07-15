<?php

namespace App\Filament\Admin\Resources\HoraLaborSocialResource\Pages;

use App\Filament\Admin\Resources\HoraLaborSocialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHoraLaborSocial extends EditRecord
{
    protected static string $resource = HoraLaborSocialResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
