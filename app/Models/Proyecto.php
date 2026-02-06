<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Traits\Auditable;

class Proyecto extends Model
{
    use HasFactory, Auditable;

    protected $table = 'proyectos';

    protected $fillable = [
        'nombre_del_proyecto',
        'objeto_contractual',
        'lineas_de_accion',
        'cobertura',
        'entidad_contratante',
        'fecha_de_ejecucion',
        'plazo',
        'valor_total',
        'cargar_archivo_proyecto',
        'cargar_contrato_o_convenio',
        'cargar_evidencias',
        'estado',
        'nivel_criticidad',
    ];

    protected $casts = [
        'fecha_de_ejecucion' => 'date',
        'valor_total' => 'decimal:2',
        'plazo' => 'decimal:2',
        'cargar_evidencias' => 'array'
    ];

    protected $attributes = [
        'cargar_evidencias' => '[]'
    ];

    public function getCargarEvidenciasAttribute($value)
    {
        try {
            // Si el valor es nulo o vacío, retornar array vacío
            if (empty($value)) {
                return [];
            }

            // Si es string, intentar decodificar
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return [];
                }
                $value = $decoded;
            }

            // Si no es array después de decodificar, retornar vacío
            if (!is_array($value)) {
                return [];
            }

            // Filtrar elementos vacíos y no válidos
            return array_values(array_filter($value, function($item) {
                return is_string($item) && !empty(trim($item));
            }));
        } catch (\Exception $e) {
            Log::error('Error procesando evidencias: ' . $e->getMessage());
            return [];
        }
    }

    public function setCargarEvidenciasAttribute($value)
    {
        try {
            // Si es nulo o vacío, guardar array vacío
            if (empty($value)) {
                $this->attributes['cargar_evidencias'] = '[]';
                return;
            }

            // Si ya es string JSON válido, usarlo directamente
            if (is_string($value)) {
                json_decode($value);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->attributes['cargar_evidencias'] = $value;
                    return;
                }
            }

            // Si es array, convertir a JSON
            if (is_array($value)) {
                $filtered = array_values(array_filter($value, function($item) {
                    return is_string($item) && !empty(trim($item));
                }));
                $this->attributes['cargar_evidencias'] = json_encode($filtered);
                return;
            }

            // En cualquier otro caso, guardar array vacío
            $this->attributes['cargar_evidencias'] = '[]';
        } catch (\Exception $e) {
            Log::error('Error guardando evidencias: ' . $e->getMessage());
            $this->attributes['cargar_evidencias'] = '[]';
        }
    }

    // Accessor para formatear el valor total
    public function getValorTotalFormattedAttribute()
    {
        return $this->valor_total ? number_format((float)$this->valor_total, 2, ',', '.') : '0,00';
    }

    // Accessor para formatear la fecha
    public function getFechaEjecucionFormattedAttribute()
    {
        return $this->fecha_de_ejecucion ? Carbon::parse($this->fecha_de_ejecucion)->format('d/m/Y') : '';
    }

    /**
     * Helper para obtener la URL de un archivo
     * Maneja tanto URLs de Cloudinary como rutas locales
     */
    private function getFileUrl($path)
    {
        if (!$path) {
            return null;
        }

        // Si es una URL completa (Cloudinary), devolverla tal cual
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }

        // Si es una ruta local, usar Storage::disk('public')->url()
        return Storage::disk('public')->url($path);
    }

    /**
     * Accessor para obtener la URL del archivo del proyecto
     */
    public function getArchivoProyectoUrlAttribute()
    {
        return $this->getFileUrl($this->cargar_archivo_proyecto);
    }

    /**
     * Accessor para obtener la URL del contrato/convenio
     */
    public function getContratoConvenioUrlAttribute()
    {
        return $this->getFileUrl($this->cargar_contrato_o_convenio);
    }

    /**
     * Accessor para obtener las URLs de las evidencias
     */
    public function getEvidenciasUrlsAttribute()
    {
        if (!$this->cargar_evidencias || !is_array($this->cargar_evidencias)) {
            return [];
        }

        return array_map(function($evidencia) {
            return $this->getFileUrl($evidencia);
        }, $this->cargar_evidencias);
    }
}