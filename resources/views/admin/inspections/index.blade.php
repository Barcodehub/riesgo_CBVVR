@extends('admin.dashboard')

@section('content')

<div class="w-full border p-4 m-4">

    <head>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>

    @if (session('success'))
    <h6 class="alert alert-success">{{ session('success') }}</h6>
    @endif

    <h4 class="mb-4">Gestión de Inspecciones: </h4>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre Establecimiento</th>
                <th scope="col">Inspector Asignado</th>
                <th scope="col">Fecha Solicitud</th>
                <th scope="col">Estado</th>
                <th scope="col" colspan="3" class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if ($inspections->isEmpty())
            <tr>
                <td colspan="6" class="text-center">
                    No hay inspecciones
                </td>
            </tr>
            @endif
            @foreach ($inspections as $inspection)
            
            <tr>
                <td>{{$inspection->id}}</td>
                <td>{{$inspection->company->razon_social}}</td>

                <td>
                    @if ($inspection->user)
                    {{$inspection->user->nombre}} {{$inspection->user->apellido}}
                    @else
                    No asignado
                    @endif
                </td>
                <td>{{$inspection->fecha_solicitud}}</td>
                <td>{{$inspection->estado}}</td>
                <td colspan="3" class="text-center">
                    <div class="flex justify-between items-center gap-2">
                        <a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{$inspection->id}}">Ver detalle <i class="ps-2 fa-solid fa-magnifying-glass-plus"></i></a>
                        @if ($inspection->valor == null)
                        <a class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cotizarModal{{$inspection->id}}">Cotizar <i class="ps-2 fa-solid fa-pen"></i></a>
                        @endif
                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#asignarInspectorModal{{$inspection->id}}">Inspector <i class="fa-regular fa-square-check"></i></a>
                        @if ($inspection->concept && $inspection->concept->isNotEmpty())
                        <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalVerConcepto{{$inspection->id}}">Concepto <i class="ps-2 fa-solid fa-magnifying-glass-plus"></i></a>
                        @endif
                    </div>
                </td>
            </tr>

            <!-- MODAL VER DETALLE -->
            <div class="modal fade" id="modal{{$inspection->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ver detalle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3 row g-3">
                                <div class="col-6">
                                    <label for="establecimiento" class="form-label">Establecimiento </label>
                                    <input type="text" class="form-control" id="establecimiento" value="{{ $inspection->company->razon_social }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" value="{{ $inspection->company->telefono }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="inspector" class="form-label">Inspector</label>
                                <input type="text" class="form-control" id="inspector" value="{{ $inspection->user ? $inspection->user->nombre : 'No asignado' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado de la Inspección</label>
                                <input type="text" class="form-control" id="estado" value="{{ $inspection->estado }}" readonly>
                            </div>

                            @if ($inspection->valor == null)
                            <h6>No tiene valor asignado</h6>
                            @else
                            <div class="mb-3">
                                <label for="valor" class="form-label">Valor de la Inspección</label>
                                <input type="text" class="form-control" id="valor" value="$ {{ $inspection->valor }}" readonly>
                            </div>

                            @endif

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL COTIZACION -->
            <div class="modal fade" id="cotizarModal{{$inspection->id}}" tabindex="-1" role="dialog" aria-labelledby="cotizarModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cotizarModalLabel">Cotizar Inspección</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('inspections.update', [$inspection->id]) }}" class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')

                                <!-- Información del establecimiento (solo lectura) -->
                                <h5>Información del Establecimiento</h5>
                                @if ($inspection->company && $inspection->company->info_establecimiento)
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="num_pisos_{{ $inspection->id }}" class="form-label">Número de Pisos:</label>
                                        <input type="text" id="num_pisos_{{ $inspection->id }}" class="form-control" value="{{ $inspection->company->info_establecimiento->first()->num_pisos }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="ancho__{{ $inspection->id }}" class="form-label">Ancho:</label>
                                        <input type="text" id="ancho_{{ $inspection->id }}" class="form-control" value="{{ $inspection->company->info_establecimiento->first()->ancho_dimensiones }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="largo_{{ $inspection->id }}" class="form-label">Largo:</label>
                                        <input type="text" id="largo_{{ $inspection->id }}" class="form-control" value="{{ $inspection->company->info_establecimiento->first()->largo_dimensiones }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="carga_ocupacional_fija_{{ $inspection->id }}" class="form-label">Carga Ocupacional Fija:</label>
                                        <input type="text" id="carga_ocupacional_fij_{{ $inspection->id }}a" class="form-control" value="{{ $inspection->company->info_establecimiento->first()->carga_ocupacional_fija }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="carga_ocupacional_flotante_{{ $inspection->id }}" class="form-label">Carga Ocupacional Flotante:</label>
                                        <input type="text" id="carga_ocupacional_flotante_{{ $inspection->id }}" class="form-control" value="{{ $inspection->company->info_establecimiento->first()->carga_ocupacional_flotante }}" readonly>
                                    </div>
                                </div>
                                @else
                                <p>No hay información del establecimiento disponible para esta inspección.</p>
                                @endif

                                <!-- Select para tipo de inspección -->
                                <div class="mb-3">
                                    <label for="tipo_inspeccion_{{ $inspection->id }}" class="form-label">Tipo de Inspección:</label>
                                    <select id="tipo_inspeccion_{{ $inspection->id }}" name="tipo_inspeccion" class="form-select" required onchange="calcularCosto('{{ $inspection->id }}')">
                                        <option value="" disabled selected>Seleccione el tipo de inspección</option>
                                        <option value="leve">Leve</option>
                                        <option value="moderado">Moderado</option>
                                        <option value="ordinario">Ordinario</option>
                                        <option value="extra">Extra</option>
                                        <option value="condicion_especial">Condición Especial</option>
                                    </select>
                                </div>

                                <!-- Input para valor de la cotización -->
                                <div class="mb-3">
                                    <label for="valor_cotizacion_{{ $inspection->id }}" class="form-label">Valor de la Cotización:</label>
                                    <input type="number" id="valor_cotizacion_{{ $inspection->id }}" name="valor_cotizacion" class="form-control" required>
                                </div>

                                <!-- Botón para enviar -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Crear Cotización</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function calcularCosto(inspeccionId) {
                    // Obtén los valores necesarios (ancho, largo, pisos, tipo de inspección)
                    const ancho = parseFloat(document.getElementById(`ancho_${inspeccionId}`).value);
                    const largo = parseFloat(document.getElementById(`largo_${inspeccionId}`).value);
                    const pisos = parseInt(document.getElementById(`num_pisos_${inspeccionId}`).value, 10);
                    const tipo = document.getElementById(`tipo_inspeccion_${inspeccionId}`).value;

                    if (isNaN(ancho) || isNaN(largo) || isNaN(pisos)) {
                        alert("Por favor, verifica los datos del establecimiento.");
                        return;
                    }

                    // Calcula el área total
                    const areaTotal = ancho * largo * pisos;

                    // Define los rangos de área y los costos según la tabla
                    const rangosArea = [10, 100, 1000, 20000, Infinity];
                    const costos = {
                        "leve": [72358, 133864, 260491, 530027, 2111061],
                        "moderado": [423298, 569824, 770619, 1040155, 2532551],
                        "ordinario": [772427, 1041964, 1407375, 1899412, 2583201],
                        "extra": [1121558, 1514103, 2044129, 2760480, 3712095],
                        "condicion_especial": [1821627, 2458383, 3319450, 4480804, 6049178]
                    };

                    // Determina el rango de área
                    let costo = 0;
                    for (let i = 0; i < rangosArea.length; i++) {
                        if (areaTotal <= rangosArea[i]) {
                            costo = costos[tipo][i];
                            break;
                        }
                    }

                    // Asigna el costo calculado al input de valor de cotización
                    document.getElementById(`valor_cotizacion_${inspeccionId}`).value = costo;
                }
            </script>


            <!-- MODAL ASIGNAR INSPECTOR -->
            <div class="modal fade" id="asignarInspectorModal{{$inspection->id}}" tabindex="-1" role="dialog" aria-labelledby="asignarInspectorModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            @if($inspection->user)
                            <h5 class="modal-title" id="asignarInspectorModalLabel">Ver Asignación de Inspector</h5>
                            @else
                            <h5 class="modal-title" id="asignarInspectorModalLabel">Asignar Inspector</h5>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        </div>

                        @if($inspection->user)

                        <div class="modal-body">

                            <p>Inspector: {{ $inspection->user->nombre }} {{ $inspection->user->apellido }}</p>
                            <p>Fecha Asignación: {{ $inspection->fecha_asignacion_inspector }}</p>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
                        </div>

                        @else

                        <div class="modal-body">

                            <form method="POST" action="{{ route('inspections.asignarInspector', [$inspection->id]) }}" class="needs-validation" novalidate>
                                @method('PATCH')

                                @csrf

                                <div class="mb-3 col">

                                    <div class="mb-3">
                                        <label for="inspector_id" class="form-label">Inspector</label>

                                        <select class="form-select" name="inspector_id" id="inspector_id" required>
                                            <option selected disabled value="">Seleccione</option>
                                            @foreach ($inspectors as $inspector)
                                            <option value="{{ $inspector->id }}">{{$inspector->nombre . ' ' . $inspector->apellido}}</option>
                                            @endforeach
                                        </select>

                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>

                                    <input type="submit" value="Asignar" class="btn btn-primary my-2" />
                                </div>
                            </form>


                        </div>

                        @endif
                    </div>
                </div>
            </div>

            <!-- MODAL VER Concepto -->
             @if($inspection->concept && $inspection->concept->isNotEmpty())
            <div class="modal fade" id="modalVerConcepto{{$inspection->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Concepto de la inspección</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="modal-custom">
                                <!-- Tabs para dividir las secciones -->
                                <ul class="nav nav-tabs" id="conceptTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="tab-establecimiento{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-establecimiento{{$inspection->id}}" type="button" role="tab" aria-controls="section-establecimiento{{$inspection->id}}" aria-selected="true">Información del Establecimiento</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-construccion{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-construccion{{$inspection->id}}" type="button" role="tab" aria-controls="section-construccion{{$inspection->id}}" aria-selected="false">Características de la Construcción</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-incendios{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-incendios{{$inspection->id}}" type="button" role="tab" aria-controls="section-incendios{{$inspection->id}}" aria-selected="false">Equipos para Extinción de Incendios</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-auxilios{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-auxilios{{$inspection->id}}" type="button" role="tab" aria-controls="section-auxilios{{$inspection->id}}" aria-selected="false">Equipos de Primeros Auxilios</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-rutas{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-rutas{{$inspection->id}}" type="button" role="tab" aria-controls="section-rutas{{$inspection->id}}" aria-selected="false">Rutas y Senderos Evacuación</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-iluminacion{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-iluminacion{{$inspection->id}}" type="button" role="tab" aria-controls="section-iluminacion{{$inspection->id}}" aria-selected="false">Sistema Iluminación Emergencia</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-electrico{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-electrico{{$inspection->id}}" type="button" role="tab" aria-controls="section-electrico{{$inspection->id}}" aria-selected="false">Condiciones Sistema Eléctrico</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-combustible{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-combustible{{$inspection->id}}" type="button" role="tab" aria-controls="section-combustible{{$inspection->id}}" aria-selected="false">Almacenamiento Combustibles</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-otros{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-otros{{$inspection->id}}" type="button" role="tab" aria-controls="section-otros{{$inspection->id}}" aria-selected="false">Otros</button>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content" id="conceptTabsContent{{$inspection->id}}">
                                <!-- Sección 1: Información del establecimiento -->
                                <div class="tab-pane fade show active" id="section-establecimiento{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-establecimiento{{$inspection->id}}">
                                    <br>
                                    <h5 class="mt-3">Información de la Empresa</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Razón Social:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->first()->razon_social}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Nombre del Establecimiento:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->nombre_establecimiento}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>NIT:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->nit}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Email:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->email}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Teléfono:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->telefono}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Dirección:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->direccion}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Barrio:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->barrio}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Representante Legal:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->representante_legal}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Cédula del Representante:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->cedula_representante}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Actividad Comercial:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->actividad_comercial}}" readonly>
                                        </div>
                                        <!-- Más inputs de la tabla companies -->
                                    </div>
                                    <br>
                                    <h5 class="mt-3">Información del Establecimiento</h5>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label>Número de Pisos:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->info_establecimiento->first()->num_pisos}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Horario de Funcionamiento:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->horario_funcionamiento}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Carga Ocupacional Fija:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->info_establecimiento->first()->carga_ocupacional_fija}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Carga Ocupacional Flotante:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->info_establecimiento->first()->carga_ocupacional_flotante}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Carga Total:</label>
                                            <input type="text" class="form-control" value="{{$inspection->company->info_establecimiento->first()->carga_ocupacional_fija + $inspection->company->info_establecimiento->first()->carga_ocupacional_flotante }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección 2: Características de la Construcción -->
                                <div class="tab-pane fade" id="section-construccion{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-construccion{{$inspection->id}}">
                                    <br>
                                    <h5 class="mt-3">Características de la Construcción</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Año de Construcción:</label>
                                            <input type="text" value="{{ $inspection->concept->first()->construccion->anio_construccion }}" class="form-control" readonly>
                                        </div>
                                        <br>
                                        <div class="col-md-6">
                                            <label>¿Se aplicó la NRS10 apartados J y K?:</label>
                                            <div>
                                                <input class="form-control" type="text" value="{{ optional($inspection->concept->first()->construccion)->nrs === 1 ? 'Sí' : (optional($inspection->concept->first()->construccion)->nrs === 0 ? 'No' : 'No aplica') }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <br>
                                            <label>¿La empresa aplica el Sistema de Gestión de la Seguridad y Salud en el trabajo? :</label>
                                            <div>
                                                <input class="form-control" type="text" value="{{$inspection->concept->first()->construccion->sst === 1 ? 'Sí' : ($inspection->concept->first()->construccion->sst === 0 ? 'No' : 'No aplica') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección 3: Equipos para extinción de incendios -->
                                <div class="tab-pane fade" id="section-incendios{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-incendios{{$inspection->id}}">
                                    <br>
                                    <h5 class="mt-3">Equipos para Extinción de Incendios</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Sistema Automático:</label>
                                            <div>
                                                <input class="form-control" type="text" value="{{ optional($inspection->concept->first()->equipo_incendio)->sistema_automatico === 1 ? 'Sí' : (optional($inspection->concept->first()->equipo_incendio)->sistema_automatico === 0 ? 'No' : 'No aplica') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tipo De Sistema Automático:</label>
                                            <input type="text" class="form-control" value="{{$inspection->concept->first()->equipo_incendio->tipo_sistema}}" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Observaciones Sistema Automático: </label>
                                            <textarea class="form-control" rows="3" readonly>{{$inspection->concept->first()->equipo_incendio->observaciones_sa}}</textarea>
                                        </div>

                                        <br>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Red Contra Incendios:</label>
                                            <div>
                                                <input class="form-control" type="text" value="{{ optional($inspection->concept->first()->equipo_incendio)->red_contra_incendios === 1 ? 'Sí' : (optional($inspection->concept->first()->equipo_incendio)->red_contra_incendios === 0 ? 'No' : 'No aplica') }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Hidrantes:</label>
                                            <input class="form-control" type="text" value="{{ optional($inspection->concept->first()->equipo_incendio)->hidrantes === 1 ? 'Sí' : (optional($inspection->concept->first()->equipo_incendio)->hidrantes === 0 ? 'No' : 'No aplica') }}" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Distancia :</label>
                                            <input type="number" value="{{$inspection->concept->first()->equipo_incendio->distancia}}" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Tipo De Hidrantes :</label>
                                            <input type="text" value="{{$inspection->concept->first()->equipo_incendio->tipo_hidrante}}" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Observaciones de Red Contra Incendio e Hidrantes:</label>
                                            <textarea class="form-control" rows="3" readonly>{{$inspection->concept->first()->equipo_incendio->observaciones_hyr}}</textarea>
                                        </div>
                                    </div>
                                    <br>

                                    <h5 class="mt-3">Extintores</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>¿El personal ha recibido capacitación en prevención de incendios y manejo de extintores?</label>
                                            <div>
                                                <input class="form-control" type="text" value="{{ optional($inspection->concept->first()->equipo_incendio)->capacitacion === 1 ? 'Sí' : (optional($inspection->concept->first()->equipo_incendio)->capacitacion === 0 ? 'No' : 'No aplica') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Observaciones de los Extintores: </label>
                                            <textarea class="form-control" rows="3" readonly>{{$inspection->concept->first()->equipo_incendio->observaciones}}</textarea>
                                        </div>
                                    </div>

                                    <!---extintores dinamicos--->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="row" id="extintores-container-{{$inspection->id}}" data-inspection-id="{{$inspection->id}}">

                                                @foreach($inspection->concept->first()->equipo_incendio->extintor_sistema_incendio as $index => $extintor)

                                                <div class="col-md-6">
                                                    <div class="extintor-item border p-4 mb-4 rounded position-relative">
                                                        <h6>Extintor {{ (int)$index + 1 }}</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="tipo_{{$inspection->id}}_{{$index}}">Tipo de Extintor:</label>
                                                                <input type="text"
                                                                    class="form-control-plaintext"
                                                                    id="tipo_{{$inspection->id}}_{{$index}}"
                                                                    value="{{ $extintor->tipo_extintor->nombre . ' - ' . $extintor->tipo_extintor->contenido }}"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="empresa_{{$inspection->id}}_{{$index}}">Empresa Recarga:</label>
                                                                <input type="text"
                                                                    class="form-control-plaintext"
                                                                    id="empresa_{{$inspection->id}}_{{$index}}"
                                                                    value="{{ $extintor->empresa_recarga }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <label for="recarga_{{$inspection->id}}_{{$index}}">Fecha de Recarga:</label>
                                                                <input type="text"
                                                                    class="form-control-plaintext"
                                                                    id="recarga_{{$inspection->id}}_{{$index}}"
                                                                    value="{{ $extintor->fecha_recarga }}"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="vencimiento_{{$inspection->id}}_{{$index}}">Vencimiento:</label>
                                                                <input type="text"
                                                                    class="form-control-plaintext"
                                                                    id="vencimiento_{{$inspection->id}}_{{$index}}"
                                                                    value="{{ $extintor->fecha_vencimiento }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <label for="cantidad_{{$inspection->id}}_{{$index}}">Cantidad:</label>
                                                                <input type="text"
                                                                    class="form-control-plaintext"
                                                                    id="cantidad_{{$inspection->id}}_{{$index}}"
                                                                    value="{{ $extintor->cantidad }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <!----Fin extintores------->

                                </div>


                                <!-- Sección 4: Equipos de Primeros Auxilios -->
                                <div class="tab-pane fade" id="section-auxilios{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-auxilios{{$inspection->id}}">
                                    <h5 class="mt-3">Equipos Para Primeros Auxilios</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Observaciones Botiquin:</label>
                                            <div>
                                                <textarea class="form-control" rows="3">{{$inspection->concept->first()->primeros_auxilios->observaciones}}</textarea>
                                            </div>
                                        </div>
                                        <!------ Botiquines dinámicos------>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="row" id="botiquin-container{{$inspection->id}}" data-inspection-id="{{$inspection->id}}">
                                                    @foreach($inspection->concept->first()->primeros_auxilios->botiquin as $index => $botiquin)
                                                    <div class="col-md-6">
                                                        <div class="botiquin-item border p-4 mb-4 rounded position-relative">
                                                            <h6>Botiquín</h6>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="kit_{{$botiquin->id}}_1">Tipo de Botiquín:</label>
                                                                    <input type="text"
                                                                        class="form-control-plaintext"
                                                                        id="recarga_{{$botiquin->id}}_{{$index}}"
                                                                        value="{{ $botiquin->tipo_botiquin->first()->descripcion }}"
                                                                        readonly>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="cantidad_{{$inspection->id}}_1">Cantidad:</label>
                                                                    <input type="number" class="form-control" value="{{$botiquin->cantidad}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!--------Fin de botiquines dinámicos----->
                                    </div>
                                    <h5 class="mt-3">Equipo Para Traslado de Lesionados</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>¿Dispone de camilla para transporte de lesionados?</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->primeros_auxilios)->camilla === 1 ? 'Sí' : (optional($inspection->concept->first()->primeros_auxilios)->camilla === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tipo Camilla :</label>
                                            <input type="text" class="form-control" value="{{$inspection->concept->first()->primeros_auxilios->tipo_camilla}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>¿Dispone de inmovilizadores cervicales?</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->primeros_auxilios)->inmovilizador_cervical === 1 ? 'Sí' : (optional($inspection->concept->first()->primeros_auxilios)->inmovilizador_cervical === 0 ? 'No' : 'No aplica') }}">

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tipo Inmovilizador cervicales :</label>
                                            <input type="text" class="form-control" value="{{$inspection->concept->first()->primeros_auxilios->tipo_inm_cervical}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>¿Dispone de inmovilizadores de extremidades?</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->primeros_auxilios)->inmovilizador_extremidades === 1 ? 'Sí' : (optional($inspection->concept->first()->primeros_auxilios)->inmovilizador_extremidades === 0 ? 'No' : 'No aplica') }}">

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tipo Inmovilizador extremidades :</label>
                                            <input type="text" value="{{$inspection->concept->first()->primeros_auxilios->tipo_inm_extremidades}}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label>¿El personal tiene capacitación en atención de primeros auxilios?</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->primeros_auxilios)->capacitacion_primeros_auxilios === 1 ? 'Sí' : (optional($inspection->concept->first()->primeros_auxilios)->capacitacion_primeros_auxilios === 0 ? 'No' : 'No aplica') }}">

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tipo de Capacitación :</label>
                                            <input type="text" value="{{$inspection->concept->first()->primeros_auxilios->tipo_capacitacion}}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Observaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->primeros_auxilios->observaciones}}</textarea>
                                        </div>

                                    </div>
                                </div>

                                <!-- Sección 5:  RUTAS O SENDEROS ASIGNADOS PARA EVACUACIÓN -->
                                <div class="tab-pane fade" id="section-rutas{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-rutas{{$inspection->id}}">
                                    <br>
                                    <h5 class="mt-3">Rutas o Senderos Asignados Para Evacuación</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Rutas de Evacuación:</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->ruta_evacuacion)->ruta_evacuacion === 1 ? 'Sí' : (optional($inspection->concept->first()->ruta_evacuacion)->ruta_evacuacion === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Salidas de Emergencia:</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->ruta_evacuacion)->salidas_emergencia === 1 ? 'Sí' : (optional($inspection->concept->first()->ruta_evacuacion)->salidas_emergencia === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Observaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->ruta_evacuacion->observaciones}}</textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <h5 class="mt-3">Estado de las Escaleras</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>¿Dispone de escaleras?</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->ruta_evacuacion)->escaleras === 1 ? 'Sí' : (optional($inspection->concept->first()->ruta_evacuacion)->escaleras === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Condición de las Escaleras :</label>
                                            <input type="text" class="form-control" value="{{$inspection->concept->first()->ruta_evacuacion->condicion_escaleras}}">

                                        </div>

                                        <div class="col-md-6">
                                            <label>¿Se encuentran señalizadas??</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->ruta_evacuacion)->señalizadas === 1 ? 'Sí' : (optional($inspection->concept->first()->ruta_evacuacion)->señalizadas === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Condición de la Señalización :</label>
                                            <input type="text" class="form-control" value="{{$inspection->concept->first()->ruta_evacuacion->condicion_señalizadas}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>¿Disponen de barandas?</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->ruta_evacuacion)->barandas === 1 ? 'Sí' : (optional($inspection->concept->first()->ruta_evacuacion)->barandas === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Condición de las Barandas:</label>
                                            <input type="text" class="form-control" value="{{$inspection->concept->first()->ruta_evacuacion->condicion_barandas}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>¿Poseen cinta antideslizante?</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->ruta_evacuacion)->antideslizante === 1 ? 'Sí' : (optional($inspection->concept->first()->ruta_evacuacion)->antideslizante === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Condición de los Antideslizantes :</label>
                                            <input type="text" class="form-control" value="{{$inspection->concept->first()->ruta_evacuacion->condicion_antideslizante}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Observaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->ruta_evacuacion->observaciones_escaleras}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!--- Sección 6: SISTEMA DE ILUMINACIÓN DE EMERGENCIA --->
                                <div class="tab-pane fade" id="section-iluminacion{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-iluminacion{{$inspection->id}}">
                                    <br>
                                    <h5 class="mt-3">Sistema de Iluminación de Emergencia</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>¿Poseen sistema de iluminación de emergencias?</label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->sistema_iluminacion)->sistema_iluminacion === 1 ? 'Sí' : (optional($inspection->concept->first()->sistema_iluminacion)->sistema_iluminacion === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Fecha de última prueba:</label>
                                            <input type="date" class="form-control" value="{{$inspection->concept->first()->sistema_iluminacion->fecha_ultima_prueba}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Observaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->sistema_iluminacion->observaciones}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!----Sección 7: Condicones del Sistema Electrico----------------------------->
                                <div class="tab-pane fade" id="section-electrico{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-electrico{{$inspection->id}}">
                                    <br>
                                    <h5 class="mt-3">Condiciones del Sistema Eléctrico</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Dispone de caja de distribución con breker: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->sistema_electrico)->caja_distribucion_breker === 1 ? 'Sí' : (optional($inspection->concept->first()->sistema_electrico)->caja_distribucion_breker === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>¿Se Encuentra Identificados? :</label>
                                            <input type="text" value="{{$inspection->concept->first()->sistema_electrico->encuentra_identificados}}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label>El sistema de cableado se encuentra protegido y sin empalme visibles: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->sistema_electrico)->sistema_cableado_protegido === 1 ? 'Sí' : (optional($inspection->concept->first()->sistema_electrico)->sistema_cableado_protegido === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Los tomas corrientes presentan evidencias de corto circuito: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->sistema_electrico)->toma_corriente_corto === 1 ? 'Sí' : (optional($inspection->concept->first()->sistema_electrico)->toma_corriente_corto === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Los tomas corrientes presentan sobrecarga: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->sistema_electrico)->toma_corriente_sobrecarga === 1 ? 'Sí' : (optional($inspection->concept->first()->sistema_electrico)->toma_corriente_sobrecarga === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Se Encuentra Identificado El Voltaje </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->sistema_electrico)->identificacion_voltaje === 1 ? 'Sí' : (optional($inspection->concept->first()->sistema_electrico)->identificacion_voltaje === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Los cajetines se encuentran con su tapa y asegurados : </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->sistema_electrico)->cajetines_asegurados === 1 ? 'Sí' : (optional($inspection->concept->first()->sistema_electrico)->cajetines_asegurados === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Dispone De Botón De Parada De Emergencia: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->sistema_electrico)->boton_emergencia === 1 ? 'Sí' : (optional($inspection->concept->first()->sistema_electrico)->boton_emergencia === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Se Relizan Mantenimiento Preventivos a Los Equipos Electrónicos: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->sistema_electrico)->mantenimiento_preventivo === 1 ? 'Sí' : (optional($inspection->concept->first()->sistema_electrico)->mantenimiento_preventivo === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Periodicidad de los Mantenimintos :</label>
                                            <input type="text" value="{{$inspection->concept->first()->sistema_electrico->periodicidad}}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Los Arreglos y Mantenimientos Eléctricos lo Hacen Personal Idoneo: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->sistema_electrico)->personal_idoneo === 1 ? 'Sí' : (optional($inspection->concept->first()->sistema_electrico)->personal_idoneo === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Observaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->sistema_electrico->observaciones}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!----Sección 8: Almacenamiento de Combustible----------------------------->
                                <div class="tab-pane fade" id="section-combustible{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-combustible{{$inspection->id}}">
                                    <br>
                                    <h5 class="mt-3">Almacenamiento De Combustible</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Material Sólido Ordinario: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->almacenamiento)->material_solido_ordinario === 1 ? 'Sí' : (optional($inspection->concept->first()->almacenamiento)->material_solido_ordinario === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Zona de Almacenamiento Retirada de Fuentes de Calor: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->almacenamiento)->zona_almacenamiento_1 === 1 ? 'Sí' : (optional($inspection->concept->first()->almacenamiento)->zona_almacenamiento_1 === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Observaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->almacenamiento->observaciones_1}}</textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Cantidad:</label>
                                            <input type="number" class="form-control" value="{{$inspection->concept->first()->almacenamiento->cantidad_1}}">
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Material liquido inflamable (derivados del petróleo) : </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->almacenamiento)->material_liquido_inflamable === 1 ? 'Sí' : (optional($inspection->concept->first()->almacenamiento)->material_liquido_inflamable === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Zona de Almacenamiento Retirada de Fuentes de Calor: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->almacenamiento)->zona_almacenamiento_2 === 1 ? 'Sí' : (optional($inspection->concept->first()->almacenamiento)->zona_almacenamiento_2 === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Observaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->almacenamiento->observaciones_2}}</textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Cantidad:</label>
                                            <input type="number" class="form-control" value="{{$inspection->concept->first()->almacenamiento->cantidad_2}}">
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Material gaseoso inflamable : </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->almacenamiento)->material_gaseoso_inflamable === 1 ? 'Sí' : (optional($inspection->concept->first()->almacenamiento)->material_gaseoso_inflamable === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Zona de Almacenamiento Retirada de Fuentes de Calor: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->almacenamiento)->zona_almacenamiento_3 === 1 ? 'Sí' : (optional($inspection->concept->first()->almacenamiento)->zona_almacenamiento_3 === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Observaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->almacenamiento->observaciones_3}}</textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Cantidad:</label>
                                            <input type="number" class="form-control" value="{{$inspection->concept->first()->almacenamiento->cantidad_3}}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Otros Químicos Almacenados: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->almacenamiento)->otros_quimicos === 1 ? 'Sí' : (optional($inspection->concept->first()->almacenamiento)->otros_quimicos === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Zona de Almacenamiento Retirada de Fuentes de Calor: </label>
                                            <div>
                                                <input type="text" class="form-control" value="{{optional($inspection->concept->first()->almacenamiento)->zona_almacenamiento_4 === 1 ? 'Sí' : (optional($inspection->concept->first()->almacenamiento)->zona_almacenamiento_4 === 0 ? 'No' : 'No aplica') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Observaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->almacenamiento->observaciones_4}}</textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Cantidad:</label>
                                            <input type="number" class="form-control" value="{{$inspection->concept->first()->almacenamiento->cantidad_4}}">
                                        </div>
                                    </div>
                                </div>

                                <!----Sección 9: Otras condiciones de riesgo----------->
                                <div class="tab-pane fade" id="section-otros{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-otros{{$inspection->id}}">
                                    <br>
                                    <h5 class="mt-3">Otras Condiciones Que Se Pueden Convertir De Riesgo Para Los Ocupantes</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Condición de riesgo:</label>
                                            <input type="text" value="{{$inspection->concept->first()->otras_condiciones->condicion}}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Observaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->otras_condiciones->observacion}}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Recomendaciones :</label>
                                            <textarea class="form-control" rows="3">{{$inspection->concept->first()->recomendaciones}}</textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <h5 class="mt-3">Condición General del Concepto: </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-5">
                                                <!-----Fotos---->

                                                <label>Fotos de Evidencia:</label>
                                                <div class="d-flex flex-wrap gap-2 mt-3">
                                                    @php
                                                    // Ruta de la carpeta donde están las imágenes
                                                    $rutaCarpeta = 'storage/documentos/empresa-' . $inspection->company->id . '/concepto-' . $inspection->concept->first()->id;
                                                    $imagenes = glob(public_path($rutaCarpeta . '/*.{jpg,jpeg,png,gif}'), GLOB_BRACE);
                                                    @endphp

                                                    <!-- Mostrar las rutas para depuración -->
                                                    @foreach ($imagenes as $imagen)
                                                    <a href="{{ str_replace(public_path(), '', $imagen) }}" target="_blank" download>
                                                        <img src="{{ str_replace(public_path(), '', $imagen) }}" alt="Evidencia" class="img-thumbnail" style="width: 200px; height: 200px; object-fit: cover;">
                                                    </a>
                                                </div>

                                                @endforeach
                                            </div>

                                            <!-----fin fotos--->

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>¿Favorable?</label>
                                        <div>
                                            <input type="text" class="form-control" value="{{ $inspection->concept->first()?->favorable === 1 ? 'Sí' : ($inspection->concept->first()?->favorable === 0 ? 'No' : 'No aplica') }}">
                                        </div>
                                    </div>
                                </div>
                            </div><!-- fin tab content--->
                        </div><!--div que cierra el modal body-->
                    </div><!--div que cierra el modal content-->
                </div><!--div que cierra el modal  dialog-->
            </div><!--div que cierra el modal-->
            @endif
            <!----fin de modal ver detalle-->




            @endforeach
        </tbody>
    </table>
</div>



<script>
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>


@endsection