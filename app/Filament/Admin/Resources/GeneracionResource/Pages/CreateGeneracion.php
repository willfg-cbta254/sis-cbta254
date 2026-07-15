<?php

namespace App\Filament\Admin\Resources\GeneracionResource\Pages;

use App\Filament\Admin\Resources\GeneracionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGeneracion extends CreateRecord
{
    protected static string $resource = GeneracionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl('index');
    }
}
