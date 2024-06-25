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

    public function destroy($id) {
        $rol = Role::find($id);

        $rol->delete();
        
        return redirect()->route('roles.index')->with('success', 'El rol se eliminó con éxito');

    }
}
