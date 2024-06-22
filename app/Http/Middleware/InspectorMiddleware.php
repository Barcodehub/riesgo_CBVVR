<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InspectorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role->nombre == 'INSPECTOR') {
            return $next($request);
        }

        // abort(401);

        return redirect('/')->withErrors(['error' => 'No tienes permiso para acceder a esta ruta.']);
    }
}
