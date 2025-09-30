<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view("login");
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                "email" => ["required", "email"],
                "password" => ["required"]
            ]);

            $user = User::where("email", $credentials["email"])->first();

            if (!$user) {
                return back()
                    ->withErrors(["email" => "No se encontró ninguna cuenta con este correo electrónico."])
                    ->withInput();
            }

            // Primero verificamos si es una contraseña sin encriptar
            if ($user->password === $credentials["password"]) {
                // Actualizamos la contraseña con versión encriptada
                $user->password = Hash::make($credentials["password"]);
                $user->save();
                
                Auth::login($user);
                $request->session()->regenerate();
                Log::info('Usuario autenticado exitosamente', ['user' => $user->email]);
                
                return redirect()->intended("dashboard")
                    ->with('success', '¡Bienvenido(a) de nuevo, ' . $user->name . '!');
            }
            
            // Si no coincide, intentamos verificar si está encriptada
            if (Hash::check($credentials["password"], $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();
                Log::info('Usuario autenticado exitosamente', ['user' => $user->email]);
                
                return redirect()->intended("dashboard")
                    ->with('success', '¡Bienvenido(a) de nuevo, ' . $user->name . '!');
            }

            Log::warning("Intento fallido de inicio de sesión", ['email' => $credentials["email"]]);
            return back()
                ->withErrors(["password" => "La contraseña ingresada es incorrecta."])
                ->withInput();

        } catch (\RuntimeException $e) {
            Log::error("Error durante el inicio de sesión: " . $e->getMessage());
            return back()
                ->withErrors(["email" => "Ha ocurrido un error durante el inicio de sesión. Por favor, intenta nuevamente."])
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect("/");
    }
}
