<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TypeExtinguisher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExtintorController extends Controller
{

    public function index() {
        $extintores = TypeExtinguisher::all();

        return view('admin.extintores.index', ['extintores' => $extintores]);
    }


    public function store(Request $request) {
        $validatedData = $request->validate([
            'descripcion' => [
                'required',
                'unique:type_extinguishers,descripcion'
            ],
        ], [
            'descripcion.unique' => 'El tipo de extintor ya existe.',
        ]);

        $validatedData['descripcion'] = strtoupper($validatedData['descripcion']);

        TypeExtinguisher::create($validatedData);

        return redirect()->route('extintores.index')->with('success', 'El tipo de extintor se creó con éxito');
    }


    public function update(Request $request, $id) {
        $extintor = TypeExtinguisher::findOrFail($id);

        $request->validate([
            'descripcion' => [
                'required',
                Rule::unique('type_extinguishers', 'descripcion')->ignore($extintor->id)
            ],
        ], [
            'descripcion.unique' => 'El tipo de extintor ya existe.',
        ]);

        $descripcion = strtoupper($request->input('descripcion'));

        $extintor->descripcion = $descripcion;

        $extintor->save();

        return redirect()->route('extintores.index')->with('success', 'El tipo de extintor se actualizó con éxito');
    } 

    public function destroy($id) {
        $rol = TypeExtinguisher::find($id);

        $rol->delete();
        
        return redirect()->route('extintores.index')->with('success', 'El tipo de extintor se eliminó con éxito');

    }
}
