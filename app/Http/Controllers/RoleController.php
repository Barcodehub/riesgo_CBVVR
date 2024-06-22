<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function index() {
        $roles = Role::all();

        return view('admin.roles.index', ['roles' => $roles]);
    }


    public function store(Request $request) {
        $validatedData = $request->validate([
            'nombre' => [
                'required',
                'unique:roles,nombre',
                function ($attribute, $value, $fail) {
                    $allowedRoles = ['ADMINISTRADOR', 'INSPECTOR', 'CLIENTE'];
                    if (!in_array(strtoupper($value), $allowedRoles)) {
                        $fail('El nombre del rol debe ser ADMINISTRADOR, INSPECTOR o CLIENTE.');
                    }
                }
            ],
        ], [
            'nombre.unique' => 'El nombre del rol ya existe.',
        ]);

        $validatedData['nombre'] = strtoupper($validatedData['nombre']);

        Role::create($validatedData);

        return redirect()->route('roles.index')->with('success', 'El rol se creó con éxito');
    }


    public function update(Request $request, $id) {
        $role = Role::find($id);

        $request->validate([
            'nombre' => [
                'required',
                Role::unique('roles', 'nombre')->ignore($role->id),
                function ($attribute, $value, $fail) {
                    $allowedRoles = ['ADMINISTRADOR', 'INSPECTOR', 'CLIENTE'];
                    if (!in_array(strtoupper($value), $allowedRoles)) {
                        $fail('El nombre del rol debe ser ADMINISTRADOR, INSPECTOR o CLIENTE.');
                    }
                }
            ],
        ]);

        $nombre = strtoupper($request->input('nombre'));

        $role->nombre = $nombre;

        $role->save();

        return redirect()->route('roles.index')->with('success', 'El rol se actualizó con éxito');
    } 

    public function destroy($id) {
        $rol = Role::find($id);

        $rol->delete();
        
        return redirect()->route('roles.index')->with('success', 'El rol se eliminó con éxito');

    }
}
