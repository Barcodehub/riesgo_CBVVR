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
            'inspector_id' => 'required',
            'company_id' => 'required'
        ]);

        $inspection = new Inspection();

        $inspection->fecha_solicitud = Carbon::now()->toDateString();
        $inspection->establecimiento_id = $request->company_id;
        $inspection->inspector_id = $request->inspector_id;
        $inspection->estado = 'SOLICITADA';

        $inspection->save();

        return redirect()->route('companies.index')->with('success', 'La inspección se creó con éxito');
    }


    public function update(Request $request, $id)
    {
        $inspection = Inspection::find($id);


        $request->validate([
            'valor' => 'required|numeric|gt:1000',
        ]);

        $inspection->valor = $request->valor;
        $inspection->estado = 'COTIZADA';


        $inspection->save();

        return redirect()->route('inspections.index')->with('success', 'La inspección se actualizó con éxito');
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


    public function finalizar($inspection_id)
    {
        $inspection = Inspection::find($inspection_id);

        $inspection->estado = 'FINALIZADA';

        $inspection->save();

        return redirect()->route('inspector.inspeccionesRealizadas')->with('success', 'La inspección se finalizó con éxito');
    }

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
}
