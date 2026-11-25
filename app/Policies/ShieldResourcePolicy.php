<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class ShieldResourcePolicy{
    protected const SUBJECT = '';

    protected function allows(User $user, string $ability): bool
    {
        if ($user->isProjectOwner()) {
            return true;
        }

        return $user->hasShieldPermission(Str::studly($ability).':'.static::SUBJECT);
    }

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'viewAny');
    }

    public function view(User $user, Model $record): bool
    {
        return $this->allows($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'create');
    }

    public function update(User $user, Model $record): bool
    {
        return $this->allows($user, 'update');
    }

    public function delete(User $user, Model $record): bool
    {
        return $this->allows($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allows($user, 'deleteAny');
    }
}
