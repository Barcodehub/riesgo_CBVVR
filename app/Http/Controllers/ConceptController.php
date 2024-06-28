<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\Inspection;
use App\Models\TipoBotiquinConcepto;
use App\Models\TipoExtintorConcepto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConceptController extends Controller
{

    public function index() {
        $concepts = Concept::all();

        return view('admin.concepts.index', ['concepts' => $concepts]);
    }


    public function store(Request $request, $inspection_id) {

        $inspection = Inspection::findOrFail($inspection_id);

        $inspection->estado = 'REVISADA';

        $validatedData = $request->validate([
            'carga_ocupacional_fija' => 'required',
            'carga_ocupacional_flotante' => 'required',
            'anios_contruccion' => 'required',
            'nrs10' => 'required|boolean',
            'sgsst' => 'required|boolean',
            'sist_automatico_incendios' => 'required|boolean',
            'observaciones_sist_incendios' => 'required',
            'descripcion_concepto' => 'required',
            'hidrante' => 'required|boolean',
            'tipo_hidrante' => 'required',
            'capacitacion' => 'required|boolean',
            'tipo_camilla' => 'required',
            'inmovilizador_vertical' => 'required',
            'capacitacion_primeros_auxilios' => 'required|boolean',
            'tipo_extintor' => 'nullable|array',
            'tipo_extintor.*' => 'integer|exists:type_extinguishers,id',
            'tipo_botiquin' => 'nullable|array',
            'tipo_botiquin.*' => 'integer|exists:type_kits,id',
            'empresa_recarga' => 'nullable|array',
            'empresa_recarga.*' => 'string|nullable',
            'fecha_vencimiento' => 'nullable|array',
            'fecha_vencimiento.*' => 'date|nullable',
        ]);

        $validatedData['inspeccion_id'] = $inspection_id;

        $validatedData['fecha_concepto'] = Carbon::now()->toDateString();


        $conceptCreated = Concept::create($validatedData);

        $inspection->save();


        // Procesar los tipos de extintor y las empresas de recarga
        if (!empty($validatedData['tipo_extintor'])) {
            foreach ($validatedData['tipo_extintor'] as $tipoExtintorId) {
                $empresaRecarga = $validatedData['empresa_recarga'][$tipoExtintorId] ?? null;
                $fechaVencimiento = $validatedData['fecha_vencimiento'][$tipoExtintorId] ?? null;

                TipoExtintorConcepto::create([
                    'concepto_id' => $conceptCreated->id,
                    'tipo_extintor_id' => $tipoExtintorId,
                    'empresa_recarga' => $empresaRecarga,
                    'fecha_vencimiento' => $fechaVencimiento,
                ]);
            }
        }

        if (!empty($validatedData['tipo_botiquin'])) {
            foreach ($validatedData['tipo_botiquin'] as $tipoBotiquinId) {
                $empresaRecarga = $validatedData['empresa_recarga'][$tipoBotiquinId] ?? null;
                $fechaVencimiento = $validatedData['fecha_vencimiento'][$tipoBotiquinId] ?? null;

                TipoBotiquinConcepto::create([
                    'concepto_id' => $conceptCreated->id,
                    'tipo_botiquin_id' => $tipoBotiquinId,
                    'empresa_recarga' => $empresaRecarga,
                    'fecha_vencimiento' => $fechaVencimiento,
                ]);
            }
        }


        return redirect()->route('inspector.inspeccionesAsignadas')->with('success', 'El concepto se creó con éxito');
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
