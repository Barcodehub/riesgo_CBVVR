<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Huella;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class HuellaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $huellas = Huella::with('user')->get();
        $users = User::whereDoesntHave('huella')->get(); // Solo usuarios sin huella registrada
        return view('admin.huella.index', compact('huellas', 'users'));
    }

    public function show($id)
    {
        $huella = Huella::with('user')->find($id);
        return view('admin.huella.show', compact('huella'));
    }

    public function user()
    {
    $user = Auth::user();
    $huellas = Huella::where('id_user', $user->id)  // ← aquí corregido
                     ->with('user')
                     ->get();
    return view('cliente.huella.index', compact('huellas'));
    }

public function destroy($id)
{
    $huella = Huella::find($id);

    if ($huella) {
        // Actualizar el estado del usuario
        $user = $huella->user;
        if ($user) {
            $user->acceso_huella = null;
            $user->save();
        }

        $huella->delete();
        
        return redirect()->route('huella.index')  // Corregido aquí
            ->with('success', 'Huella eliminada correctamente');
    }

}



// En tu controlador (app/Http/Controllers/BiometricController.php)
public function crearHuella($idUser)
{
    try {

       

        $user = User::findOrFail($idUser);
        
        $host = config('services.biometric.host', '127.0.0.1');
        $port = config('services.biometric.port', 8080);
        $timeout = 300; // 2 minutos de timeout

         if (!$this->isBiometricServiceRunning($host, $port)) {
    throw new \Exception("El servicio biométrico no está disponible");
}

        // Crear socket con manejo mejorado de errores
        $socket = $this->createBiometricSocket($host, $port, $timeout);

        // Enviar ID de usuario
        $this->sendSocketData($socket, $idUser . "\n");

        // Leer respuesta completa
        $templateData = '';
        $endMarker = false;
        $startTime = time();
        
        while (!$endMarker && (time() - $startTime) < $timeout) {
            // Leer datos del socket
            $buffer = socket_read($socket, 2048, PHP_NORMAL_READ);
            
            if ($buffer === false || $buffer === '') {
                $error = socket_last_error($socket);
                if ($error !== SOCKET_EWOULDBLOCK) {
                    throw new \Exception("Error de lectura: " . socket_strerror($error));
                }
                usleep(200000); // Esperar 200ms si no hay datos
                continue;
            }

            // Procesar línea recibida
            $line = trim($buffer);
            
            if ($line === 'READY') {
                continue;
            }

            // Manejar errores del servidor
            if (strpos($line, 'ERROR:') === 0) {
                throw new \Exception(substr($line, 6));
            }
            
            // Ignorar mensajes de progreso
            if (strpos($line, 'PROGRESS:') === 0) {
                $progress = (int) substr($line, 9);
                Log::debug("Progreso de huella digital: " . $progress . " muestras restantes");
                continue;
            }
            
            // Verificar marcador de fin
            if ($line === 'END') {
                $endMarker = true;
                break;
            }
            
            // Acumular datos del template (validación básica de Base64)
            if (preg_match('/^[a-zA-Z0-9\/+]+={0,2}$/', $line)) {
                $templateData .= $line;
            }
        }

        // Validaciones finales
        if (!$endMarker) {
            throw new \Exception("No se recibió confirmación de finalización del servidor");
        }

        if (empty($templateData)) {
            throw new \Exception("No se recibieron datos de huella válidos");
        }

        if (strlen($templateData) < 2000) {
            throw new \Exception("El template de huella es demasiado corto");
        }

        // Guardar en base de datos
        return $this->saveFingerprintData($user, $templateData);

    } catch (\Exception $e) {
        Log::error("Error en registro de huella", [
            'user_id' => $idUser,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error al registrar huella: ' . $e->getMessage(),
            'error' => $e->getMessage(),
            'debug' => env('APP_DEBUG') ? [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ] : null
        ], 500);
    } finally {
        if (isset($socket) && (is_resource($socket) || (class_exists('Socket') && $socket instanceof \Socket))) {
            @socket_close($socket);
        }
    }
}

private function createBiometricSocket($host, $port, $timeout)
{
    $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        throw new \Exception("No se pudo crear socket: " . socket_strerror(socket_last_error()));
    }

    // Configurar opciones del socket
    socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
    socket_set_option($socket, SOL_SOCKET, SO_KEEPALIVE, 1);
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => $timeout, 'usec' => 0]);
    socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => $timeout, 'usec' => 0]);

    // Conectar con timeout
    if (!@socket_connect($socket, $host, $port)) {
        $error = socket_last_error();
        socket_close($socket);
        throw new \Exception("Conexión fallida: " . socket_strerror($error));
    }

    return $socket;
}

private function sendSocketData($socket, $data)
{
    $sent = 0;
    $length = strlen($data);
    
    while ($sent < $length) {
        $bytes = socket_write($socket, substr($data, $sent));
        if ($bytes === false) {
            throw new \Exception("Error enviando datos: " . socket_strerror(socket_last_error($socket)));
        }
        $sent += $bytes;
    }
}

private function saveFingerprintData($user, $fingerprintData)
{
    DB::beginTransaction();
    try {
        // Validación adicional del template
        $decoded = base64_decode($fingerprintData);
        if ($decoded === false) {
            throw new \Exception("El template de huella no es un Base64 válido");
        }
Log::debug('Guardando template', [
    'raw' => substr($fingerprintData, 0, 100),
    'length' => strlen($fingerprintData),
    'is_base64' => base64_decode($fingerprintData) !== false ? 'Sí' : 'No'
]);
        // Almacenar el template tal como viene del servidor biométrico
        $huella = Huella::updateOrCreate(
            ['id_user' => $user->id],
            ['huella' => $fingerprintData] // Almacenar el Base64 directamente
        );

        Log::debug('Guardando huella', [
            'length' => strlen($fingerprintData),
            'data' => substr($fingerprintData, 0, 100) . '...' // Mostrar solo inicio
        ]);
        Log::debug('Guardando template', [
            'user_id' => $user->id,
            'encoded_length' => strlen($fingerprintData),
            'decoded_length' => strlen($decoded),
        ]);

        $user->acceso_huella = true;
        $user->save();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Huella registrada correctamente',
            'data' => [
                'user_id' => $user->id,
                'huella_id' => $huella->id
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}



private function isBiometricServiceRunning($host, $port)
{
    $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        return false;
    }
    
    $connected = @socket_connect($socket, $host, $port);
    @socket_close($socket);
    
    return $connected;
}

private function readSocketResponseWithTimeout($socket, $timeout)
{
    $response = '';
    $startTime = time();
    
    while (true) {
        if ((time() - $startTime) >= $timeout) {
            throw new \Exception("Tiempo de espera agotado al leer respuesta");
        }

        $buffer = @socket_read($socket, 2048);
        if ($buffer === false) {
            throw new \Exception("Error leyendo del socket: " . socket_strerror(socket_last_error($socket)));
        }

        if ($buffer !== '') {
            $response .= $buffer;
            if (strpos($buffer, "\n") !== false || strpos($buffer, "ERROR:") === 0) {
                break;
            }
        }
        
        usleep(5000000); // Esperar 200ms
    }

    return trim($response);

}





private function readSocketResponse($socket, $timeout)
{
    $response = '';
    $startTime = time();
    
    while (true) {
        // Verificar timeout
        if ((time() - $startTime) >= $timeout) {
            throw new \Exception("Tiempo de espera agotado al leer respuesta");
        }

        $buffer = @socket_read($socket, 1024, PHP_NORMAL_READ);
        if ($buffer === false) {
            throw new \Exception("Error al leer respuesta del servidor biométrico");
        }

        if ($buffer !== '') {
            $response .= $buffer;
            if (strpos($buffer, "\n") !== false) {
                break;
            }
        }
        
        usleep(100000); // Esperar 100ms para evitar consumo excesivo de CPU
    }

    return trim($response);
}

}