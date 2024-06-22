<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role->nombre == 'CLIENTE') {
            return $next($request);
        }

        return redirect('/')->withErrors(['error' => 'No tienes permiso para acceder a esta ruta.']);
    }
}
