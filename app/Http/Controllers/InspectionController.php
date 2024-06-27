<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\Inspection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InspectionController extends Controller
{
    public function index() {
        $inspections = Inspection::all();

        $opcionesEstado = [
            'SOLICITADA' => 'SOLICITADA',
            'ASIGNADA' => 'ASIGNADA',
            'COTIZADA' => 'COTIZADA',
            'FINALIZADA' => 'FINALIZADA',
        ];

        return view('admin.inspections.index', ['inspections' => $inspections, 'opcionesEstado' => $opcionesEstado]);
    }

    public function store(Request $request) {

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


    public function update(Request $request, $id) {
        $inspection = Inspection::find($id);


        $request->validate([
            'estado' => 'required'
        ]);

        $inspection->estado = $request->estado;

        $inspection->save();

        return redirect()->route('inspections.index')->with('success', 'La inspección se actualizó con éxito');
    } 

    public function destroy($id) {
        $inspection = Inspection::find($id);

        $inspection->delete();
        
        return redirect()->route('inspections.index')->with('success', 'La inspección se eliminó con éxito');

    }

    public function inspeccionesAsignadas() {
        $inspections = Inspection::where('estado', 'SOLICITADA')->get();

        return view('inspector.inspections.index', ['inspections' => $inspections]);
    }

    public function inspeccionByEmpresa() {

        $empresaId = Auth::user()->companies->first()->id;

        
        $inspection = Inspection::where('establecimiento_id', $empresaId)->first();

        $concept = Concept::where('inspeccion_id', $inspection->id)->first();

        

        return view('cliente.detalle-inspeccion', ['inspection' => $inspection, 'concept' => $concept]);
    }
}
