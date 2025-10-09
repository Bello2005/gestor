<?php

namespace App\Http\Controllers;

use App\Models\AccessRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccessRequestApproved;

class AccessRequestController extends Controller
{
    public function create()
    {
        return view('access-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:access_requests|unique:users',
            'phone' => 'nullable|string|max:20',
            'reason' => 'required|string'
        ]);

        try {
            AccessRequest::create($validated);
            Log::info('Nueva solicitud de acceso creada para: ' . $validated['email']);
            
            return redirect()->route('login')
                ->with('success', '¡Tu solicitud de acceso ha sido enviada exitosamente! Te notificaremos por correo cuando sea revisada por nuestro equipo.');
        } catch (\Exception $e) {
            Log::error("Error al crear solicitud de acceso: " . $e->getMessage());
            
            return back()
                ->withErrors(['error' => 'Lo sentimos, hubo un problema al procesar tu solicitud. Por favor, intenta nuevamente.'])
                ->withInput();
        }
    }

    public function index()
    {
        $requests = AccessRequest::orderBy('created_at', 'desc')->get();
        return view('access-requests.index', compact('requests'));
    }

    public function approve(AccessRequest $request)
    {
        // Verificar si la solicitud ya fue procesada
        if ($request->status !== 'pending') {
            return response()->json([
                'error' => 'La solicitud ya ha sido procesada anteriormente'
            ], 422);
        }

        try {
            $user = DB::transaction(function () use ($request) {
            // Crear el usuario con contraseña temporal hasheada
            $temporaryPassword = 'password123';
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($temporaryPassword), // Usar bcrypt explícitamente
                'is_temporary_password' => true
            ]);                // Asignar rol de usuario normal
                $userRole = DB::table('roles')->where('slug', 'user')->first();
                if (!$userRole) {
                    throw new \Exception('No se encontró el rol de usuario');
                }
                DB::table('role_user')->insert([
                    'user_id' => $user->id,
                    'role_id' => $userRole->id
                ]);

                // Actualizar estado de la solicitud
                $request->update([
                    'status' => 'approved',
                    'reviewed_at' => now(),
                    'admin_comment' => 'Solicitud aprobada. Usuario creado.'
                ]);

                return $user;
            });

            // Enviar email con las credenciales
            try {
                Mail::to($user->email)->send(new AccessRequestApproved($user, 'password123'));
                Log::info("Email de bienvenida enviado a {$user->email}");

                return response()->json([
                    'success' => true,
                    'message' => 'Solicitud aprobada y usuario creado exitosamente'
                ]);
            } catch (\Exception $e) {
                Log::error("Error al enviar email a {$user->email}: " . $e->getMessage());
                
                return response()->json([
                    'success' => true,
                    'message' => 'Solicitud aprobada y usuario creado exitosamente, pero hubo un error al enviar el correo electrónico'
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Error al aprobar solicitud de acceso: " . $e->getMessage());
            return response()->json([
                'error' => 'Hubo un error al procesar la solicitud. Por favor, intente nuevamente.',
                'details' => $e->getMessage()
            ], 500);
            report($e);
            
            return response()->json([
                'error' => 'Error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, AccessRequest $accessRequest)
    {
        // Verificar si la solicitud ya fue procesada
        if ($accessRequest->status !== 'pending') {
            return response()->json([
                'error' => 'La solicitud ya ha sido procesada anteriormente'
            ], 422);
        }

        try {
            $validated = $request->validate([
                'admin_comment' => 'required|string'
            ]);

            $accessRequest->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'admin_comment' => $validated['admin_comment']
            ]);

            // TODO: Enviar email al usuario notificando el rechazo
            Log::info("Solicitud de acceso rechazada para {$accessRequest->email}");

            return response()->json([
                'success' => true,
                'message' => 'Solicitud rechazada exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error("Error al rechazar solicitud de acceso: " . $e->getMessage());
            report($e);
            
            return response()->json([
                'error' => 'Error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}