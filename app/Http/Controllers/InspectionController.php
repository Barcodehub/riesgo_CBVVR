<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Inspection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function store(Request $request, $companyId) {

        $request->validate([
            'inspector_id' => 'required'
        ]);

        $inspection = new Inspection();

        $inspection->fecha_solicitud = Carbon::now()->toDateString();
        $inspection->establecimiento_id = $companyId;
        $inspection->inspector_id = $request->inspector_id;
        $inspection->estado = 'SOLICITADA';

        $inspection->save();

        return redirect()->route('admin.companies.index')->with('success', 'La inspección se creó con éxito');
    }


    public function update(Request $request, $id) {
        $inspection = Inspection::find($id);


        $request->validate([
            'estado' => 'required'
        ]);

        $inspection->estado = $request->estado;

        $inspection->save();

        return redirect()->route('admin.inspections.index')->with('success', 'La inspección se actualizó con éxito');
    } 

    public function destroy($id) {
        $inspection = Inspection::find($id);

        $inspection->delete();
        
        return redirect()->route('admin.inspections.index')->with('success', 'La inspección se eliminó con éxito');

    }

    public function inspeccionesAsignadas() {
        $inspections = Inspection::where('estado', 'ASIGNADA')->get();

        return view('inspector.inspections.index', ['inspections' => $inspections]);
    }
}
