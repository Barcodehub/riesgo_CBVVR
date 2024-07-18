<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Document;
use App\Models\Inspection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
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

        $inspectors = User::whereHas('role', function ($query) {
            $query->where('nombre', 'INSPECTOR');
        })->get();



        return view('admin.companies.index', ['companies' => $companies, 'opcionesPisos' => $opcionesPisos, 'inspectors' => $inspectors]);
    }

    public function store(Request $request)
    {

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

        $companyCreated = Company::create($validatedData);


        $request->validate([
            'rut' => 'required|mimes:pdf',
            'camara_comercio' => 'required|mimes:pdf',
            'cedula' => 'required|mimes:pdf',
            'fachada' => 'required|mimes:jpeg,jpg,png'
        ]);

        $documents = [
            'RUT' => 'rut',
            'CAMARA_COMERCIO' => 'camara_comercio',
            'CEDULA_REPRESENTANTE' => 'cedula',
            'FOTO_FACHADA' => 'fachada'
        ];

        foreach ($documents as $tipo_documento => $input_name) {
            if ($request->hasFile($input_name)) {

                $file = $request->file($input_name);
                
                $file_name = $input_name . '-' . $companyCreated->id . "." . $file->extension();
                
                Storage::disk('public')->put('documentos/' . $file_name, file_get_contents($request->file($input_name)));

                Document::create([
                    'tipo_documento' => $tipo_documento,
                    'archivo' => $file_name,
                    'empresa_id' => $companyCreated->id
                ]);
            }
        }


        return redirect()->route('companies.index')->with('success', 'La empresa se creó con éxito');
    }

    public function storeCliente(Request $request)
    {

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

        $validatedData['cliente_id'] = Auth::user()->id;

        $companyCreated = Company::create($validatedData);


        $inspection = new Inspection();

        $inspection->fecha_solicitud = Carbon::now()->toDateString();
        $inspection->establecimiento_id = $companyCreated->id;
        $inspection->estado = 'SOLICITADA';

        $inspection->save();


        $request->validate([
            'rut' => 'required|mimes:pdf',
            'camara_comercio' => 'required|mimes:pdf',
            'cedula' => 'required|mimes:pdf',
            'fachada' => 'required|mimes:jpeg,jpg,png'
        ]);

        $documents = [
            'RUT' => 'rut',
            'CAMARA_COMERCIO' => 'camara_comercio',
            'CEDULA_REPRESENTANTE' => 'cedula',
            'FOTO_FACHADA' => 'fachada'
        ];

        foreach ($documents as $tipo_documento => $input_name) {
            if ($request->hasFile($input_name)) {

                $file = $request->file($input_name);
                
                $file_name = $input_name . '-' . $companyCreated->id . "." . $file->extension();
                
                Storage::disk('public')->put('documentos/' . $file_name, file_get_contents($request->file($input_name)));

                Document::create([
                    'tipo_documento' => $tipo_documento,
                    'archivo' => $file_name,
                    'empresa_id' => $companyCreated->id
                ]);
            }
        }


        return redirect()->route('cliente.datosEmpresa')->with('success', 'La empresa se creó con éxito');
    }


    public function updateCliente(Request $request, $id)
    {
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

        $documents = [
            'RUT' => 'rut',
            'CAMARA_COMERCIO' => 'camara_comercio',
            'CEDULA_REPRESENTANTE' => 'cedula',
            'FOTO_FACHADA' => 'fachada',
        ];

        foreach ($documents as $tipo_documento => $input_name) {
            if ($request->hasFile($input_name)) {

                $base_file_name = $input_name . '-' . $company->id;

                $files = Storage::disk('public')->files('documentos');
                foreach ($files as $file) {
                    if (preg_match("/^documentos\/" . preg_quote($base_file_name, '/') . "\..+$/", $file)) {
                        Storage::disk('public')->delete($file);
                    }
                }

                $file = $request->file($input_name);

                $file_name = $base_file_name . "." . $file->extension();

                Storage::disk('public')->put('documentos/' . $file_name, file_get_contents($request->file($input_name)));

                Document::updateOrCreate(
                    [
                        'empresa_id' => $company->id,
                        'tipo_documento' => $tipo_documento
                    ],
                    [
                        'archivo' => $file_name
                    ]
                );
            }
        }

        return redirect()->route('cliente.datosEmpresa')->with('success', 'La empresa se actualizó con éxito');
    }

    public function update(Request $request, $id)
    {
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

        $documents = [
            'RUT' => 'rut',
            'CAMARA_COMERCIO' => 'camara_comercio',
            'CEDULA_REPRESENTANTE' => 'cedula',
            'FOTO_FACHADA' => 'fachada',
        ];

        foreach ($documents as $tipo_documento => $input_name) {
            if ($request->hasFile($input_name)) {

                $base_file_name = $input_name . '-' . $company->id;

                $files = Storage::disk('public')->files('documentos');
                foreach ($files as $file) {
                    if (preg_match("/^documentos\/" . preg_quote($base_file_name, '/') . "\..+$/", $file)) {
                        Storage::disk('public')->delete($file);
                    }
                }

                $file = $request->file($input_name);

                $file_name = $base_file_name . "." . $file->extension();

                Storage::disk('public')->put('documentos/' . $file_name, file_get_contents($request->file($input_name)));

                Document::updateOrCreate(
                    [
                        'empresa_id' => $company->id,
                        'tipo_documento' => $tipo_documento
                    ],
                    [
                        'archivo' => $file_name
                    ]
                );
            }
        }

        return redirect()->route('companies.index')->with('success', 'La empresa se actualizó con éxito');
    }

    public function destroy($id)
    {
        $company = Company::find($id);

        $company->delete();

        return redirect()->route('companies.index')->with('success', 'La empresa se eliminó con éxito');
    }

    public function datosEmpresa()
    {

        $user = auth()->user();

        $opcionesPisos = [
            '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', 
            '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10',
        ];

        $company = Company::where('cliente_id', $user->id)->first();

        return view('cliente.detalle-company', ['company' => $company, 'opcionesPisos' => $opcionesPisos]);
    }

}
