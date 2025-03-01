<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        //dd($request->all());
        $inspection = Inspection::findOrFail($inspection_id);
        $input_name = "photos_{$inspection_id}";

        $validatedData = $request->validate([
            
            ////INFORMACION DEL ESTABLECIMIENTO SE CARGA DIRECTAMENTE CON LA INFORMACION ANTES REGISTRADA//////

            ////CARACTERISTICAS DE LA CONSTRUCCION////

            "anio_construcción_{$inspection_id}" => 'required|integer|min:1800|max:' . date('Y'),
            "nrs_{$inspection_id}" => 'required|in:1,0',
            "sst_{$inspection_id}" => 'required|in:1,0,null',

            ////EQUIPOS PARA EXTINCION DE INCENDIOS//////
            "sistema_automatico_{$inspection_id}" => 'required|in:1,0,null',
            "tipo_sistema_{$inspection_id}" => 'nullable|string|max:255',
            "observaciones_S_A_{$inspection_id}" => 'string|nullable|max:500',
            "red_incendios_{$inspection_id}" => 'required|in:1,0,null',
            "hidrantes_{$inspection_id}" => 'required|in:1,0,null',
            "tipo_hidrante_{$inspection_id}" => 'required|string|max:255',
            "distancia_hidrante_{$inspection_id}" => 'required|numeric',
            "observaciones_red_{$inspection_id}" => 'string|nullable|max:500',
            "capacitacion_{$inspection_id}" => 'required|in:1,0,null',
            "observaciones_extintores_{$inspection_id}" => 'string|nullable|max:500',


            ///////PRIMEROS AUXILIOS/////////
            "botiquin_{$inspection_id}" => 'required|in:1,0,null',
            "observaciones_botiquin_{$inspection_id}" => 'string|nullable|max:500',
            "camilla_{$inspection_id}" => 'required|in:1,0,null',
            "tipo_camilla_{$inspection_id}" => 'required|string|max:255',
            "cervicales_{$inspection_id}" => 'required|in:1,0,null',
            "tipo_cervicales_{$inspection_id}" => 'required|string|max:255',
            "extremidades_{$inspection_id}" => 'required|in:1,0,null',
            "tipo_extremidades_{$inspection_id}" => 'required|string|max:255',
            "capacitacion_PA_{$inspection_id}" => 'required|in:1,0,null',
            "tipo_capacitacion_PA_{$inspection_id}" => 'required|string|max:255',
            "observaciones_equipo_lesionados_{$inspection_id}" => 'string|nullable|max:500',

            //////RUTAS DE EVACUACION////////////////

            "ruta_{$inspection_id}" => 'required|in:1,0,null',
            "salida_{$inspection_id}" => 'required|in:1,0,null',
            "observaciones_salida_emergencia_{$inspection_id}" => 'string|nullable|max:500',
            "escaleras_{$inspection_id}" => 'required|in:1,0,null',
            "condicion_escaleras_{$inspection_id}" => 'required|in:bueno,regular,malo',
            "señalizadas_{$inspection_id}" => 'required|in:1,0,null',
            "condicion_señalizacion_{$inspection_id}" => 'required|in:bueno,regular,malo',
            "barandas_{$inspection_id}" => 'required|in:1,0,null',
            "condicion_barandas_{$inspection_id}" => 'required|in:bueno,regular,malo',
            "antideslizante_{$inspection_id}" => 'required|in:1,0,null',
            "condicion_antideslizantes_{$inspection_id}" => 'required|in:bueno,regular,malo',
            "observaciones_antideslizante_{$inspection_id}" => 'string|nullable|max:500',


            //////////SISTEMA DE ILUMINACION DE EMERGENCIA///////
            "iluminacion_emergencia_{$inspection_id}" => 'required|in:1,0,null',
            "fecha_ultima_prueba_{$inspection_id}" => 'required|date|before_or_equal:today',
            "observaciones_iluminacion_emergencia_{$inspection_id}" => 'string|nullable|max:500',

            //////////CONDICIONES DEL SISTEMA ELECTRICO////////

            "breker_{$inspection_id}" => 'required|in:1,0,null',
            "identificados_{$inspection_id}" => 'required|string|max:255',
            "empalme_{$inspection_id}" => 'required|in:1,0',
            "toma_corriente_{$inspection_id}" => 'required|in:1,0',
            "sobrecarga_{$inspection->id}" =>  'required|in:1,0',
            "voltaje_{$inspection_id}" => 'required|in:1,0,null',
            "cajetines_{$inspection_id}" => 'required|in:1,0',
            "boton_{$inspection_id}" => 'required|in:1,0',
            "mantenimiento_{$inspection_id}" => 'required|in:1,0',
            "periodicidad_{$inspection_id}" => 'required|string|max:255',
            "personal_idoneo_{$inspection_id}" => 'required|in:1,0',
            "observaciones_sistema_electrico_{$inspection_id}" => 'string|nullable|max:500',

            /////////ALMACENAMIENTO DE COMBUSTIBLE//////////

            "material_solido_{$inspection_id}" => 'required|in:1,0',
            "Almacenamiento_solidos_{$inspection_id}" => 'required|in:1,0',
            "observaciones_solidos_{$inspection_id}" => 'string|nullable|max:500',
            "cantidad_solidos_{$inspection_id}" => 'required|numeric',
            "material_liquido_{$inspection_id}" => 'required|in:1,0',
            "Almacenamiento_liquidos_{$inspection_id}" => 'required|in:1,0',
            "observaciones_liquidos_{$inspection_id}" => 'string|nullable|max:500',
            "cantidad_liquidos_{$inspection_id}" => 'required|numeric',
            "material_gaseoso_{$inspection_id}" => 'required|in:1,0',
            "Almacenamiento_gaseoso_{$inspection_id}" => 'required|in:1,0',
            "observaciones_gaseoso_{$inspection_id}" => 'string|nullable|max:500',
            "cantidad_gaseoso_{$inspection_id}" => 'required|numeric',
            "quimico_{$inspection_id}" => 'required|in:1,0',
            "Almacenamiento_quimico_{$inspection_id}" => 'required|in:1,0',
            "observaciones_quimico_{$inspection_id}" => 'string|nullable|max:500',
            "cantidad_quimico_{$inspection_id}" => 'required|numeric',

            ////////OTRAS CONDICIONES DE RIESGO///////////

            "otra_condicion_riesgo_{$inspection_id}" => 'required|string|max:255',
            "observaciones_otros_{$inspection_id}" => 'required|string|max:500',
            "recomendaciones_otros_{$inspection_id}" => 'required|string|max:500',

             ///Validaciones del concepto
             "favorable_{$inspection_id}" => 'required|in:1,0',
             "{$input_name}.*" => 'image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);


        //////////Validacion de campos dinamicos del formulario//////

        ///1. Tipo de sistema automatico://////
        $sistemaAutomatico = $request->input("sistema_automatico_{$inspection_id}");
        if ($sistemaAutomatico === 'null') {
            $validatedData["tipo_sistema_{$inspection_id}"] = null;
        }
        ///2. Tipo de Red

        $redIncendios = $request->input("red_incendios_{$inspection_id}");
        if ($redIncendios === 'null') {
            $validatedData["tipo_red_{$inspection_id}"] = null; // Forzar el valor de tipo_red a null
        }

        ///3. Tipo de extintores
        $data = $request->all();
        $extintores = [];

        // Agrupar datos dinámicos de extintores por índice
        foreach ($data as $key => $value) {
            if (preg_match("/^(tipo|empresa|recarga|vencimiento|cantidad)_(\d+)_(\d+)$/", $key, $matches)) {
                $field = $matches[1]; // 'tipo', 'empresa', etc.
                $inspectionIdInput = $matches[2]; // ID de la inspección
                $extintorIndex = $matches[3]; // Índice del extintor

                // Ignorar datos de otras inspecciones
                if ($inspectionIdInput != $inspection_id) {
                    continue;
                }

                $extintores[$extintorIndex][$field] = $value;
            }
        }

        // Depuración para verificar estructura
        //dd($extintores);

        // Validar cada extintor
        $validatedExtintores = [];
        foreach ($extintores as $index => $extintor) {
            if (!isset($extintor['tipo'])) {
                return redirect()->back()->withErrors(["tipo_{$index}" => "El campo tipo es obligatorio para el extintor {$index}."])->withInput();
            }

            $validator = Validator::make($extintor, [
                'tipo' => 'required|string|max:255',
                'empresa' => 'required|string|max:255',
                'recarga' => 'required|date',
                'vencimiento' => 'required|date|after:recarga',
                'cantidad' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validatedExtintores[] = [
                'tipo_extintor_id' => $validator->validated()['tipo'], // Renombrar clave según columna de la BD
                'empresa_recarga' => $validator->validated()['empresa'],
                'fecha_recarga' => $validator->validated()['recarga'],
                'fecha_vencimiento' => $validator->validated()['vencimiento'],
                'cantidad' => $validator->validated()['cantidad'],
            ];
        }



        ////4. Validacion de botiquines///////

        // Manejo y validación de botiquines dinámicos
        $data = $request->all(); // Obtener todos los datos enviados
        $botiquines = [];

        // Agrupar datos dinámicos de botiquines por índice
        foreach ($data as $key => $value) {
            if (preg_match("/^(kit|cantidad)_(\d+)_(\d+)$/", $key, $matches)) {
                $field = $matches[1]; // 'kit' o 'cantidad'
                $inspectionIdInput = $matches[2]; // ID de la inspección
                $botiquinIndex = $matches[3]; // Índice del botiquín

                // Ignorar datos que no pertenezcan a esta inspección
                if ($inspectionIdInput != $inspection_id) {
                    continue;
                }

                // Agrupar los datos del botiquín
                $botiquines[$botiquinIndex][$field] = $value;
            }
        }

        // Validar los botiquines dinámicos
        $validatedBotiquines = [];
        foreach ($botiquines as $index => $botiquin) {
            $validator = Validator::make($botiquin, [
                'kit' => 'required|string|max:255',
                'cantidad' => 'required|integer|min:1',
            ], [
                'kit.required' => "El tipo de botiquín es obligatorio (botiquín {$index}).",
                'cantidad.required' => "La cantidad es obligatoria (botiquín {$index}).",
                'cantidad.min' => "La cantidad debe ser al menos 1 (botiquín {$index}).",
            ]);

            // Si falla la validación, retorna errores
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Almacenar botiquines validados
            $validatedBotiquines[] = $validator->validated();
        }



        /////////////Fin validaciones dinamicas////////////////////

        ////Crear el equipo contra incendios
        $equipoContraIncendios = Equipo_incendio::create([
            'sistema_automatico' => $validatedData["sistema_automatico_{$inspection_id}"],
            'tipo_sistema' => $validatedData["tipo_sistema_{$inspection_id}"],
            'observaciones_sa' => $validatedData["observaciones_S_A_{$inspection_id}"],
            'red_contra_incendios' => $validatedData["red_incendios_{$inspection_id}"],
            'hidrantes' => $validatedData["hidrantes_{$inspection_id}"],
            'tipo_hidrante' => $validatedData["tipo_hidrante_{$inspection_id}"],
            'distancia' => $validatedData["distancia_hidrante_{$inspection_id}"],
            'observaciones_hyr' => $validatedData["observaciones_red_{$inspection_id}"],
            'capacitacion' => $validatedData["capacitacion_{$inspection_id}"],
            'observaciones' => $validatedData["observaciones_extintores_{$inspection_id}"],
        ]);


        ///Crea informacion de los extintores presentes en el sistema de incendios
        if (!empty($validatedExtintores)) {
            foreach ($validatedExtintores as $extintor) {
                Extintor_sistema_incendios::create([
                    'id_equipo_contra_incendio' => $equipoContraIncendios->id, // Relacionar con el equipo
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
            'botiquin' => $validatedData["botiquin_{$inspection_id}"],
            'observaciones_botiquin' => $validatedData["observaciones_botiquin_{$inspection_id}"],
            'camilla' => $validatedData["camilla_{$inspection_id}"],
            'tipo_camilla' => $validatedData["tipo_camilla_{$inspection_id}"],
            'inmovilizador_cervical' => $validatedData["cervicales_{$inspection_id}"],
            'tipo_inm_cervical' => $validatedData["tipo_cervicales_{$inspection_id}"],
            'inmovilizador_extremidades' => $validatedData["extremidades_{$inspection_id}"],
            'tipo_inm_extremidades' => $validatedData["tipo_extremidades_{$inspection_id}"],
            'capacitacion_primeros_auxilios' => $validatedData["capacitacion_PA_{$inspection_id}"],
            'tipo_capacitacion' => $validatedData["tipo_capacitacion_PA_{$inspection_id}"],
            'observaciones' => $validatedData["observaciones_equipo_lesionados_{$inspection_id}"],
        ]);


        /////////Crea la informacion de los botiquines presentes en el primeros auxilios

        if (!empty($validatedBotiquines)) {
            foreach ($validatedBotiquines as $botiquin) {
                Botiquin_primeros_auxilios::create([
                    'id_primeros_auxilios' => $primerosAuxilios->id, // Relacionar con primeros auxilios
                    'tipo_botiquin_id' =>  $botiquin['kit'], // Suponiendo que 'kit' es el ID del tipo de botiquín
                    'cantidad' =>  $botiquin['cantidad'],
                ]);
            }
        }

        //////////Crear contruccion

        $construccion = Construccion::create([
            'anio_construccion' => $validatedData["anio_construcción_{$inspection_id}"],
            'nrs' => $validatedData["nrs_{$inspection_id}"], // Se usa el valor validado directamente
            'sst' => $validatedData["sst_{$inspection_id}"], // Se permite null según la validación
            'id_info_establecimiento' => $inspection->company->info_establecimiento->first()->id, // Valor por defecto si no está presente
        ]);


        ////////Crear ruta de evacuacion//////

        $rutaEvacuacion = Ruta_evacuacion::create([
            'ruta_evacuacion' => $validatedData["ruta_{$inspection_id}"],
            'salidas_emergencia' => $validatedData["salida_{$inspection_id}"],
            'observaciones' => $validatedData["observaciones_salida_emergencia_{$inspection_id}"] ?? null,
            'escaleras' => $validatedData["escaleras_{$inspection_id}"],
            'señalizadas' => $validatedData["señalizadas_{$inspection_id}"],
            'barandas' => $validatedData["barandas_{$inspection_id}"],
            'antideslizante' => $validatedData["antideslizante_{$inspection_id}"],
            'condicion_escaleras' => $validatedData["condicion_escaleras_{$inspection_id}"],
            'condicion_señalizadas' => $validatedData["condicion_señalizacion_{$inspection_id}"],
            'condicion_barandas' => $validatedData["condicion_barandas_{$inspection_id}"],
            'condicion_antideslizante' => $validatedData["condicion_antideslizantes_{$inspection_id}"],
            'observaciones_escaleras' => $validatedData["observaciones_antideslizante_{$inspection_id}"] ?? null,
        ]);


        ///////crear sistema de iluminacion////////
        $sistemaIluminacion = Sistema_iluminacion::create([
            'sistema_iluminacion' => $validatedData["iluminacion_emergencia_{$inspection_id}"],
            'fecha_ultima_prueba' => $validatedData["fecha_ultima_prueba_{$inspection_id}"],
            'observaciones' => $validatedData["observaciones_iluminacion_emergencia_{$inspection_id}"] ?? null, // Manejo de campo nullable
        ]);


        ///////crear sistema electrico//////////

        $sistemaElectrico = Sistema_electrico::create([
            'caja_distribucion_breker' => $validatedData["breker_{$inspection_id}"],
            'encuentra_identificados' => $validatedData["identificados_{$inspection_id}"],
            'sistema_cableado_protegido' => $validatedData["empalme_{$inspection_id}"],
            'toma_corriente_corto' => $validatedData["toma_corriente_{$inspection_id}"],
            'toma_corriente_sobrecarga' => $validatedData["sobrecarga_{$inspection_id}"],
            'identificacion_voltaje' => $validatedData["voltaje_{$inspection_id}"],
            'cajetines_asegurados' => $validatedData["cajetines_{$inspection_id}"],
            'boton_emergencia' => $validatedData["boton_{$inspection_id}"],
            'mantenimiento_preventivo' => $validatedData["mantenimiento_{$inspection_id}"],
            'periodicidad' => $validatedData["periodicidad_{$inspection_id}"],
            'personal_idoneo' => $validatedData["personal_idoneo_{$inspection_id}"],
            'observaciones' => $validatedData["observaciones_sistema_electrico_{$inspection_id}"] ?? null, // Manejo de campo opcional
        ]);


        /////////  Almacenamiento de combustible

        $almacenamientoCombustibles = Almacenamiento::create([
            // Material sólido ordinario
            'material_solido_ordinario' => $validatedData["material_solido_{$inspection_id}"],
            'zona_almacenamiento_1' => $validatedData["Almacenamiento_solidos_{$inspection_id}"],
            'observaciones_1' => $validatedData["observaciones_solidos_{$inspection_id}"] ?? null,
            'cantidad_1' => $validatedData["cantidad_solidos_{$inspection_id}"],

            // Material líquido inflamable
            'material_liquido_inflamable' => $validatedData["material_liquido_{$inspection_id}"],
            'zona_almacenamiento_2' => $validatedData["Almacenamiento_liquidos_{$inspection_id}"],
            'observaciones_2' => $validatedData["observaciones_liquidos_{$inspection_id}"] ?? null,
            'cantidad_2' => $validatedData["cantidad_liquidos_{$inspection_id}"],

            // Material gaseoso inflamable
            'material_gaseoso_inflamable' => $validatedData["material_gaseoso_{$inspection_id}"],
            'zona_almacenamiento_3' => $validatedData["Almacenamiento_gaseoso_{$inspection_id}"],
            'observaciones_3' => $validatedData["observaciones_gaseoso_{$inspection_id}"] ?? null,
            'cantidad_3' => $validatedData["cantidad_gaseoso_{$inspection_id}"],

            // Otros químicos
            'otros_quimicos' => $validatedData["quimico_{$inspection_id}"],
            'zona_almacenamiento_4' => $validatedData["Almacenamiento_quimico_{$inspection_id}"],
            'observaciones_4' => $validatedData["observaciones_quimico_{$inspection_id}"] ?? null,
            'cantidad_4' => $validatedData["cantidad_quimico_{$inspection_id}"],
        ]);



        /////// Crear otras condiciones/////

        $otrasCondiciones = Otras_Condiciones::create([
            'condicion' => $validatedData["otra_condicion_riesgo_{$inspection_id}"],
            'observacion' => $validatedData["observaciones_otros_{$inspection_id}"],
            'recomendaciones' => $validatedData["recomendaciones_otros_{$inspection_id}"], // Agregar el campo 'recomendaciones' si corresponde a la base de datos
        ]);



        ///////////CREAR EL CONCEPTO////////////
        $inspection->estado = 'REVISADA';

        if ($validatedData["favorable_{$inspection_id}"] == 1) {
            $inspection->estado = 'FINALIZADA';
        }

        $inspection->save();

        $concepto = new Concept();

        // Asignar los datos del concepto
        $concepto->fecha_concepto = Carbon::now()->toDateString();
        $concepto->inspeccion_id = $inspection->id;  // El ID de la inspección
        $concepto->favorable = $validatedData["favorable_{$inspection_id}"];
        $concepto->recomendaciones = $validatedData["recomendaciones_otros_{$inspection_id}"] ?? '';
        $concepto->id_info_establecimiento = $inspection->company->info_establecimiento->first()->id;
        $concepto->id_auxilios = $primerosAuxilios->id;
        $concepto->id_equipo = $equipoContraIncendios->id;
        $concepto->id_construccion = $construccion->id;
        $concepto->id_ruta = $rutaEvacuacion->id;
        $concepto->id_sistema_iluminacion = $sistemaIluminacion->id;
        $concepto->id_sistema_electrico = $sistemaElectrico->id;
        $concepto->id_almacenamiento = $almacenamientoCombustibles->id;
        $concepto->id_otros = $otrasCondiciones->id;
        
        $concepto->save();

        /////Guardar las imágenes

        if ($request->hasFile($input_name)) {
            foreach ($request->file($input_name) as $photo) {
                // Ruta de almacenamiento
                $empresa =$inspection->company;
                $empresaFolder = "public/documentos/empresa-{$empresa -> id}";
                $conceptFolder = "{$empresaFolder}/concepto-{$concepto -> id}";
    
                // Crear las carpetas si no existen
                if (!Storage::exists($conceptFolder)) {
                    Storage::makeDirectory($conceptFolder);
                }
    
                // Guardar el archivo con un nombre único
                $filename = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs($conceptFolder, $filename);
    
                // Registrar el archivo en la base de datos
                $archivo = Archivos::create([
                    'tipo_archivo' => 'imagen de concepto',
                    'url' => str_replace('public/', 'storage/', $path),
                    'id_concepto' => $concepto -> id
                ]); 
            }

        return redirect()->route('inspector.inspeccionesAsignadas')->with('success', 'El concepto se creó con éxito');
    }

}
}
