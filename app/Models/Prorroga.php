<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Prorroga extends Model
{
    use Auditable;

    protected $table = 'prorrogas';

    protected $fillable = [
        'proyecto_id',
        'solicitado_por',
        'tipo_solicitud',
        'causa_tipo',
        'causa_subtipo',
        'dias_solicitados',
        'fecha_fin_original',
        'fecha_fin_propuesta',
        'justificacion',
        'impacto_descripcion',
        'departamento_afectado',
        'referencia_ideam',
        'referencia_declaratoria',
        'evidencia_path',
        'evidencia_nombre_original',
        'estado',
        'aprobado_por',
        'aprobado_en',
        'rechazado_por',
        'rechazado_en',
        'decision_comentario',
    ];

    protected $casts = [
        'fecha_fin_original' => 'date',
        'fecha_fin_propuesta' => 'date',
        'aprobado_en' => 'datetime',
        'rechazado_en' => 'datetime',
        'dias_solicitados' => 'integer',
    ];

    // ─── Constantes ─────────────────────────────────────────

    public const DEPARTAMENTOS_COLOMBIA = [
        'Amazonas', 'Antioquia', 'Arauca', 'Atlantico', 'Bogota D.C.',
        'Bolivar', 'Boyaca', 'Caldas', 'Caqueta', 'Casanare',
        'Cauca', 'Cesar', 'Choco', 'Cordoba', 'Cundinamarca',
        'Guainia', 'Guaviare', 'Huila', 'La Guajira', 'Magdalena',
        'Meta', 'Narino', 'Norte de Santander', 'Putumayo', 'Quindio',
        'Risaralda', 'San Andres y Providencia', 'Santander', 'Sucre',
        'Tolima', 'Valle del Cauca', 'Vaupes', 'Vichada',
    ];

    public const CAUSAS_SUBTIPOS = [
        'fuerza_mayor' => [
            'climatica' => 'Evento Climático (lluvias, sequía, heladas)',
            'sismica' => 'Evento Sísmico',
            'inundacion' => 'Inundación',
            'deslizamiento' => 'Deslizamiento de tierra',
            'otro_natural' => 'Otro evento natural',
        ],
        'caso_fortuito' => [
            'orden_publico' => 'Alteración de orden público',
            'paro' => 'Paro / Protesta social',
            'pandemia' => 'Pandemia / Emergencia sanitaria',
            'otro_humano' => 'Otro evento humano',
        ],
        'necesidad_servicio' => [
            'cambio_alcance' => 'Cambio de alcance',
            'disponibilidad_presupuestal' => 'Disponibilidad presupuestal',
            'ajuste_diseno' => 'Ajuste de diseño técnico',
        ],
        'mutuo_acuerdo' => [
            'conveniencia_partes' => 'Conveniencia de las partes',
        ],
    ];

    // ─── Relaciones ─────────────────────────────────────────

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitado_por');
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function rechazador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rechazado_por');
    }

    // ─── Scopes ─────────────────────────────────────────────

    public function scopePendiente($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAprobada($query)
    {
        return $query->where('estado', 'aprobada');
    }

    public function scopeForProyecto($query, int $proyectoId)
    {
        return $query->where('proyecto_id', $proyectoId);
    }

    // ─── Helpers ────────────────────────────────────────────

    public function isPendiente(): bool
    {
        return $this->estado === 'pendiente';
    }

    public function isAprobada(): bool
    {
        return $this->estado === 'aprobada';
    }

    // ─── Accessors ──────────────────────────────────────────

    public function getCausaTipoLabelAttribute(): string
    {
        return match ($this->causa_tipo) {
            'fuerza_mayor' => 'Fuerza Mayor',
            'caso_fortuito' => 'Caso Fortuito',
            'necesidad_servicio' => 'Necesidad del Servicio',
            'mutuo_acuerdo' => 'Mutuo Acuerdo',
            default => $this->causa_tipo,
        };
    }

    public function getCausaSubtipoLabelAttribute(): string
    {
        $subtipos = self::CAUSAS_SUBTIPOS[$this->causa_tipo] ?? [];
        return $subtipos[$this->causa_subtipo] ?? ($this->causa_subtipo ?? 'N/A');
    }

    public function getEstadoLabelAttribute(): string
    {
        return match ($this->estado) {
            'pendiente' => 'Pendiente',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            default => $this->estado,
        };
    }

    public function getEstadoColorAttribute(): string
    {
        return match ($this->estado) {
            'pendiente' => 'amber',
            'aprobada' => 'green',
            'rechazada' => 'red',
            default => 'gray',
        };
    }

    public function getEvidenciaUrlAttribute(): ?string
    {
        if (!$this->evidencia_path) {
            return null;
        }
        if (str_starts_with($this->evidencia_path, 'http')) {
            return $this->evidencia_path;
        }
        return Storage::disk('public')->url($this->evidencia_path);
    }
}
