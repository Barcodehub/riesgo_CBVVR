<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Huella;
use App\Models\User;

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


    public function crearHuella($idUser)
{
    // en blade le pasamos un numero(idUser) con el id del usuario se crea su propia huella

    $host = config('services.biometric.host');
    $port = config('services.biometric.port');
    $message = $idUser . "\n";

    // Crear el socket
    $socket = socket_create(AF_INET, SOCK_STREAM, 0);
    if ($socket === false) {
        return response()->json(['error' => 'No se pudo crear el socket'], 500);
    }

    // Conectar al servidor
    $result = socket_connect($socket, $host, $port);
    if ($result === false) {
        return response()->json(['error' => 'No se pudo conectar con el servidor'], 500);
    }

    // Enviar el mensaje
    $result = socket_write($socket, $message, strlen($message));
    if ($result === false) {
        return response()->json(['error' => 'No se pudo enviar datos al servidor'], 500);
    }

    // Leer la respuesta
    $result = socket_read($socket, 1024);
    if ($result === false) {
        return response()->json(['error' => 'No se pudo leer la respuesta del servidor'], 500);
    }

    // Cerrar el socket
    socket_close($socket);

    // Procesar la respuesta
    $huella = trim($result);  // Elimina espacios y saltos de línea

    // Buscar al User
    $user = User::find($idUser);
    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    // Crear o actualizar la huella del user
    $huellaModel = Huella::updateOrCreate(
        ['id_user' => $idUser],  // Condición para buscar
        ['huella' => $huella]            // Datos para crear/actualizar
    );

    // Actualizar el campo "acceso_huella" del user
    $user->acceso_huella = true;  // O 'Si', dependiendo del tipo de campo
    $user->save();

    // Devolver la respuesta
    return response()->json([
        'respuesta' => $huella,
        'mensaje' => 'Huella creada/actualizada correctamente',
        'huella' => $huellaModel,
        'user' => $user
    ]);
}


    public function destroyForIdUser($idUser)
    {
        $huella = Huella::where('id_user', $idUser)->first();
        
        if ($huella) {
            // Actualizar el estado del usuario
            $user = $huella->user;
            if ($user) {
                $user->acceso_huella = false;
                $user->save();
            }
            
            $huella->delete();
            return redirect()->route('huella.index')
                ->with('success', 'Huella eliminada correctamente');
        }
        
        return redirect()->route('huella.index')
            ->with('error', 'No se encontró huella para este usuario');
    }

    
    public function destroyForIdEmploye($idUser)
    {
        $huella = Huella::where('huella.id_user', '=', $idUser)->first();
        if ($huella) {
            $huella->delete();
            return $huella;
        }
        return [];
    }
}
