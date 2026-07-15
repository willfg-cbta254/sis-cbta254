<?php

namespace App\Filament\Admin\Resources\RoleResource\Pages;

use App\Filament\Admin\Resources\RoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    private array $savedPermissionIds = [];

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $permissions = $this->record->permissions()->pluck('id', 'name');

        foreach (RoleResource::getPermissionGroups() as $label => $entity) {
            $key = 'perm_'.Str::slug($entity, '_');

            $data[$key] = $permissions
                ->filter(fn ($id, string $name) => str_ends_with($name, " {$entity}"))
                ->values()
                ->toArray();
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function afterSave(): void
    {
        $this->record->syncPermissions($this->savedPermissionIds);
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
