<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ProductPolicy extends ShieldResourcePolicy
{
    protected const SUBJECT = 'Product';

    public function delete(User $user, Model $record): bool
    {
        return $this->allows($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
