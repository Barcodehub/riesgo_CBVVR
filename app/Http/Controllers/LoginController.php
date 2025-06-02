<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Huella;
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
    set_time_limit(180); // Increase timeout to 3 minutes
    
    try {
        // Validate and prepare templates
        $templates = Huella::query()
            ->whereNotNull('huella')
            ->where('huella', '<>', '')
            ->get()
            ->mapWithKeys(function ($huella) {
                // Strict validation of template format
                if (!preg_match('/^[a-zA-Z0-9\/+]+={0,2}$/', $huella->huella) || 
                    strlen($huella->huella) < 1000) {
                    Log::error('Invalid fingerprint template', [
                        'user_id' => $huella->id_user,
                        'length' => strlen($huella->huella)
                    ]);
                    return [];
                }
                return [$huella->id_user => $huella->huella];
            })
            ->filter()
            ->toArray();

        if (empty($templates)) {
            throw new \Exception('No valid fingerprint templates found');
        }

        Log::debug('Sending templates to biometric server', ['user_ids' => array_keys($templates)]);
        
        // Connect to biometric server
        $host = config('services.biometric.host', '127.0.0.1');
        $port = config('services.biometric.port', 8080);
        $timeout = 120;

        $socket = $this->createBiometricSocket($host, $port, $timeout);
        
        try {
            // Send login command and templates
            $this->sendSocketData($socket, "login\n");
            $this->sendSocketData($socket, json_encode($templates) . "\n");
            
            // Read response with improved protocol handling
            $userId = $this->readSocketResponse($socket, $timeout);
            
            // Find and authenticate user
            $user = User::with('role')->find($userId);
            if (!$user) {
                throw new \Exception('Fingerprint not registered or user not found');
            }

            if ($user->disponibilidad == 0) {
                throw new \Exception('User account is disabled');
            }

            if (!$user->acceso_huella) {
                throw new \Exception('Fingerprint access not enabled for this user');
            }

            Auth::login($user);
            return $this->redirectByRole($user->role->nombre);

        } finally {
            @socket_close($socket);
        }

    } catch (\Exception $e) {
        Log::error('Fingerprint authentication failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('login')->withErrors([
            'fingerprint_error' => $e->getMessage()
        ]);
    }
}


    private function createBiometricSocket($host, $port, $timeout)
    {
        $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            throw new \Exception("No se pudo crear socket: " . socket_strerror(socket_last_error()));
        }

        // Configurar opciones
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => $timeout, 'usec' => 0]);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => $timeout, 'usec' => 0]);

        // Conectar
        if (!@socket_connect($socket, $host, $port)) {
            $error = socket_last_error();
            socket_close($socket);
            throw new \Exception("Error de conexión: " . socket_strerror($error));
        }

        return $socket;
    }

private function sendSocketData($socket, $data)
{
    // Dividir datos en chunks si son muy grandes
    $chunkSize = 1024;
    $sent = 0;
    $length = strlen($data);
    
    while ($sent < $length) {
        $chunk = substr($data, $sent, $chunkSize);
        $bytes = socket_write($socket, $chunk);
        
        if ($bytes === false) {
            throw new \Exception("Error enviando datos: " . socket_strerror(socket_last_error($socket)));
        }
        
        $sent += $bytes;
        
        // Pequeña pausa entre chunks
        usleep(10000);
    }
}

/* private function findUserIdByTemplate($receivedTemplate)
{
    try {
        // Decodificar el template recibido (Base64)
        $receivedDecoded = base64_decode($receivedTemplate);
        if ($receivedDecoded === false) {
            throw new \Exception("Template recibido no es un Base64 válido");
        }

        // Obtener todas las huellas almacenadas
        $huellas = Huella::with('user')->get();

        foreach ($huellas as $huella) {
            // Saltar si el template guardado es inválido
            if (empty($huella->huella)) continue;

            // Decodificar el template almacenado
            $storedDecoded = base64_decode($huella->huella);
            if ($storedDecoded === false) continue;

            // Loguear tamaños para debugging
            Log::debug('Comparando template', [
                'user_id' => $huella->id_user,
                'tamaño_recibido' => strlen($receivedDecoded),
                'tamaño_almacenado' => strlen($storedDecoded),
            ]);

            // Comparación segura: hash_equals()
            if (\hash_equals($storedDecoded, $receivedDecoded)) {
                return $huella->id_user;
            }
        }

        // Si no encontró coincidencia
        throw new \Exception("Huella no registrada en el sistema");

    } catch (\Exception $e) {
        Log::error('Error en findUserIdByTemplate', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
} */

private function readSocketResponse($socket, $timeout)
{
    $startTime = time();
    $responseBuffer = '';
    $endMarker = false;

    while (!$endMarker && (time() - $startTime) < $timeout) {
        $buffer = socket_read($socket, 2048, PHP_NORMAL_READ);
        
        if ($buffer === false) {
            $error = socket_last_error($socket);
            if ($error !== SOCKET_EWOULDBLOCK) {
                throw new \Exception("Socket read error: " . socket_strerror($error));
            }
            usleep(200000); // Wait 200ms if no data
            continue;
        }

        $line = trim($buffer);
        
        if (empty($line)) continue;
        
        Log::debug('Received from biometric server', ['line' => $line]);

        if ($line === 'READY') {
            continue;
        } elseif (strpos($line, 'ERROR:') === 0) {
            throw new \Exception(substr($line, 6));
        } elseif (is_numeric($line)) {
            return $line; // Return user ID
        } elseif ($line === 'END') {
            $endMarker = true;
            break;
        }
        
        $responseBuffer .= $line . "\n";
    }

    if (!$endMarker) {
        throw new \Exception("Timeout waiting for complete response");
    }

    throw new \Exception("Unexpected server response: " . $responseBuffer);
}

    private function redirectByRole($roleName)
    {
        switch ($roleName) {
            case 'ADMINISTRADOR':
                return redirect()->intended(route('admin.dashboard'));
            case 'INSPECTOR':
                return redirect()->intended(route('inspector.dashboard'));
            case 'CLIENTE':
                return redirect()->intended(route('cliente.dashboard'));
            default:
                throw new \Exception('Rol no válido');
        }
    }


    
}







