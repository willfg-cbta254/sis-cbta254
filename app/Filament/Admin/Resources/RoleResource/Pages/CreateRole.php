<?php

namespace App\Filament\Admin\Resources\RoleResource\Pages;

use App\Filament\Admin\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    private array $savedPermissionIds = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $prefix = 'perm_';

        $this->savedPermissionIds = collect($data)
            ->filter(fn ($value, string $key) => str_starts_with($key, $prefix))
            ->flatten()
            ->unique()
            ->values()
            ->map(fn ($id) => (int) $id)
            ->toArray();

        return collect($data)->reject(fn ($value, string $key) => str_starts_with($key, $prefix))->toArray();
    }

    protected function afterCreate(): void
    {
        $this->record->syncPermissions($this->savedPermissionIds);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
