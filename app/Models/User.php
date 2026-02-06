<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use \App\Traits\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'is_temporary_password',
    ];

    /**
     * Accessor: mapea 'name' a 'full_name' para compatibilidad.
     */
    public function getNameAttribute(): ?string
    {
        return $this->attributes['full_name'] ?? null;
    }

    /**
     * Mutator: mapea 'name' a 'full_name' para compatibilidad.
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['full_name'] = $value;
    }



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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_temporary_password' => 'boolean',
        ];
    }

    // Relación con roles (muchos a muchos)
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function passwordResetHistory()
    {
        return $this->hasMany(PasswordResetHistory::class);
    }

    public function latestPasswordReset()
    {
        return $this->hasOne(PasswordResetHistory::class)
            ->latest();
    }

    public function resourceAccessRequests()
    {
        return $this->hasMany(ResourceAccessRequest::class);
    }

    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    public function activePermissions()
    {
        return $this->userPermissions()->active();
    }

    public function hasDirectPermission(string $permissionSlug, ?int $proyectoId = null): bool
    {
        $query = $this->userPermissions()
            ->active()
            ->whereHas('permission', fn($q) => $q->where('slug', $permissionSlug));

        if ($proyectoId) {
            $query->where(fn($q) => $q->whereNull('proyecto_id')->orWhere('proyecto_id', $proyectoId));
        }

        return $query->exists();
    }

    /**
     * Verifica si el usuario tiene un rol específico
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }
        return false;
    }

    /**
     * Asigna un rol al usuario
     *
     * @param string|Role $role
     * @return $this
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }
        if (!$this->hasRole($role)) {
            $this->roles()->attach($role);
        }
        return $this;
    }

    /**
     * Verifica si el usuario tiene alguno de los roles especificados
     *
     * @param array $roleNames
     * @return bool
     */
    public function hasAnyRole($roleNames)
    {
        return $this->roles()
            ->whereIn('slug', (array) $roleNames)
            ->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN);
    }

    public function isGestor(): bool
    {
        return $this->hasRole(Role::GESTOR);
    }

    public function isColaborador(): bool
    {
        return $this->hasRole(Role::COLABORADOR);
    }

    public function isExemptFromAccessRequests(): bool
    {
        return $this->isAdmin();
    }

    public function getRoleTrustBonus(): int
    {
        if ($this->isGestor()) {
            return -15;
        }
        return 0;
    }
}