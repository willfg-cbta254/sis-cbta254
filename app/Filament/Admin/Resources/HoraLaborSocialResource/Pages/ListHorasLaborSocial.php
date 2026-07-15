<?php

namespace App\Filament\Admin\Resources\HoraLaborSocialResource\Pages;

use App\Filament\Admin\Resources\HoraLaborSocialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHorasLaborSocial extends ListRecords
{
    protected static string $resource = HoraLaborSocialResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
