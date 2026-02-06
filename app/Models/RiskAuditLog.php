<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskAuditLog extends Model
{
    public $timestamps = false;

    protected $table = 'risk_audit_log';

    protected $fillable = [
        'resource_access_request_id',
        'action',
        'actor_id',
        'actor_name',
        'risk_score_at_time',
        'risk_level_at_time',
        'details',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    public function resourceAccessRequest(): BelongsTo
    {
        return $this->belongsTo(ResourceAccessRequest::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'score_calculated' => 'Riesgo calculado',
            'auto_approved' => 'Auto-aprobado',
            'admin_direct_grant' => 'Otorgamiento directo (Admin)',
            'manually_approved' => 'Aprobado manualmente',
            'second_approval' => 'Segunda aprobación',
            'rejected' => 'Rechazado',
            'escalated' => 'Escalado',
            'revoked' => 'Revocado',
            'expired' => 'Expirado',
            default => $this->action,
        };
    }
}
