<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\Inspection;
use App\Models\TypeExtinguisher;
use App\Models\TypeKit;
use App\Models\Almacenamiento;
use App\Models\Archivos;
use App\Models\construccion;
use App\Models\Equipo_incendio;
use App\Models\Otras_condiciones;
use App\Models\Primeros_auxilios;
use App\Models\Ruta_evacuacion;
use App\Models\sistema_electrico;
use App\Models\Sistema_iluminacion;
use App\Models\Extintor_sistema_incendios;
use App\Models\botiquin_primeros_auxilios;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConceptController extends Controller
{

    public function index()
    {
        $concepts = Concept::all();

        return view('admin.concepts.index', ['concepts' => $concepts]);
    }

    public function getExtinguishers()
    {
        // Obtener todos los tipos de extintores
        $extinguishers = TypeExtinguisher::all();

        // Devolver los tipos de extintores como JSON
        return response()->json($extinguishers);
    }

    public function getBotiquines()
    {
        // Obtener todos los tipos de extintores
        $botiquines = TypeKit::all();

        // Devolver los tipos de extintores como JSON
        return response()->json($botiquines);
    }

    public function store(Request $request, $inspection_id)
    {

        $inspection = Inspection::findOrFail($inspection_id);


        $validatedData = $request->validate([
            ///Validaciones del concepto
            'favorable' => 'required|boolean',
            'recomendaciones' => 'nullable|string',

            ////INFORMACION DEL ESTABLECIMIENTO SE CARGA DIRECTAMENTE CON LA INFORMACION ANTES REGISTRADA//////

            ////CARACTERISTICAS DE LA CONSTRUCCION////

            'construccion.anio_construccion' => 'required|date',
            'construccion.nrs' => 'boolean',
            'construccion.sst' => 'required|boolean',
            'construccion.id_info_establecimiento' => 'required',

            ////EQUIPOS PARA EXTINCION DE INCENDIOS//////

            'equipo_contra_incendio.sistema_automatico' => 'required|boolean',
            'equipo_contra_incendio.tipo_sistema' => 'required|string',
            'equipo_contra_incendio.observaciones_sa' => 'string|nullable',
            'equipo_contra_incendio.red_contra_incendios' => 'boolean',
            'equipo_contra_incendio.hidrantes' => 'boolean',
            'equipo_contra_incendio.tipo_hidrante' => 'string|nullable',
            'equipo_contra_incendio.distancia' => 'required|numeric',
            'equipo_contra_incendio.observaciones_hyr' => 'String|nullable',
            'equipo_contra_incendio.extintores' => 'required|boolean',
            'equipo_contra_incendio.capacitacion' => 'required|boolean',
            'equipo_contra_incendio.observaciones' => 'String|nullable',

            /////TIPO DE EXTINTOR EQUIPO DE EXTINCION /////

            'tipo_extintor_equipo' => 'nullable|array|min:1',
            'tipo_extintor_equipo.*.tipo_extintor_id' => 'required|exists:type_extinguishers,id',
            'tipo_extintor_equipo.*.empresa_recarga' => 'required|string|max:255',
            'tipo_extintor_equipo.*.fecha_recarga' => 'required|date',
            'tipo_extintor_equipo.*.fecha_vencimiento' => 'required|date|after:tipo_extintor_equipo.*.fecha_recarga',
            'tipo_extintor_equipo.*.cantidad' => 'required|integer|min:1',

            ///////PRIMEROS AUXILIOS/////////

            'primeros_auxilios.camilla' => 'required|boolean',
            'primeros_auxilios.inmovilizador_cervical' => 'required|booleanl',
            'primeros_auxilios.inmovilizador_extremidades' => 'required|boolean',
            'primeros_auxilios.capacitacion_primeros_auxilios' => 'required|boolean',
            'primeros_auxilios.tipo_camilla' => 'required|string|max:255',
            'primeros_auxilios.tipo_inm_cervical' => 'required|string|max:255',
            'primeros_auxilios.tipo_inm_extremidades' => 'required|string|max:255',
            'primeros_auxilios.tipo_capacitacion' => 'required|string|max:255',
            'primeros_auxilios.observaciones' => 'required|string|max:255',

            ///////BOTIQUIN_PRIMEROS_AUXILIOS/////

            'tipo_botiquin_Auxilios' => 'nullable|array|min:1',
            'tipo_botiquin_Auxilios.*.tipo_botiquin_id' => 'integer|exists:type_kits,id',
            'tipo_botiquin_Auxilios.*.cantidad' => 'nullable|integer|min:1',

            //////RUTAS DE EVACUACION////////////////

            'ruta_evacuacion.ruta_evacuacion' => 'required|boolean',
            'ruta_evacuacion.salidas_emergencia' => 'required|boolean',
            'ruta_evacuacion.observaciones' => 'required|string|max:255',
            'ruta_evacuacion.escaleras' => 'required|boolean',
            'ruta_evacuacion.señalizadas' => 'required|boolean',
            'ruta_evacuacion.barandas' => 'required|boolean',
            'ruta_evacuacion.condicion_escaleras' => 'required|string|max:255',
            'ruta_evacuacion.condicion_señalizadas' => 'required|string|max:255',
            'ruta_evacuacion.condicion_barandas' => 'required|string|max:255',
            'ruta_evacuacion.condicion_antideslizante' => 'required|string|max:255',
            'ruta_evacuacion.observaciones_escaleras' => 'required|string|max:255',


            //////////SISTEMA DE ILUMINACION DE EMERGENCIA///////
            'sistema_iluminacion.sistema_iluminacion' => 'required|boolean',
            'sistema_iluminacion.fecha_ultima_prueba' => 'required|date',
            'sistema_iluminacion.observaciones' => 'required|string|max:255',

            //////////CONDICIONES DEL SISTEMA ELECTRICO////////

            'sistema_electrico.caja_distribucion_breker' => 'required|boolean',
            'sistema_electrico.encuentra_identificados' => 'required|boolean',
            'sistema_electrico.sistema_cableado_protegido' => 'required|boolean',
            'sistema_electrico.toma_corriente_corto' => 'required|boolean',
            'sistema_electrico.toma_corriente_sobrecarga' => 'required|boolean',
            'sistema_electrico.identificacion_voltaje' => 'required|boolea',
            'sistema_electrico.cajetines_asegurados' => 'required|boolean',
            'sistema_electrico.boton_emergencia' => 'required|boolean',
            'sistema_electrico.mantenimiento_preventivo' => 'required|boolean',
            'sistema_electrico.periodicidad' => 'required|string|max:255',
            'sistema_electrico.personal_idoneo' => 'required|boolean',
            'sistema_electrico.observaciones' => 'required|string|max:255',

            /////////ALMACENAMIENTO DE COMBUSTIBLE//////////

            'almacenamiento_combustibles.material_solido_ordinario' => 'required|boolean',
            'almacenamiento_combustibles.zona_almacenamiento_1' => 'required|boolean',
            'almacenamiento_combustibles.observaciones_1' => 'required|string|max:255',
            'equipo_contra_incendio.cantidad_1' => 'required|numeric',
            'almacenamiento_combustibles.material_liquido_inflamable' => 'required|boolean',
            'almacenamiento_combustibles.zona_almacenamiento_2' => 'required|boolean',
            'almacenamiento_combustibles.observaciones_2' => 'required|string|max:255',
            'equipo_contra_incendio.cantidad_2' => 'required|numeric',
            'almacenamiento_combustibles.material_gaseoso_inflamable' => 'required|boolean',
            'almacenamiento_combustibles.zona_almacenamiento_3' => 'required|boolean',
            'almacenamiento_combustibles.observaciones_3' => 'required|string|max:255',
            'equipo_contra_incendio.cantidad_3' => 'required|numeric',
            'almacenamiento_combustibles.otros_quimicos' => 'required|boolean',
            'almacenamiento_combustibles.zona_almacenamiento_4' => 'required|boolean',
            'almacenamiento_combustibles.observaciones_4' => 'required|string|max:255',
            'equipo_contra_incendio.cantidad_4' => 'required|numeric',

            ////////OTRAS CONDICIONES DE RIESGO///////////

            'otras_condiciones.condicion' => 'required|string|max:500',
            'otras_condiciones.observacion' => 'required|string|max:500',

        ]);

        ////Crear el equipo contra incendios
        $equipoContraIncendios = Equipo_incendio::create([
            'sistema_automatico' => $validatedData['equipo_contra_incendio']['sistema_automatico'],
            'tipo_sistema' => $validatedData['equipo_contra_incendio']['tipo_sistema'],
            'observaciones_sa' => $validatedData['equipo_contra_incendio']['observaciones_sa'],
            'red_contra_incendios' => $validatedData['equipo_contra_incendio']['red_contra_incendios'],
            'hidrantes' => $validatedData['equipo_contra_incendio']['hidrantes'],
            'tipo_hidrante' => $validatedData['equipo_contra_incendio']['tipo_hidrante'],
            'distancia' => $validatedData['equipo_contra_incendio']['distancia'],
            'observaciones_hyr' => $validatedData['equipo_contra_incendio']['observaciones_hyr'],
            'extintores' => $validatedData['equipo_contra_incendio']['extintores'],
            'capacitacion' => $validatedData['equipo_contra_incendio']['capacitacion'],
            'observaciones' => $validatedData['equipo_contra_incendio']['observaciones'],
        ]);

        ///Crea informacion de los extintores presentes en el sistema de incendios
        if (!empty($validatedData['tipo_extintor_equipo'])) {
            foreach ($validatedData['tipo_extintor_equipo'] as $extintor) {
                Extintor_sistema_incendios::create([
                    'equipo_id' => $equipoContraIncendios->id, // Relacionar con el equipo
                    'tipo_extintor_id' => $extintor['tipo_extintor_id'],
                    'empresa_recarga' => $extintor['empresa_recarga'],
                    'fecha_recarga' => $extintor['fecha_recarga'],
                    'fecha_vencimiento' => $extintor['fecha_vencimiento'],
                    'cantidad' => $extintor['cantidad'],
                ]);
            }
        }

        /////////Crear primeros auxilios

        $primerosAuxilios = Primeros_auxilios::create([
            'camilla' => $validatedData['primeros_auxilios']['camilla'],
            'inmovilizador_cervical' => $validatedData['primeros_auxilios']['inmovilizador_cervical'],
            'inmovilizador_extremidades' => $validatedData['primeros_auxilios']['inmovilizador_extremidades'],
            'capacitacion_primeros_auxilios' => $validatedData['primeros_auxilios']['capacitacion_primeros_auxilios'],
            'tipo_camilla' => $validatedData['primeros_auxilios']['tipo_camilla'],
            'tipo_inm_cervical' => $validatedData['primeros_auxilios']['tipo_inm_cervical'],
            'tipo_inm_extremidades' => $validatedData['primeros_auxilios']['tipo_inm_extremidades'],
            'tipo_capacitacion' => $validatedData['primeros_auxilios']['tipo_capacitacion'],
            'observaciones' => $validatedData['primeros_auxilios']['observaciones'],
        ]);

        /////////Crea la informacion de los botiquines presentes en el primeros auxilios

        if (!empty($validatedData['tipo_botiquin_Auxilios'])) {
            foreach ($validatedData['tipo_botiquin_Auxilios'] as $botiquin) {
                botiquin_primeros_auxilios::create([
                    'primeros_auxilios_id' => $primerosAuxilios->id, // Relacionar con primeros auxilios
                    'tipo_botiquin_id' => $botiquin['tipo_botiquin_id'],
                    'cantidad' => $botiquin['cantidad'],
                ]);
            }
        }

        //////////Crear contruccion

        $construccion = Construccion::create([
            'anio_construccion' => $validatedData['construccion']['anio_construccion'],
            'nrs' => $validatedData['construccion']['nrs'] ?? false, // Default a false si no está presente
            'sst' => $validatedData['construccion']['sst'],
            'id_info_establecimiento' => $validatedData['construccion']['id_info_establecimiento'],
        ]);

        ////////Crear ruta de evacuacion//////

        $rutaEvacuacion = Ruta_evacuacion::create([
            'ruta_evacuacion' => $validatedData['ruta_evacuacion']['ruta_evacuacion'],
            'salidas_emergencia' => $validatedData['ruta_evacuacion']['salidas_emergencia'],
            'observaciones' => $validatedData['ruta_evacuacion']['observaciones'],
            'escaleras' => $validatedData['ruta_evacuacion']['escaleras'],
            'señalizadas' => $validatedData['ruta_evacuacion']['señalizadas'],
            'barandas' => $validatedData['ruta_evacuacion']['barandas'],
            'condicion_escaleras' => $validatedData['ruta_evacuacion']['condicion_escaleras'],
            'condicion_señalizadas' => $validatedData['ruta_evacuacion']['condicion_señalizadas'],
            'condicion_barandas' => $validatedData['ruta_evacuacion']['condicion_barandas'],
            'condicion_antideslizante' => $validatedData['ruta_evacuacion']['condicion_antideslizante'],
            'observaciones_escaleras' => $validatedData['ruta_evacuacion']['observaciones_escaleras'],
        ]);

        ///////crear sistema de iluminacion////////
        $sistemaIluminacion = Sistema_iluminacion::create([
            'sistema_iluminacion' => $validatedData['sistema_iluminacion']['sistema_iluminacion'],
            'fecha_ultima_prueba' => $validatedData['sistema_iluminacion']['fecha_ultima_prueba'],
            'observaciones' => $validatedData['sistema_iluminacion']['observaciones'],
        ]);

        ///////crear sistema electrico//////////

        $sistemaElectrico = sistema_electrico::create([
            'caja_distribucion_breker' => $validatedData['sistema_electrico']['caja_distribucion_breker'],
            'encuentra_identificados' => $validatedData['sistema_electrico']['encuentra_identificados'],
            'sistema_cableado_protegido' => $validatedData['sistema_electrico']['sistema_cableado_protegido'],
            'toma_corriente_corto' => $validatedData['sistema_electrico']['toma_corriente_corto'],
            'toma_corriente_sobrecarga' => $validatedData['sistema_electrico']['toma_corriente_sobrecarga'],
            'identificacion_voltaje' => $validatedData['sistema_electrico']['identificacion_voltaje'],
            'cajetines_asegurados' => $validatedData['sistema_electrico']['cajetines_asegurados'],
            'boton_emergencia' => $validatedData['sistema_electrico']['boton_emergencia'],
            'mantenimiento_preventivo' => $validatedData['sistema_electrico']['mantenimiento_preventivo'],
            'periodicidad' => $validatedData['sistema_electrico']['periodicidad'],
            'personal_idoneo' => $validatedData['sistema_electrico']['personal_idoneo'],
            'observaciones' => $validatedData['sistema_electrico']['observaciones'],
        ]);

        /////////  Almacenamiento de combustible

        $almacenamientoCombustibles = Almacenamiento::create([
            // Material sólido ordinario
            'material_solido_ordinario' => $validatedData['almacenamiento_combustibles']['material_solido_ordinario'],
            'zona_almacenamiento_1' => $validatedData['almacenamiento_combustibles']['zona_almacenamiento_1'],
            'observaciones_1' => $validatedData['almacenamiento_combustibles']['observaciones_1'],
            'cantidad_1' => $validatedData['almacenamiento_combustibles']['cantidad_1'],

            // Material líquido inflamable
            'material_liquido_inflamable' => $validatedData['almacenamiento_combustibles']['material_liquido_inflamable'],
            'zona_almacenamiento_2' => $validatedData['almacenamiento_combustibles']['zona_almacenamiento_2'],
            'observaciones_2' => $validatedData['almacenamiento_combustibles']['observaciones_2'],
            'cantidad_2' => $validatedData['almacenamiento_combustibles']['cantidad_2'],

            // Material gaseoso inflamable
            'material_gaseoso_inflamable' => $validatedData['almacenamiento_combustibles']['material_gaseoso_inflamable'],
            'zona_almacenamiento_3' => $validatedData['almacenamiento_combustibles']['zona_almacenamiento_3'],
            'observaciones_3' => $validatedData['almacenamiento_combustibles']['observaciones_3'],
            'cantidad_3' => $validatedData['almacenamiento_combustibles']['cantidad_3'],

            // Otros químicos
            'otros_quimicos' => $validatedData['almacenamiento_combustibles']['otros_quimicos'],
            'zona_almacenamiento_4' => $validatedData['almacenamiento_combustibles']['zona_almacenamiento_4'],
            'observaciones_4' => $validatedData['almacenamiento_combustibles']['observaciones_4'],
            'cantidad_4' => $validatedData['almacenamiento_combustibles']['cantidad_4'],
        ]);


        /////// Crear otras condiciones/////

        $otrasCondiciones = Otras_Condiciones::create([
            'condicion' => $validatedData['otras_condiciones']['condicion'],
            'observacion' => $validatedData['otras_condiciones']['observacion'],
        ]);


        ///////////CREAR EL CONCEPTO////////////
        $inspection->estado = 'REVISADA';

        if ($validatedData['favorable'] == 1) {
            $inspection->estado = 'FINALIZADA';
        }

        $inspection->save();

        $concepto = new Concept();

        // Asignar los datos del concepto
        $concepto->fecha_concepto = Carbon::now()->toDateString();
        $concepto->inspeccion_id = $inspection->id;  // El ID de la inspección
        $concepto->favorable = $validatedData['favorable'];
        $concepto->recomendaciones = $validatedData['recomendaciones'] ?? '';
        $concepto->id_info_establecimiento = $inspection->company->info_establecimiento->id;
        $concepto->id_auxilios = $primerosAuxilios->id;
        $concepto->id_equipo = $equipoContraIncendios->id;
        $concepto->id_construccion = $construccion->id;
        $concepto->id_ruta = $rutaEvacuacion->id;
        $concepto->id_sistema_iluminacion = $sistemaIluminacion->id;
        $concepto->id_sistema_electrico = $sistemaElectrico->id;
        $concepto->id_almacenamiento = $almacenamientoCombustibles->id;
        $concepto->id_otros = $otrasCondiciones->id;
        $concepto->id_imagen = $validatedData['id_imagen'] ?? null;

        $concepto->save();

        return redirect()->route('inspector.inspeccionesAsignadas')->with('success', 'El concepto se creó con éxito');
    }

    public function destroy($id)
    {
        //TODO

    }
}
