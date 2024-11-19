<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establecimiento;

class EstablecimientoController extends Controller
{
    public function store(Request $request, $id)
    {
       
        $request->validate([
            'num_pisos' => 'required',
            'ancho_dimensiones' => 'required',
            'largo_dimensiones' => 'required',
            'carga_ocupacional_fija' => 'required',
            'carga_ocupacional_flotante' => 'required',
        ]);

        $establecimiento = new Establecimiento();
        $establecimiento->num_pisos = $request->num_pisos;
        $establecimiento->ancho_dimensiones = $request->ancho_dimensiones;
        $establecimiento->largo_dimensiones = $request->largo_dimensiones;
        $establecimiento->carga_ocupacional_fija = $request->carga_ocupacional_fija;
        $establecimiento->carga_ocupacional_flotante = $request->carga_ocupacional_flotante;
        $establecimiento->id_empresa = $id;
        $establecimiento->save();

        return redirect()->route('companies.index')->with('success', 'Establecimiento creado exitosamente.');
    }


    public function storeCliente(Request $request, $id)
    {
       
        $request->validate([
            'num_pisos' => 'required',
            'ancho_dimensiones' => 'required',
            'largo_dimensiones' => 'required',
            'carga_ocupacional_fija' => 'required',
            'carga_ocupacional_flotante' => 'required',
        ]);

        $establecimiento = new Establecimiento();
        $establecimiento->num_pisos = $request->num_pisos;
        $establecimiento->ancho_dimensiones = $request->ancho_dimensiones;
        $establecimiento->largo_dimensiones = $request->largo_dimensiones;
        $establecimiento->carga_ocupacional_fija = $request->carga_ocupacional_fija;
        $establecimiento->carga_ocupacional_flotante = $request->carga_ocupacional_flotante;
        $establecimiento->id_empresa = $id;
        $establecimiento->save();

        return redirect()->route('cliente.datosEmpresa')->with('success', 'Establecimiento creado exitosamente.');
    }
}
