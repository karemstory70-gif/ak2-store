<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CategoryPolicy extends ShieldResourcePolicy
{
    protected const SUBJECT = 'Category';

    public function delete(User $user, Model $record): bool
    {
        if (! $record instanceof Category || $record->products()->exists() || $record->children()->exists()) {
            return false;
        }

        return $this->allows($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
