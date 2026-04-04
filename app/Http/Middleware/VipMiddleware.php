<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VipMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está logueado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a este contenido.');
        }

        $user = Auth::user();
        
        // Usar el método isVip() que definimos en el modelo User
        if (!$user->isVip()) {
            return redirect()->route('gift-codes.redeem')->with('error', 'Necesitas una membresía VIP activa para acceder a este contenido VIP.');
        }

        return $next($request);
    }
}