<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_role') || $user->hasRole('admin');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->can('view_role') || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->can('create_role') || $user->hasRole('admin');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->can('update_role') || $user->hasRole('admin');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can('delete_role') || $user->hasRole('admin');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_role') || $user->hasRole('admin');
    }
}
