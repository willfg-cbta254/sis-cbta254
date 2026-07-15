<?php

namespace App\Filament\Admin\Resources\WelcomeContents\Pages;

use App\Filament\Admin\Resources\WelcomeContents\WelcomeContentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWelcomeContent extends CreateRecord
{
    protected static string $resource = WelcomeContentResource::class;
}
