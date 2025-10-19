<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Proyecto;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats para las cards superiores
        $totalProjects = Proyecto::count();
        $activeProjects = Proyecto::where('estado', 'activo')->count();
        $totalUsers = User::count();

        // Active users (usuarios que han actualizado su perfil recientemente)
        $activeUsers = User::where('updated_at', '>=', now()->subWeek())->count();

        // Proyectos recientes (últimos 5)
        $recentProjects = Proyecto::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Tareas pendientes (esto depende de tu modelo de tareas)
        // Por ahora lo dejo en 0, puedes actualizarlo cuando tengas el modelo de tareas
        $pendingTasks = 0;

        // Actividad reciente (puedes implementar un modelo de Activity Log después)
        $recentActivity = [];

        return view('dashboard', compact(
            'totalProjects',
            'activeProjects',
            'pendingTasks',
            'totalUsers',
            'activeUsers',
            'recentProjects',
            'recentActivity'
        ));
    }
}
