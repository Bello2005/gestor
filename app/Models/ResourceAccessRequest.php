<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceAccessRequest extends Model
{
    use Auditable;

    protected $fillable = [
        'user_id',
        'permission_id',
        'proyecto_id',
        'requested_access_level',
        'justification',
        'duration_type',
        'starts_at',
        'expires_at',
        'risk_score',
        'risk_level',
        'risk_factors',
        'status',
        'requires_double_approval',
        'approved_by',
        'approved_at',
        'second_approved_by',
        'second_approved_at',
        'rejected_by',
        'rejected_at',
        'decision_rationale',
        'revoked_by',
        'revoked_at',
        'revocation_reason',
    ];

    protected $casts = [
        'risk_factors' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'second_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'revoked_at' => 'datetime',
        'requires_double_approval' => 'boolean',
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

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function secondApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_approved_by');
    }

    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function revoker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    public function riskAuditLogs(): HasMany
    {
        return $this->hasMany(RiskAuditLog::class);
    }

    // --- Scopes ---

    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeByRiskLevel($query, string $level)
    {
        return $query->where('risk_level', $level);
    }

    public function scopeHighRisk($query)
    {
        return $query->whereIn('risk_level', ['alto', 'critico']);
    }

    public function scopeExpiringSoon($query, int $days = 3)
    {
        return $query->where('status', 'aprobada')
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    // --- Helpers ---

    public function isPending(): bool
    {
        return $this->status === 'pendiente';
    }

    public function isApproved(): bool
    {
        return $this->status === 'aprobada';
    }

    public function needsSecondApproval(): bool
    {
        return $this->requires_double_approval && $this->approved_by && !$this->second_approved_by;
    }

    public function isFullyApproved(): bool
    {
        if ($this->requires_double_approval) {
            return $this->approved_by && $this->second_approved_by;
        }
        return (bool) $this->approved_by;
    }

    public function getRiskColorAttribute(): string
    {
        return match ($this->risk_level) {
            'bajo' => 'green',
            'medio' => 'amber',
            'alto' => 'orange',
            'critico' => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pendiente' => 'Pendiente',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            'expirada' => 'Expirada',
            'revocada' => 'Revocada',
            default => $this->status,
        };
    }
}
