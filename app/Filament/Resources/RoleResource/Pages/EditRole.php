<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Permission;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get current role's permissions
        $role = $this->record;
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        // Group permissions by module
        $permissions = Permission::all();
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $parts = explode('_', $permission->name);
            if (count($parts) >= 2) {
                $module = implode('_', array_slice($parts, 1));
                $groupedPermissions[$module][] = $permission->id;
            }
        }

        // Populate each module's checkbox field
        foreach ($groupedPermissions as $module => $permissionIds) {
            $data["permissions_{$module}"] = array_values(array_intersect($rolePermissionIds, $permissionIds));
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // Merge all module permission fields and sync to role
        $allPermissions = [];

        foreach ($this->data as $key => $value) {
            if (str_starts_with($key, 'permissions_') && is_array($value)) {
                $allPermissions = array_merge($allPermissions, $value);
            }
        }

        $this->record->permissions()->sync(array_unique($allPermissions));
    }
}
