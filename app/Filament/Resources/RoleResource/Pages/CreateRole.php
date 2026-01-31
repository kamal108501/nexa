<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Permission;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function afterCreate(): void
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
