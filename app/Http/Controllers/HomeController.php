<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role->nombre == 'ADMINISTRADOR') {  
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role->nombre == 'INSPECTOR') {
                return redirect()->route('inspector.dashboard');
            } elseif (Auth::user()->role->nombre == 'CLIENTE') {
                return redirect()->route('cliente.dashboard');
            }
        }

        // El usuario no está autenticado
        return redirect()->route('login');
    }
}
