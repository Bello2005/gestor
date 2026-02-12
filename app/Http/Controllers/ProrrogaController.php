<?php

namespace App\Http\Controllers;

use App\Models\Prorroga;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProrrogaController extends Controller
{
    /**
     * Listar todas las prórrogas (HTML fragment para tab Prórrogas en Vigilancia).
     */
    public function index(Request $request)
    {
        $query = Prorroga::with(['proyecto:id,nombre_del_proyecto,nivel_criticidad', 'solicitante:id,full_name', 'aprobador:id,full_name', 'rechazador:id,full_name'])
            ->orderByRaw("CASE WHEN estado = 'pendiente' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at');

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('proyecto', fn($q) => $q->where('nombre_del_proyecto', 'ilike', "%{$search}%"));
        }

        $prorrogas = $query->get();

        // KPIs
        $kpis = [
            'pendientes'  => Prorroga::where('estado', 'pendiente')->count(),
            'aprobadas'   => Prorroga::where('estado', 'aprobada')->count(),
            'rechazadas'  => Prorroga::where('estado', 'rechazada')->count(),
            'total'       => Prorroga::count(),
            'avg_dias'    => (int) Prorroga::avg('dias_solicitados'),
        ];

        return response(
            view('analytics.partials._prorrogas', compact('prorrogas', 'kpis'))->render()
        )->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Crear solicitud de prórroga (gestores y admins).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id'             => 'required|exists:proyectos,id',
            'tipo_solicitud'          => 'required|in:prorroga,suspension',
            'causa_tipo'              => 'required|in:fuerza_mayor,caso_fortuito,necesidad_servicio,mutuo_acuerdo',
            'causa_subtipo'           => 'nullable|string|max:30',
            'dias_solicitados'        => 'required|integer|min:1|max:365',
            'justificacion'           => 'required|string|min:30',
            'impacto_descripcion'     => 'nullable|string|max:2000',
            'departamento_afectado'   => 'nullable|string|max:50',
            'referencia_ideam'        => 'nullable|string|max:100',
            'referencia_declaratoria' => 'nullable|string|max:100',
            'evidencia'               => 'nullable|file|mimes:pdf,doc,docx,jpeg,jpg,png|max:10240',
        ]);

        $proyecto = Proyecto::findOrFail($validated['proyecto_id']);

        // No permitir si ya hay una prórroga pendiente para este proyecto
        if (Prorroga::where('proyecto_id', $proyecto->id)->where('estado', 'pendiente')->exists()) {
            return response()->json([
                'error' => 'Ya existe una solicitud de prórroga pendiente para este proyecto.',
            ], 422);
        }

        $fechaFinActual = $proyecto->fecha_fin;
        if (!$fechaFinActual) {
            return response()->json([
                'error' => 'El proyecto no tiene fecha de fin calculable.',
            ], 422);
        }

        $fechaFinPropuesta = $fechaFinActual->copy()->addDays((int) $validated['dias_solicitados']);

        try {
            return DB::transaction(function () use ($validated, $proyecto, $fechaFinActual, $fechaFinPropuesta, $request) {
                $evidenciaPath = null;
                $evidenciaNombre = null;

                if ($request->hasFile('evidencia')) {
                    $file = $request->file('evidencia');
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
                    
                    // Asegurar que el directorio existe
                    $directory = 'proyectos/prorrogas';
                    if (!Storage::disk('public')->exists($directory)) {
                        Storage::disk('public')->makeDirectory($directory);
                    }
                    
                    // Generar hash único corto para evitar conflictos
                    $hash = substr(md5($originalName . time() . uniqid()), 0, 8);
                    
                    // Construir nombre: nombre_original_hash.ext
                    $filename = $nameWithoutExtension . '_' . $hash . ($extension ? '.' . $extension : '');
                    
                    // Guardar con el nombre preservado
                    $evidenciaPath = Storage::disk('public')->putFileAs($directory, $file, $filename);
                    $evidenciaNombre = $originalName;
                    
                    Log::info('Evidencia guardada exitosamente', [
                        'prorroga_id' => 'creating',
                        'evidencia_path' => $evidenciaPath,
                        'evidencia_nombre' => $evidenciaNombre
                    ]);
                }

                $prorroga = Prorroga::create([
                    'proyecto_id'              => $proyecto->id,
                    'solicitado_por'           => Auth::id(),
                    'tipo_solicitud'           => $validated['tipo_solicitud'],
                    'causa_tipo'               => $validated['causa_tipo'],
                    'causa_subtipo'            => $validated['causa_subtipo'] ?? null,
                    'dias_solicitados'         => $validated['dias_solicitados'],
                    'fecha_fin_original'       => $fechaFinActual,
                    'fecha_fin_propuesta'      => $fechaFinPropuesta,
                    'justificacion'            => $validated['justificacion'],
                    'impacto_descripcion'      => $validated['impacto_descripcion'] ?? null,
                    'departamento_afectado'    => $validated['departamento_afectado'] ?? null,
                    'referencia_ideam'         => $validated['referencia_ideam'] ?? null,
                    'referencia_declaratoria'  => $validated['referencia_declaratoria'] ?? null,
                    'evidencia_path'           => $evidenciaPath,
                    'evidencia_nombre_original' => $evidenciaNombre,
                    'estado'                   => 'pendiente',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Solicitud de prórroga creada exitosamente. Pendiente de aprobación.',
                    'prorroga_id' => $prorroga->id,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Error al crear solicitud de prórroga: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
        }
    }

    /**
     * Aprobar solicitud de prórroga (solo admin).
     */
    public function approve(Request $request, Prorroga $prorroga)
    {
        if (!$prorroga->isPendiente()) {
            return response()->json(['error' => 'Esta solicitud ya fue procesada.'], 422);
        }

        $validated = $request->validate([
            'decision_comentario' => 'required|string|min:10',
        ]);

        try {
            return DB::transaction(function () use ($prorroga, $validated) {
                $prorroga->update([
                    'estado'              => 'aprobada',
                    'aprobado_por'        => Auth::id(),
                    'aprobado_en'         => now(),
                    'decision_comentario' => $validated['decision_comentario'],
                ]);

                $prorroga->proyecto->increment('prorroga_dias_aprobados', $prorroga->dias_solicitados);

                return response()->json([
                    'success' => true,
                    'message' => 'Prórroga aprobada. Se han agregado ' . $prorroga->dias_solicitados . ' días al plazo del proyecto.',
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Error al aprobar prórroga: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar la aprobación.'], 500);
        }
    }

    /**
     * Rechazar solicitud de prórroga (solo admin).
     */
    public function reject(Request $request, Prorroga $prorroga)
    {
        if (!$prorroga->isPendiente()) {
            return response()->json(['error' => 'Esta solicitud ya fue procesada.'], 422);
        }

        $validated = $request->validate([
            'decision_comentario' => 'required|string|min:10',
        ]);

        $prorroga->update([
            'estado'              => 'rechazada',
            'rechazado_por'       => Auth::id(),
            'rechazado_en'        => now(),
            'decision_comentario' => $validated['decision_comentario'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Solicitud de prórroga rechazada.',
        ]);
    }

    /**
     * Historial de prórrogas de un proyecto (gestores y admins).
     */
    public function porProyecto(Proyecto $proyecto)
    {
        $prorrogas = $proyecto->prorrogas()
            ->with(['solicitante:id,full_name', 'aprobador:id,full_name'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($p) => [
                'id'                   => $p->id,
                'tipo_solicitud'       => $p->tipo_solicitud,
                'causa_tipo'           => $p->causa_tipo,
                'causa_tipo_label'     => $p->causa_tipo_label,
                'causa_subtipo_label'  => $p->causa_subtipo_label,
                'dias_solicitados'     => $p->dias_solicitados,
                'fecha_fin_original'   => $p->fecha_fin_original->format('d/m/Y'),
                'fecha_fin_propuesta'  => $p->fecha_fin_propuesta->format('d/m/Y'),
                'justificacion'        => $p->justificacion,
                'estado'               => $p->estado,
                'estado_label'         => $p->estado_label,
                'estado_color'         => $p->estado_color,
                'solicitante'          => $p->solicitante?->full_name ?? 'N/A',
                'aprobador'            => $p->aprobador?->full_name ?? null,
                'decision_comentario'  => $p->decision_comentario,
                'created_at'           => $p->created_at->format('d/m/Y H:i'),
                'has_evidencia'        => !empty($p->evidencia_path),
                'evidencia_url'        => !empty($p->evidencia_path) 
                    ? route('prorrogas.download.evidencia', $p) 
                    : null,
                'evidencia_nombre'     => $p->evidencia_nombre_original,
                'departamento'         => $p->departamento_afectado,
                'referencia_ideam'     => $p->referencia_ideam,
            ]);

        return response()->json(['prorrogas' => $prorrogas]);
    }

    /**
     * Descargar evidencia de prórroga
     */
    public function downloadEvidencia(Prorroga $prorroga)
    {
        try {
            if (!$prorroga->evidencia_path) {
                Log::warning('Intento de descargar evidencia sin path', [
                    'prorroga_id' => $prorroga->id
                ]);
                abort(404, 'Evidencia no encontrada');
            }

            // Si es una URL externa (Cloudinary), redirigir
            if (str_starts_with($prorroga->evidencia_path, 'http://') || str_starts_with($prorroga->evidencia_path, 'https://')) {
                return redirect($prorroga->evidencia_path);
            }

            // Normalizar la ruta (eliminar barras iniciales si las hay)
            $evidenciaPath = ltrim($prorroga->evidencia_path, '/');
            
            // Verificar que el archivo existe
            $exists = Storage::disk('public')->exists($evidenciaPath);
            
            if (!$exists) {
                // Intentar buscar el archivo con diferentes variaciones de la ruta
                $basePath = 'proyectos/prorrogas';
                $basename = basename($evidenciaPath);
                
                // Intentar diferentes variaciones de la ruta
                $possiblePaths = [
                    $evidenciaPath, // Ruta original
                    $basePath . '/' . $basename, // Solo el nombre del archivo en el directorio base
                    str_replace('proyectos/prorrogas/', '', $evidenciaPath), // Sin el prefijo del directorio
                ];
                
                // Eliminar duplicados
                $possiblePaths = array_unique($possiblePaths);
                
                Log::warning('Archivo no encontrado en ruta original', [
                    'prorroga_id' => $prorroga->id,
                    'evidencia_path' => $prorroga->evidencia_path,
                    'normalized_path' => $evidenciaPath,
                    'possible_paths' => $possiblePaths,
                    'all_files_in_dir' => Storage::disk('public')->allFiles($basePath)
                ]);
                
                // Intentar con las rutas alternativas
                foreach ($possiblePaths as $testPath) {
                    if (Storage::disk('public')->exists($testPath)) {
                        $path = Storage::disk('public')->path($testPath);
                        $filename = $prorroga->evidencia_nombre_original ?? $basename;
                        Log::info('Archivo encontrado en ruta alternativa', [
                            'prorroga_id' => $prorroga->id,
                            'found_path' => $testPath
                        ]);
                        return response()->download($path, $filename);
                    }
                }
                
                abort(404, 'Archivo no encontrado en el servidor');
            }

            $path = Storage::disk('public')->path($evidenciaPath);
            
            // Verificar que el archivo físico existe
            if (!file_exists($path)) {
                Log::error('Archivo físico no existe aunque Storage dice que existe', [
                    'prorroga_id' => $prorroga->id,
                    'evidencia_path' => $prorroga->evidencia_path,
                    'normalized_path' => $evidenciaPath,
                    'full_path' => $path
                ]);
                abort(404, 'Archivo físico no encontrado');
            }
            
            // Usar el nombre original si está disponible, sino extraer del path
            $filename = $prorroga->evidencia_nombre_original ?? $this->extractOriginalFilename($evidenciaPath);

            return response()->download($path, $filename);
        } catch (\Exception $e) {
            Log::error('Error al descargar evidencia de prórroga', [
                'prorroga_id' => $prorroga->id,
                'evidencia_path' => $prorroga->evidencia_path ?? 'null',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Error al descargar la evidencia: ' . $e->getMessage());
        }
    }

    /**
     * Extraer nombre original del archivo almacenado
     * Formato: nombre_original_hash.ext -> nombre_original.ext
     */
    private function extractOriginalFilename($storedPath)
    {
        $basename = basename($storedPath);
        $extension = pathinfo($basename, PATHINFO_EXTENSION);
        $nameWithoutExt = pathinfo($basename, PATHINFO_FILENAME);
        
        // Si tiene formato nombre_hash.ext, extraer el nombre original
        // Buscar el último underscore seguido de 8 caracteres alfanuméricos (el hash)
        if (preg_match('/^(.+)_([a-f0-9]{8})$/i', $nameWithoutExt, $matches)) {
            return $matches[1] . ($extension ? '.' . $extension : '');
        }
        
        // Si no tiene el formato esperado, devolver el nombre tal cual
        return $basename;
    }
}
