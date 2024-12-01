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

        $inspectors = User::whereHas('role', function ($query) {
            $query->where('nombre', 'INSPECTOR');
        })->get();

        return view('admin.companies.index', ['companies' => $companies, 'inspectors' => $inspectors]);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'razon_social' => 'required',
            'nombre_establecimiento' => 'required',
            'representante_legal' => 'required',
            'horario_funcionamiento' => 'required',
            'cedula_representante' => 'required',
            'nit' => 'required',
            'direccion' => 'required',
            'barrio' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'actividad_comercial' => 'required',
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

                $storage_path = 'documentos/empresa-' . $companyCreated->id;

                $file_name = $input_name . '_' . now()->format('Ymd_His') . '.' . $file->extension();

                Storage::disk('public')->put($storage_path . '/' . $file_name, file_get_contents($request->file($input_name)));

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
            'nombre_establecimiento' => 'required',
            'representante_legal' => 'required',
            'horario_funcionamiento' => 'required',
            'cedula_representante' => 'required',
            'nit' => 'required',
            'direccion' => 'required',
            'barrio' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'actividad_comercial' => 'required',
        ]);

        $validatedData['cliente_id'] = Auth::user()->id;

        $companyCreated = Company::create($validatedData);

        /*
        $inspection = new Inspection();

        $inspection->fecha_solicitud = Carbon::now()->toDateString();
        $inspection->establecimiento_id = $companyCreated->id;
        $inspection->estado = 'SOLICITADA';

        $inspection->save();
        */

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

                $storage_path = 'documentos/empresa-' . $companyCreated->id;

                $file_name = $input_name . '_' . now()->format('Ymd_His') . '.' . $file->extension();

                Storage::disk('public')->put($storage_path . '/' . $file_name, file_get_contents($request->file($input_name)));

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
            'nombre_establecimiento' => 'required',
            'representante_legal' => 'required',
            'horario_funcionamiento' => 'required',
            'cedula_representante' => 'required',
            'nit' => 'required',
            'direccion' => 'required',
            'barrio' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'actividad_comercial' => 'required',
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

                $files = Storage::disk('public')->files('documentos/empresa-' . $company->id);
                foreach ($files as $file) {
                    if (preg_match("/^documentos\/empresa-" . $company->id . "\/" . preg_quote($input_name, '/') . "\..+$/", $file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
                $file = $request->file($input_name);

                $storage_path = 'documentos/empresa-' . $company->id;

                $file_name = $input_name . '_' . now()->format('Ymd_His') . '.' . $file->extension();

                Storage::disk('public')->put($storage_path . '/' . $file_name, file_get_contents($request->file($input_name)));

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
            'nombre_establecimiento' => 'required',
            'representante_legal' => 'required',
            'horario_funcionamiento' => 'required',
            'cedula_representante' => 'required',
            'nit' => 'required',
            'direccion' => 'required',
            'barrio' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'actividad_comercial' => 'required',
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

                $files = Storage::disk('public')->files('documentos/empresa-' . $company->id);
                foreach ($files as $file) {
                    if (preg_match("/^documentos\/empresa-" . $company->id . "\/" . preg_quote($input_name, '/') . "\..+$/", $file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
                $file = $request->file($input_name);

                $storage_path = 'documentos/empresa-' . $company->id;

                $file_name = $input_name . '_' . now()->format('Ymd_His') . '.' . $file->extension();

                Storage::disk('public')->put($storage_path . '/' . $file_name, file_get_contents($request->file($input_name)));

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
        $user = Auth::user();

        $companies = Company::where('cliente_id', $user->id)->get();

        return view('cliente.detalle-company', [
            'companies' => $companies,
            'clientName' => $user->nombre
        ]);
    }
}
