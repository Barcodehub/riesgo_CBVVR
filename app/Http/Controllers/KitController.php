<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TypeKit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KitController extends Controller
{

    public function index() {
        $kits = TypeKit::all();

        return view('admin.kits.index', ['kits' => $kits]);
    }


    public function store(Request $request) {
        $validatedData = $request->validate([
            'descripcion' => [
                'required',
                'unique:type_kits,descripcion'
            ],
        ], [
            'descripcion.unique' => 'El tipo de botiquin ya existe.',
        ]);

        $validatedData['descripcion'] = strtoupper($validatedData['descripcion']);

        TypeKit::create($validatedData);

        return redirect()->route('kits.index')->with('success', 'El tipo de botiquin se creó con éxito');
    }


    public function update(Request $request, $id) {
        $botiquin = TypeKit::findOrFail($id);

        $request->validate([
            'descripcion' => [
                'required',
                Rule::unique('type_kits', 'descripcion')->ignore($botiquin->id)
            ],
        ], [
            'descripcion.unique' => 'El tipo de botiquin ya existe.',
        ]);

        $descripcion = strtoupper($request->input('descripcion'));

        $botiquin->descripcion = $descripcion;

        $botiquin->save();

        return redirect()->route('kits.index')->with('success', 'El tipo de botiquin se actualizó con éxito');
    } 

    public function destroy($id) {
        $rol = TypeKit::find($id);

        $rol->delete();
        
        return redirect()->route('kits.index')->with('success', 'El tipo de botiquin se eliminó con éxito');

    }
}
