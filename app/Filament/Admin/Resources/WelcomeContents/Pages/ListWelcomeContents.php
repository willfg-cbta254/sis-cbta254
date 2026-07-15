<?php

namespace App\Filament\Admin\Resources\WelcomeContents\Pages;

use App\Filament\Admin\Resources\WelcomeContents\WelcomeContentResource;
use App\Models\WelcomeContent;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWelcomeContents extends ListRecords
{
    protected static string $resource = WelcomeContentResource::class;

    public function mount(): void
    {
        parent::mount();

        $record = WelcomeContent::first();

        if ($record) {
            $this->redirect(WelcomeContentResource::getUrl('edit', ['record' => $record]));
        }
    }

    protected function getHeaderActions(): array
    {
        $exists = WelcomeContent::exists();

        return $exists ? [] : [
            CreateAction::make(),
        ];
    }
}
