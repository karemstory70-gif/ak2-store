<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BannerPolicy extends ShieldResourcePolicy
{
    protected const SUBJECT = 'Banner';

    public function delete(User $user, Model $record): bool
    {
        return $this->allows($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
