<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConceptController extends Controller
{

    public function index() {
        $concepts = Concept::all();

        return view('admin.concepts.index', ['concepts' => $concepts]);
    }


    public function store(Request $request) {
        $validatedData = $request->validate([
            'carga_ocupacional_fija' => 'required',
            'carga_ocupacional_flotante' => 'required',
            'anio_construccion' => 'required',
            'nrs10' => 'required',

        ]);

        Concept::create($validatedData);

        return redirect()->route('extintores.index')->with('success', 'El tipo de extintor se creó con éxito');
    }


    public function update(Request $request, $id) {
        $extintor = Concept::findOrFail($id);

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
        $rol = Concept::find($id);

        $rol->delete();
        
        return redirect()->route('extintores.index')->with('success', 'El tipo de extintor se eliminó con éxito');

    }
}
