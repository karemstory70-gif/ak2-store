<?php

namespace App\Policies;

use App\Models\StorefrontPage;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class StorefrontPagePolicy extends ShieldResourcePolicy
{
    protected const SUBJECT = 'StorefrontPage';

    private function contentTableExists(): bool
    {
        return Schema::hasTable((new StorefrontPage)->getTable());
    }

    public function viewAny(User $user): bool
    {
        return $this->contentTableExists() && $this->allows($user, 'viewAny');
    }

    public function view(User $user, Model $record): bool
    {
        return $this->contentTableExists() && $this->allows($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->contentTableExists() && $this->allows($user, 'create');
    }

    public function update(User $user, Model $record): bool
    {
        return $this->contentTableExists() && $this->allows($user, 'update');
    }

    public function delete(User $user, Model $record): bool
    {
        return $this->contentTableExists() && $this->allows($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
