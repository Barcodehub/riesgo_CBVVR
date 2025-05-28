<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use App\Models\Company;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function index()
    {
        $risks = Risk::with('company')->get();
        return view('admin.risks.index', compact('risks'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('admin.risks.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'risk_type' => 'required|string|max:255',
            'severity' => 'required|in:baja,media,alta',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'mitigation_measures' => 'nullable|string'
        ]);

        Risk::create($validated);

        return redirect()->route('risks.index')->with('success', 'Riesgo creado exitosamente.');
    }

    public function show(Risk $risk)
{
    $risk->load([
        'company',
        'concepts.inspection.user',
        'concepts.archivos'
    ]);
    
        return view('admin.risks.show', compact('risk'));
}

    public function edit(Risk $risk)
    {
        $companies = Company::all();
        return view('admin.risks.edit', compact('risk', 'companies'));
    }

    public function update(Request $request, Risk $risk)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'risk_type' => 'required|string|max:255',
            'severity' => 'required|in:baja,media,alta',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'mitigation_measures' => 'nullable|string'
        ]);

        $risk->update($validated);

        return redirect()->route('risks.index')->with('success', 'Riesgo actualizado exitosamente.');
    }

    public function destroy(Risk $risk)
    {
        $risk->delete();
        return redirect()->route('risks.index')->with('success', 'Riesgo eliminado exitosamente.');
    }


public function mapView()
{
    // Obtener empresas con sus relaciones
    $companies = Company::with([
        'risks',
        'inspections.user',
        'inspections.concept.infoestablecimiento', // Cargar relación anidada
        'info_establecimiento'
    ])->get();
    
    // Preparar datos para el mapa
    $mapData = $companies->map(function($company) {
        // Obtener conceptos no nulos de las inspecciones
        $concepts = $company->inspections->filter(function($inspection) {
            return !is_null($inspection->concept);
        })->map(function($inspection) {
            return $inspection->concept;
        });
        
        return [
            'id' => $company->id,
            'name' => $company->razon_social,
            'establishment' => $company->nombre_establecimiento,
            'address' => $company->direccion,
            'lat' => $company->risks->avg('latitude') ?? null,
            'lng' => $company->risks->avg('longitude') ?? null,
            'risks' => $company->risks->map(function($risk) {
                return [
                    'id' => $risk->id,
                    'name' => $risk->name,
                    'type' => $risk->risk_type,
                    'severity' => $risk->severity,
                    'description' => $risk->description,
                    'mitigation' => $risk->mitigation_measures
                ];
            }),
            'concepts' => $concepts->map(function($concept) {
                return [
                    'id' => $concept->id ?? null, // Manejo seguro del ID
                    'date' => $concept->fecha_concepto ?? 'N/A',
                    'favorable' => $concept->favorable ?? false,
                    'recommendations' => $concept->recomendaciones ?? 'N/A',
                    'inspection_id' => $concept->inspeccion_id ?? null,
                    'inspector' => optional(optional($concept->inspection)->user)->name ?? 'N/A'
                ];
            })->filter(function($concept) {
                return !is_null($concept['id']); // Filtrar conceptos sin ID
            })->values() // Reindexar el array
        ];
    })->filter(function($company) {
        return !is_null($company['lat']) && !is_null($company['lng']);
    });

    return view('admin.risks.map', compact('mapData'));
}
}