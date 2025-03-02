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
        return Huella::all();
    }

   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $huella = Huella::find($id);
        return isset($huella) ? $huella : [];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $huella = Huella::find($id);
        if ($huella) {
            $huella->delete();
            return $huella;
        }
        return [];
    }

    public function crearHuella($idUser)
{
    $host = "127.0.0.1";  // Accede al host desde Docker
    $port = 1234;
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
