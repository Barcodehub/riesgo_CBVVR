<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TypeExtinguisher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExtintorController extends Controller
{

    public function index()
    {
        $extintores = TypeExtinguisher::all();

        return view('admin.extintores.index', ['extintores' => $extintores]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'contenido' => [
                'required',
                'unique:type_extinguishers,contenido',
            ],
        ], [
            'contenido.unique' => 'El tipo de extintor ya existe.',
            'nombre.required' => 'El campo nombre es obligatorio.',
        ]);

        $validatedData['contenido'] = strtoupper($validatedData['contenido']);

        TypeExtinguisher::create($validatedData);

        return redirect()->route('extintores.index')->with('success', 'El tipo de extintor se creó con éxito');
    }



    public function update(Request $request, $id)
    {
        $extintor = TypeExtinguisher::findOrFail($id);
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'contenido' => [
                'required',
                Rule::unique('type_extinguishers', 'contenido')->ignore($extintor->id),
            ],
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'contenido.unique' => 'El tipo de extintor ya existe.',
        ]);

        $validatedData['contenido'] = strtoupper($validatedData['contenido']);

        $extintor->update($validatedData);

        return redirect()->route('extintores.index')->with('success', 'El tipo de extintor se actualizó con éxito');
    }


    public function destroy($id)
    {
        $rol = TypeExtinguisher::find($id);

        $rol->delete();

        return redirect()->route('extintores.index')->with('success', 'El tipo de extintor se eliminó con éxito');
    }
}
