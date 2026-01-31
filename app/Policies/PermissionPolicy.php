<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_permission') || $user->hasRole('admin');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->can('view_permission') || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->can('create_permission') || $user->hasRole('admin');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->can('update_permission') || $user->hasRole('admin');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->can('delete_permission') || $user->hasRole('admin');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_permission') || $user->hasRole('admin');
    }
}
