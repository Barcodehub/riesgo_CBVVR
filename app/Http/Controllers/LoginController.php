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
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $validatedData['disponibilidad'] = 1;
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['rol_id'] = $rol_cliente->id;

        $userCreated = User::create($validatedData);

        Auth::login($userCreated);

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

            if($user->disponibilidad == 0) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'error' => 'El usuario se encuentra deshabilitado.',
                ]);
            }

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






//login con huella
public function loginFingerPrint(Request $request)
{
    $host = config('services.biometric.host');
    $port = config('services.biometric.port');
    $message = "login"."\n";

    // Crear el socket
    $socket = socket_create(AF_INET, SOCK_STREAM, 0);
    if ($socket === false) {
        return redirect()->route('login')->withErrors([
            'error' => 'No se pudo conectar con el servidor de huellas.',
        ]);
    }

    // Conectar al servidor
    $result = socket_connect($socket, $host, $port);
    if ($result === false) {
        return redirect()->route('login')->withErrors([
            'error' => 'No se pudo conectar con el servidor de huellas.',
        ]);
    }

    // Enviar el mensaje
    $result = socket_write($socket, $message, strlen($message));
    if ($result === false) {
        return redirect()->route('login')->withErrors([
            'error' => 'No se pudo enviar datos al servidor de huellas.',
        ]);
    }

    // Leer la respuesta
    $result = socket_read($socket, 1024);
    if ($result === false) {
        return redirect()->route('login')->withErrors([
            'error' => 'No se pudo leer la respuesta del servidor de huellas.',
        ]);
    }

    // Cerrar el socket
    socket_close($socket);

    // Procesar la respuesta
    $id = trim($result);  // Elimina espacios y saltos de línea

    // Buscar al usuario por ID
    $user = User::find($id);
    if ($user) {
        // Verificar si el usuario está habilitado
        if ($user->disponibilidad == 0) {
            return redirect()->route('login')->withErrors([
                'error' => 'El usuario se encuentra deshabilitado.',
            ]);
        }

        // Iniciar sesión
        Auth::login($user);

        // Redirigir según el rol
        switch ($user->role->nombre) {
            case 'ADMINISTRADOR':
                return redirect()->intended(route('admin.dashboard'));
            case 'INSPECTOR':
                return redirect()->intended(route('inspector.dashboard'));
            case 'CLIENTE':
                return redirect()->intended(route('cliente.dashboard'));
            default:
                return redirect()->route('login')->withErrors([
                    'error' => 'Rol no válido.',
                ]);
        }
    } else {
        return redirect()->route('login')->withErrors([
            'error' => 'Huella no registrada o usuario no encontrado.',
        ]);
    }
}


    
}







