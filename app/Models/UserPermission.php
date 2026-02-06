<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{
    use Auditable;

    protected $fillable = [
        'user_id',
        'permission_id',
        'proyecto_id',
        'granted_by',
        'resource_access_request_id',
        'is_temporary',
        'starts_at',
        'expires_at',
        'is_active',
        'revoked_at',
        'revoked_by',
    ];

    protected $casts = [
        'is_temporary' => 'boolean',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    // --- Relationships ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function grantedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function accessRequest(): BelongsTo
    {
        return $this->belongsTo(ResourceAccessRequest::class, 'resource_access_request_id');
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeExpired($query)
    {
        return $query->where('is_temporary', true)
            ->where('is_active', true)
            ->where('expires_at', '<=', now());
    }

    public function scopeExpiringSoon($query, int $days = 3)
    {
        return $query->where('is_active', true)
            ->where('is_temporary', true)
            ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    // --- Helpers ---

    public function isExpired(): bool
    {
        return $this->is_temporary && $this->expires_at && $this->expires_at->isPast();
    }
}
