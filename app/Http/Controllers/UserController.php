<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        return view('admin.users.index', ['users' => $users, 'roles' => $roles]);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'] ?? null,
            'documento' => $validated['documento'],
            'telefono' => $validated['telefono'],
            'disponibilidad' => $request->disponibilidad == 'on' ? 1 : 0,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol_id' => $validated['rol_id'],
        ]);

        return redirect()->route('users.index')->with('success', 'El usuario se creó con éxito');
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->documento = $request->documento;
        $user->telefono = $request->telefono;
        $user->disponibilidad = $request->disponibilidad == 'on' ? 1 : 0;
        $user->email = $request->email;

        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }

        $user->rol_id = $request->rol_id;

        $user->save();

        return redirect()->route('users.index')->with('success', 'El usuario se actualizó con éxito');
    }

    public function changeState($id)
    {
        $user = User::find($id);

        $user->disponibilidad = !$user->disponibilidad;

        $user->save();

        return redirect()->route('users.index')->with('success', 'El usuario se actualizó con éxito');
    }
}
