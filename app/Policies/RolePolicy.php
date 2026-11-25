<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    private function canManageRoles(User $user): bool
    {
        if (! $user->isProjectOwner()) {
            return false;
        }

        foreach ([
            config('permission.table_names.roles', 'roles'),
            config('permission.table_names.permissions', 'permissions'),
            config('permission.table_names.model_has_roles', 'model_has_roles'),
            config('permission.table_names.model_has_permissions', 'model_has_permissions'),
            config('permission.table_names.role_has_permissions', 'role_has_permissions'),
        ] as $table) {
            if (! is_string($table) || ! Schema::hasTable($table)) {
                return false;
            }
        }

        return true;
    }

    public function viewAny(User $user): bool
    {
        return $this->canManageRoles($user);
    }

    public function view(User $user, Role $role): bool
    {
        return $this->canManageRoles($user);
    }

    public function create(User $user): bool
    {
        return $this->canManageRoles($user);
    }

    public function update(User $user, Role $role): bool
    {
        return $this->canManageRoles($user)
            && $role->name !== 'super_admin';
    }

    public function delete(User $user, Role $role): bool
    {
        return $this->canManageRoles($user)
            && ! in_array($role->name, ['super_admin', 'panel_user'], true);
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
