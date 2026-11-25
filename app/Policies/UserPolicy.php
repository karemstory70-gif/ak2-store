<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isProjectOwner() || $user->hasShieldPermission('ViewAny:User');
    }

    public function view(User $user, User $record): bool
    {
        if ($record->isProjectOwner() && ! $user->isProjectOwner()) {
            return false;
        }

        return $user->isProjectOwner() || $user->hasShieldPermission('View:User');
    }

    public function create(User $user): bool
    {
        return $user->isProjectOwner() || $user->hasShieldPermission('Create:User');
    }

    public function update(User $user, User $record): bool
    {
        if ($record->isProjectOwner() || $user->is($record)) {
            return false;
        }

        return $user->isProjectOwner() || $user->hasShieldPermission('Update:User');
    }

    public function delete(User $user, User $record): bool
    {
        if ($record->isProjectOwner()) {
            return false;
        }

        return $user->isProjectOwner() || $user->hasShieldPermission('Delete:User');
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
