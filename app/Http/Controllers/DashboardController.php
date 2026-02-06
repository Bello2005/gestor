<?php

namespace App\Http\Controllers;

use App\Models\AccessRequest;
use App\Models\ResourceAccessRequest;
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
        $activeUsers = User::where('updated_at', '>=', now()->subWeek())->count();

        $recentProjects = Proyecto::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentActivity = [];

        // Role-specific data
        $isAdmin = $user->isAdmin();
        $isGestor = $user->isGestor();

        $pendingAccessRequests = 0;
        $pendingAccountRequests = 0;
        $pendingTasks = 0;

        if ($isAdmin) {
            $pendingAccessRequests = ResourceAccessRequest::where('status', 'pendiente')->count();
            $pendingAccountRequests = AccessRequest::where('status', 'pending')->count();
            $pendingTasks = $pendingAccessRequests + $pendingAccountRequests;
        }

        return view('dashboard', compact(
            'totalProjects',
            'activeProjects',
            'pendingTasks',
            'totalUsers',
            'activeUsers',
            'recentProjects',
            'recentActivity',
            'isAdmin',
            'isGestor',
            'pendingAccessRequests',
            'pendingAccountRequests'
        ));
    }
}
