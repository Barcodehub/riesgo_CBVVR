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
    // Obtener empresas con sus riesgos e inspecciones
    $companies = Company::with(['risks', 'inspections'])->get();
    
    // Preparar datos para el mapa
    $mapData = $companies->map(function($company) {
        return [
            'id' => $company->id,
            'name' => $company->razon_social,
            'establishment' => $company->nombre_establecimiento,
            'address' => $company->direccion,
            'lat' => $company->risks->avg('latitude') ?? null,
            'lng' => $company->risks->avg('longitude') ?? null,
            'risks' => $company->risks->map(function($risk) {
                return [
                    'name' => $risk->name,
                    'type' => $risk->risk_type,
                    'severity' => $risk->severity,
                    'description' => $risk->description,
                    'mitigation' => $risk->mitigation_measures
                ];
            }),
            'inspections' => $company->inspections->map(function($inspection) {
                return [
                    'date' => $inspection->fecha_solicitud,
                    'status' => $inspection->estado,
                    'value' => $inspection->valor,
                    'inspector' => $inspection->user->name ?? 'N/A'
                ];
            })
        ];
    })->filter(function($company) {
        return !is_null($company['lat']) && !is_null($company['lng']);
    });

    return view('admin.risks.map', compact('mapData'));
}
}