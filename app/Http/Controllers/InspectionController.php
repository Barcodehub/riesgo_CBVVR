<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\Inspection;
use App\Models\TypeExtinguisher;
use App\Models\TypeKit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Archivos;
use Barryvdh\DomPDF\Facade\Pdf;

class InspectionController extends Controller
{
    public function index()
    {
        $inspections = Inspection::all();

        $inspectors = User::whereHas('role', function ($query) {
            $query->where('nombre', 'INSPECTOR');
        })->get();

        return view('admin.inspections.index', ['inspections' => $inspections, 'inspectors' => $inspectors]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'company_id' => 'required'
        ]);

        $inspection = new Inspection();

        $inspection->fecha_solicitud = Carbon::now()->toDateString();
        $inspection->establecimiento_id = $request->company_id;
        $inspection->inspector_id = null;
        $inspection->estado = 'SOLICITADA';

        $inspection->save();

        return redirect()->route('cliente.datosEmpresa')->with('success', 'La inspección se creó con éxito');
    }




    public function update(Request $request, $id)
    {
        $inspection = Inspection::find($id);


        $request->validate([
            'valor_cotizacion' => 'required|numeric|gt:1000',
        ]);

        $inspection->valor = $request->valor_cotizacion;
        $inspection->estado = 'COTIZADA';


        $inspection->save();

        return redirect()->route('inspections.index')->with('success', 'La inspección se cotizó con éxito');
    }

    public function asignarInspector(Request $request, $id)
    {
        $inspection = Inspection::find($id);


        $request->validate([
            'inspector_id' => 'required|numeric',
        ]);

        $inspection->inspector_id = $request->inspector_id;
        $inspection->fecha_asignacion_inspector = Carbon::now()->toDateString();


        $inspection->save();

        return redirect()->route('inspections.index')->with('success', 'La inspección se actualizó con éxito');
    }

    public function destroy($id)
    {
        $inspection = Inspection::find($id);

        $inspection->delete();

        return redirect()->route('inspections.index')->with('success', 'La inspección se eliminó con éxito');
    }

    public function inspeccionesAsignadas()
    {
        $estados = ['SOLICITADA', 'COTIZADA'];
        $inspector_id = Auth::user()->id;

        $inspections = $this->getInspectionsByStateAndInspector($estados, $inspector_id);

        $tipos_extintor = TypeExtinguisher::all();
        $tipos_botiquin = TypeKit::all();


        return view('inspector.inspections.index', ['inspections' => $inspections, 'tipos_extintor' => $tipos_extintor, 'tipos_botiquin' => $tipos_botiquin]);
    }

    public function inspeccionesRealizadas()
    {

        $estados = ['REVISADA', 'FINALIZADA'];
        $inspector_id = Auth::user()->id;

        $inspections = $this->getInspectionsByStateAndInspector($estados, $inspector_id);


        return view('inspector.inspecciones-realizadas.index', ['inspections' => $inspections]);
    }


/*     public function finalizar($inspection_id)
    {
        $inspection = Inspection::find($inspection_id);

        $inspection->estado = 'FINALIZADA';

        $inspection->save();

        return redirect()->route('inspector.inspeccionesRealizadas')->with('success', 'La inspección se finalizó con éxito');
    } */

    public function getInspectionsByStateAndInspector(array $estados, $inspector_id)
    {

        $inspections = Inspection::whereIn('estado', $estados)
            ->where('inspector_id', $inspector_id)
            ->with(['concept' => function ($query) {
                $query->orderBy('fecha_concepto', 'desc');
            }])
            ->get();

        foreach ($inspections as $inspection) {
            $inspection->latest_concepto = $inspection->concept->first();
        }

        return $inspections;
    }

    public function inspeccionByEmpresa()
    {

        $empresaId = Auth::user()->companies->first()->id;


        $inspection = Inspection::where('establecimiento_id', $empresaId)->first();

        $concept = Concept::where('inspeccion_id', $inspection->id)->first();

        $fechaConcepto = null;
        $fechaMasUnAnio = null;

        if ($concept) {
            $fechaConcepto = Carbon::parse($concept->fecha_concepto);
            $fechaMasUnAnio = $fechaConcepto->addYear()->format('Y-m-d');
        }


        return view('cliente.detalle-inspeccion', ['inspection' => $inspection, 'concept' => $concept, 'fechaVencimiento' => $fechaMasUnAnio]);
    }

    public function showInspections()
    {
        $client = Auth::user();
        $companies = $client->companies;
        $inspections = \App\Models\Inspection::whereIn('company_id', $companies->pluck('id'))->get();
        return view('cliente.detalle-inspeccion', compact('companies', 'inspections'));
    }

    public function storeEvidence(Request $request, $inspectionId)
    {
        // Buscar la inspección
        $inspection = Inspection::findOrFail($inspectionId);
    
        // Validar los campos del formulario
        $request->validate([
            'recomendaciones' => 'required|string',
            'evidencias' => 'nullable|array',
            'evidencias.*' => 'mimes:jpg,jpeg,png,pdf|max:10240', // Max 10MB por archivo
        ]);
    
        $empresa = $inspection->company; // Obtener la empresa
        $empresaId = $empresa->id;
        $concepto = $inspection->concept; // Obtener el concepto
    
        // Definir la ruta relativa para la carpeta 'evidencias'
        $directory = "public/documentos/empresa-{$empresaId}/concepto-{$concepto->first()->id}/evidencias";
    
        // Crear las carpetas si no existen
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
    
        // Subir los archivos de evidencia
        if ($request->hasFile('evidencias')) {
            foreach ($request->file('evidencias') as $photo) {
                // Guardar el archivo con un nombre único
                $filename = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                // Guardar el archivo en la carpeta de 'evidencias'
                $path = $photo->storeAs($directory, $filename);
    
                // Registrar el archivo en la base de datos
                Archivos::create([
                    'tipo_archivo' => 'evidencia de concepto',
                    'url' => str_replace('public/', 'storage/', $path),
                    'id_concepto' => $concepto->first()->id,
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Evidencias cargadas exitosamente.');
    }
    


    public function finalizar($id)
{
    // Buscar la inspección
    $inspection = Inspection::findOrFail($id);

    // Marcar como finalizada
    $inspection->estado = 'FINALIZADA';
    $inspection->save();

    // Generar el certificado en PDF
    $pdf = Pdf::loadView('certificado', compact('inspection'));

    // Guardar el certificado en el almacenamiento
    $filename = "certificado-{$inspection->id}.pdf";
    Storage::put("public/certificados/{$filename}", $pdf->output());

    // Asociar el certificado a la inspección (opcional, si tienes una columna en la tabla)
    $inspection->certificado_url = "certificados/{$filename}";
    $inspection->save();

    return redirect()->back()->with('success', 'Inspección finalizada y certificado generado.');
}




public function descargarCertificado($id)
{
    // Buscar la inspección
    $inspection = Inspection::findOrFail($id);

    // Verificar si el certificado existe
    if (!$inspection->certificado_url) {
        return redirect()->back()->with('error', 'El certificado no está disponible.');
    }

    // Obtener la ruta del certificado
    $path = Storage::path("public/{$inspection->certificado_url}");

    // Descargar el archivo
    return response()->download($path);
}


    
}
