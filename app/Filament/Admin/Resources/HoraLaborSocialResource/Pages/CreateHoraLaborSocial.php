<?php

namespace App\Filament\Admin\Resources\HoraLaborSocialResource\Pages;

use App\Filament\Admin\Resources\HoraLaborSocialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHoraLaborSocial extends CreateRecord
{
    protected static string $resource = HoraLaborSocialResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['registrado_por'] = auth()->id();

        return $data;
    }
}
