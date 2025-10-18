<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    /**
     * 
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

// public function hasPermissionTo($permission, $guardName = null): bool
// {
//     // Get the active permission record
//     $perm = \Spatie\Permission\Models\Permission::query()
//         ->where('name', $permission)
//         ->where('is_active', 1)
//         ->first();

//     // If not active or not found, deny
//     if (! $perm) {
//         return false;
//     }

//     // 🔍 If user has direct permission and it's active
//     if ($this->permissions()
//         ->where('permissions.id', $perm->id)
//         ->where('permissions.is_active', 1)
//         ->exists()) {
//         return true;
//     }

//     // 🔍 Check through active roles with active permissions
//     return $this->roles()
//         ->where('roles.is_active', 1)
//         ->whereHas('permissions', function ($q) use ($perm) {
//             $q->where('permissions.id', $perm->id)
//               ->where('permissions.is_active', 1);
//         })
//         ->exists();
// }


public function hasPermissionTo($permission, $guardName = null): bool
{
    // Load only active permissions
    $perm = \Spatie\Permission\Models\Permission::query()
        ->where('name', $permission)
        ->where('is_active', 1)
        ->first();

    // 🚫 If the permission doesn’t exist or is inactive
    if (! $perm) {
        return false;
    }

    // ✅ Check if user has it directly and it’s active
    if ($this->permissions()
        ->where('permissions.id', $perm->id)
        ->where('permissions.is_active', 1)
        ->exists()) {
        return true;
    }

    // ✅ Check via active roles with active permissions
    return $this->roles()
        ->where('roles.is_active', 1)
        ->whereHas('permissions', function ($q) use ($perm) {
            $q->where('permissions.id', $perm->id)
              ->where('permissions.is_active', 1);
        })
        ->exists();
}




public function hasRole($roles, $guard = null): bool
{
    $activeRoles = $this->roles()->where('is_active', 1)->pluck('name')->toArray();

    foreach ((array) $roles as $role) {
        if (in_array($role, $activeRoles)) {
            return true;
        }
    }

    return false;
}

}
