<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password', 'avatar_path'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    private const PROJECT_OWNER_ID = 2;

    private ?bool $spatiePermissionTablesExist = null;

    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    public function isProjectOwner(): bool
    {
        return $this->exists && (int) $this->getKey() === self::PROJECT_OWNER_ID;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() !== 'admin') {
            return false;
        }

        if ($this->isProjectOwner()) {
            return true;
        }

        if (! Schema::hasTable('roles') || ! Schema::hasTable('model_has_roles')) {
            return false;
        }

        return $this->hasAnyRole('super_admin', 'panel_user');
    }

    /**
     * Let Spatie's Gate integration fail closed until its tables exist.
     */
    public function checkPermissionTo($permission, ?string $guardName = null): bool
    {
        if (! $this->hasSpatiePermissionTables()) {
            return false;
        }

        try {
            return $this->hasPermissionTo($permission, $guardName);
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist) {
            return false;
        }
    }

    public function hasShieldPermission(string $permission): bool
    {
        if (! $this->hasSpatiePermissionTables()) {
            return false;
        }

        $permissionModel = app(\Spatie\Permission\PermissionRegistrar::class)->getPermissionClass();
        $permissionRecord = $permissionModel::query()
            ->where('name', $permission)
            ->where('guard_name', 'web')
            ->first();

        return $permissionRecord !== null && $this->hasPermissionTo($permissionRecord);
    }

    private function hasSpatiePermissionTables(): bool
    {
        if ($this->spatiePermissionTablesExist !== null) {
            return $this->spatiePermissionTablesExist;
        }

        foreach ([
            config('permission.table_names.permissions', 'permissions'),
            config('permission.table_names.roles', 'roles'),
            config('permission.table_names.model_has_permissions', 'model_has_permissions'),
            config('permission.table_names.model_has_roles', 'model_has_roles'),
            config('permission.table_names.role_has_permissions', 'role_has_permissions'),
        ] as $table) {
            if (! is_string($table) || ! Schema::hasTable($table)) {
                return $this->spatiePermissionTablesExist = false;
            }
        }

        return $this->spatiePermissionTablesExist = true;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
    /**
 * Get the user's shopping cart.
 */
public function cart(): HasOne
{
    return $this->hasOne(Cart::class);
}

/**
 * Orders placed by this user.
 */
public function orders(): HasMany
{
    return $this->hasMany(Order::class);
}
}
