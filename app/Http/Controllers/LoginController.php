<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{

    public function adminDashboard() {
        return view('admin.dashboard.index');
    }

    public function inspectorDashboard() {
        return view('inspector.dashboard.index');
    }

    public function clienteDashboard() {
        return view('cliente.dashboard.index');
    }

    public function register(Request $request)
    {

        $rol_cliente = Role::where('nombre', 'CLIENTE')->first();

        if(!$rol_cliente) {
            $rol_cliente = new Role();
            $rol_cliente->nombre = 'CLIENTE';
            $rol_cliente->save();
        }

        $validatedData = $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'documento' => 'required|unique:users',
            'telefono' => 'required',
            'disponibilidad' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $validatedData['disponibilidad'] = 1;
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['rol_id'] = $rol_cliente->id;

        User::create($validatedData);

        Auth::login($validatedData);

        return redirect()->route('login')->with('success', 'El usuario se creó con éxito');
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'El campo email es obligatorio.',
            'password.required' => 'El campo contraseña es obligatorio.',
        ]);

        $credentials = $request->only('email', 'password');

        $remember = ($request->has('remember') ? true : false);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role->nombre == 'ADMINISTRADOR') {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('inspector.dashboard'));
            }

        } else {
            return redirect()->route('login')->withErrors([
                'error' => 'Las credenciales proporcionadas son incorrectas.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
