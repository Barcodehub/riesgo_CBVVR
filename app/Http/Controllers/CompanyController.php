<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index() {
        $companies = Company::all();

        $opcionesPisos = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
        ];

        $inspectors = User::whereHas('role', function($query) {
            $query->where('nombre', 'INSPECTOR');
        })->get();

        return view('admin.companies.index', ['companies' => $companies, 'opcionesPisos' => $opcionesPisos, 'inspectors' => $inspectors]);
    }

    public function store(Request $request) {

        //TODO: Primero se almacenan los documentos cargados.

        $validatedData = $request->validate([
            'razon_social' => 'required',
            'representante_legal' => 'required',
            'horario_funcionamiento' => 'required',
            'cedula_representante' => 'required',
            'nit' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'actividad_comercial' => 'required',
            'ancho_dimensiones' => 'required',
            'largo_dimensiones' => 'required',
            'num_pisos' => 'required'
        ]);


        Company::create($validatedData);

        //CREAR LOS DOCUMENTOS QUE LLEGAN EN EL REQUEST ASOCIANDO CADA EMPRESA

        return redirect()->route('companies.index')->with('success', 'La empresa se creó con éxito');
    }


    public function update(Request $request, $id) {
        $company = Company::find($id);


        $validatedData = $request->validate([
            'razon_social' => 'required',
            'representante_legal' => 'required',
            'horario_funcionamiento' => 'required',
            'cedula_representante' => 'required',
            'nit' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'actividad_comercial' => 'required',
            'ancho_dimensiones' => 'required',
            'largo_dimensiones' => 'required',
            'num_pisos' => 'required'
        ]);

        $company->update($validatedData);

        return redirect()->route('companies.index')->with('success', 'La empresa se actualizó con éxito');
    } 

    public function destroy($id) {
        $company = Company::find($id);

        $company->delete();
        
        return redirect()->route('companies.index')->with('success', 'La empresa se eliminó con éxito');

    }

    public function datosEmpresa() {

        $user = auth()->user();

        $company = Company::where('cliente_id', $user->id)->first();

        return view('cliente.detalle-company', ['company' => $company]);
    }

    
}
