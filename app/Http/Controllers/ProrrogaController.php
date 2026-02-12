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
                    $evidenciaPath = Storage::disk('public')->putFile('proyectos/prorrogas', $file);
                    $evidenciaNombre = $file->getClientOriginalName();
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
                'evidencia_url'        => $p->evidencia_url,
                'evidencia_nombre'     => $p->evidencia_nombre_original,
                'departamento'         => $p->departamento_afectado,
                'referencia_ideam'     => $p->referencia_ideam,
            ]);

        return response()->json(['prorrogas' => $prorrogas]);
    }
}
