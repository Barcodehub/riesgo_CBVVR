@extends('inspector.dashboard')

@section('content')

<div class="w-full border p-4 m-4">

    @if (session('success'))
    <h6 class="alert alert-success">{{ session('success') }}</h6>
    @endif

    <head>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>

    <h4 class="mb-4">Inspecciones Asignadas: </h4>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre Establecimiento</th>
                <th scope="col">Fecha Solicitud</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>

            @if ($inspections->isEmpty())
            <tr>
                <td colspan="5" class="text-center">
                    No hay inspecciones
                </td>
            </tr>
            @endif
            @foreach ($inspections as $inspection)
            <tr>
                <td>{{$inspection->id}}</td>
                <td>{{$inspection->company->nombre_establecimiento}}</td>
                <td>{{$inspection->fecha_solicitud}}</td>
                <td>{{$inspection->estado}}</td>
                <td>
                    <div class="flex justify-between items-center gap-2">
                        @if ($inspection->estado == 'SOLICITADA' || $inspection->estado == 'COTIZADA')
                        <a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#conceptoModal{{ $inspection->id }}">Dar Concepto <i class="ps-2 fa-solid fa-plus"></i></a>
                        @endif
                    </div>
                </td>
            </tr>
           

            <!-- MODAL CREAR CONCEPTO -->


            <!-- Modal -->
            <div class="modal fade" id="conceptoModal{{ $inspection->id }}" tabindex="-1"
                aria-labelledby="conceptModalLabel" aria-hidden="true" data-inspection-id="{{ $inspection->id }}" data-url="{{ route('inspector.getExtinguishers') }}" data-link="{{ route('inspector.getBotiquines') }}">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('inspector.store', ['inspection' => $inspection->id]) }}" class="needs-validation"  enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="conceptModalLabel">Registrar Concepto</h5>
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
                                <!-- Contenido de cada sección -->
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
                                                <input type="text" name="anio_construcción_{{$inspection->id}}" id="anio_construccion_{{$inspection->id}}" class="form-control">
                                            </div>
                                            <br>
                                            <div class="col-md-6">
                                                <label>¿Se aplicó la NRS10 apartados J y K?:</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="nrs_{{$inspection->id}}" id="nrs1_{{$inspection->id}}" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="nrs_{{$inspection->id}}" id="nrs2_{{$inspection->id}}" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <br>
                                                <label>¿La empresa aplica el Sistema de Gestión de la Seguridad y Salud en el trabajo? :</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sst_{{$inspection->id}}" id="sst1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sst_{{$inspection->id}}" id="sst2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sst_{{$inspection->id}}" id="sst3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
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
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sistema_automatico_{{$inspection->id}}" id="sistema_automatico_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sistema_automatico_{{$inspection->id}}" id="sistema_automatico_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sistema_automatico_{{$inspection->id}}" id="sistema_automatico_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo De Sistema Automático:</label>
                                                <input type="text" name="tipo_sistema_{{$inspection->id}}" id="tipo_sistema" class="form-control">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Observaciones Sistema Automático: </label>
                                                <textarea class="form-control" name="observaciones_S_A_{{$inspection->id}}" id="observaciones_S_A" rows="3"></textarea>
                                            </div>

                                            <br>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Red Contra Incendios:</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="red_incendios_{{$inspection->id}}" id="red_incendios_1_{{$inspection->id}}" value="1">
                                                        <label class="form-check-label" for="red_incendios_1_{{$inspection->id}}">
                                                            Sí
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="red_incendios_{{$inspection->id}}" id="red_incendios_2_{{$inspection->id}}" value="0">
                                                        <label class="form-check-label" for="red_incendios_2_{{$inspection->id}}">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="red_incendios_{{$inspection->id}}" id="red_incendios_3_{{$inspection->id}}" value="null">
                                                        <label class="form-check-label" for="red_incendios_3_{{$inspection->id}}">
                                                            No Aplica
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Hidrantes:</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="hidrantes_{{$inspection->id}}" id="hidrantes_{{$inspection->id}}" value="1">
                                                        <label class="form-check-label" for="red_incendios_1_{{$inspection->id}}">
                                                            Sí
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="hidrantes_{{$inspection->id}}" id="hidrantes_{{$inspection->id}}" value="0">
                                                        <label class="form-check-label" for="red_incendios_2_{{$inspection->id}}">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="hidrantes_{{$inspection->id}}" id="hidrantes_{{$inspection->id}}" value="null">
                                                        <label class="form-check-label" for="red_incendios_3_{{$inspection->id}}">
                                                            No Aplica
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Distancia :</label>
                                                <input type="number" name="distancia_hidrante_{{$inspection->id}}" id="distancia_hidrante_{{$inspection->id}}" class="form-control">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Tipo De Hidrantes :</label>
                                                <input type="text" name="tipo_hidrante_{{$inspection->id}}" id="tipo_hidrante_{{$inspection->id}}" class="form-control">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Observaciones de Red Contra Incendio e Hidrantes:</label>
                                                <textarea class="form-control" name="observaciones_red_{{$inspection->id}}" id="observaciones_red_{{$inspection->id}}" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <br>

                                        <h5 class="mt-3">Extintores</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>¿El personal ha recibido capacitación en prevención de incendios y manejo de extintores?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="capacitacion_{{$inspection->id}}" id="capacitacion_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="capacitacion_{{$inspection->id}}" id="capacitacion_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones de los Extintores: </label>
                                                <textarea class="form-control" name="observaciones_extintores_{{$inspection->id}}" id="observaciones_extintores_{{$inspection->id}}" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <!------Extintores dinamicos------->
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="row" id="extintores-container-{{$inspection->id}}" data-inspection-id="{{$inspection->id}}">
                                                    <div class="col-md-6">
                                                        <div class="extintor-item border p-4 mb-4 rounded position-relative">
                                                            <h6>Extintor</h6>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="tipo_{{$inspection->id}}">Tipo de Extintor:</label>
                                                                    <select class="form-select" id="tipo_{{$inspection->id}}" name="tipo_{{$inspection->id}}_1">
                                                                        <!-- Los tipos de extintores se llenarán dinámicamente -->
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Empresa Recarga:</label>
                                                                    <input type="text" class="form-control" id="empresa_{{$inspection->id}}_1" name="empresa_{{$inspection->id}}_1">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="col-md-6">
                                                                    <label>Fecha de Recarga:</label>
                                                                    <input type="date" class="form-control" id="recarga_{{$inspection->id}}_1" name="recarga_{{$inspection->id}}_1">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Vencimiento:</label>
                                                                    <input type="date" class="form-control" id="vencimiento_{{$inspection->id}}_1" name="vencimiento_{{$inspection->id}}_1">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="col-md-6">
                                                                    <label>Cantidad:</label>
                                                                    <input type="number" class="form-control" id="cantidad_{{$inspection->id}}_1" name="cantidad_{{$inspection->id}}_1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Botón para añadir extintores -->
                                                <button type="button" class="btn btn-outline-primary mt-3" id="add-extintor-{{$inspection->id}}">Añadir Extintor</button>
                                            </div>
                                        </div>
                                        <br>
                                        <!-----Hasta Aqui los campos dinamicos--->
                                    </div>

                                    <!-- Sección 4: Equipos de Primeros Auxilios -->
                                    <div class="tab-pane fade" id="section-auxilios{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-auxilios{{$inspection->id}}">
                                        <h5 class="mt-3">Equipos Para Primeros Auxilios</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Botiquín:</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="botiquin_{{$inspection->id}}" id="botiquin_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="botiquin_{{$inspection->id}}" id="botiquin_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones Botiquin:</label>
                                                <div>
                                                    <textarea class="form-control" name="observaciones_botiquin_{{$inspection->id}}" id="observaciones_botiquin_{{$inspection->id}}" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <!------ Botiquines dinámicos------>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="row" id="botiquin-container{{$inspection->id}}" data-inspection-id="{{$inspection->id}}">
                                                        <div class="col-md-6">
                                                            <div class="botiquin-item border p-4 mb-4 rounded position-relative">
                                                                <h6>Botiquín</h6>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="kit_{{$inspection->id}}_1">Tipo de Botiquín:</label>
                                                                        <select class="form-select" id="kit_{{$inspection->id}}_1" name="kit_{{$inspection->id}}_1">
                                                                            <!-- Los tipos de botiquín se llenarán dinámicamente -->
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="cantidad_{{$inspection->id}}_1">Cantidad:</label>
                                                                        <input type="number" class="form-control" id="cantidad_{{$inspection->id}}_1" name="cantidad_{{$inspection->id}}_1">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Botón para añadir botiquines -->
                                                    <button type="button" class="btn btn-outline-primary mt-3" id="add-botiquin-{{$inspection->id}}">Añadir Botiquín</button>
                                                </div>
                                            </div>

                                            <!--------Fin de botiquines dinámicos----->
                                        </div>
                                        <h5 class="mt-3">Equipo Para Traslado de Lesionados</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>¿Dispone de camilla para transporte de lesionados?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="camilla_{{$inspection->id}}" id="camilla_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="camilla_{{$inspection->id}}" id="camilla_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="camilla_{{$inspection->id}}" id="camilla_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo Camilla :</label>
                                                <input type="text" name="tipo_camilla_{{$inspection->id}}" id="tipo_camilla" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Dispone de inmovilizadores cervicales?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cervicales_{{$inspection->id}}" id="cervicales_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cervicales_{{$inspection->id}}" id="cervicales_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cervicales_{{$inspection->id}}" id="cervicales_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo Inmovilizador cervicales :</label>
                                                <input type="text" name="tipo_cervicales_{{$inspection->id}}" id="tipo_camilla" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Dispone de inmovilizadores de extremidades?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="extremidades_{{$inspection->id}}" id="extremidades_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="extremidades_{{$inspection->id}}" id="extremidades_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="extremidades_{{$inspection->id}}" id="extremidades_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo Inmovilizador extremidades :</label>
                                                <input type="text" name="tipo_extremidades_{{$inspection->id}}" id="tipo_camilla" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿El personal tiene capacitación en atención de primeros auxilios?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="capacitacion_PA_{{$inspection->id}}" id="capacitacion_PA_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="capacitacion_PA_{{$inspection->id}}" id="capacitacion_PA_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="capacitacion_PA_{{$inspection->id}}" id="capacitacion_PA_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo de Capacitación :</label>
                                                <input type="text" name="tipo_capacitacion_PA_{{$inspection->id}}" id="tipo_camilla" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_equipo_lesionados_{{$inspection->id}}" id="observaciones_equipo_lesionados" rows="3"></textarea>
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
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="ruta_{{$inspection->id}}" id="ruta_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="ruta_{{$inspection->id}}" id="ruta_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="ruta_{{$inspection->id}}" id="ruta_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Salidas de Emergencia:</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="salida_{{$inspection->id}}" id="salida_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="salida_{{$inspection->id}}" id="salida_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="salida_{{$inspection->id}}" id="salida_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_salida_emergencia_{{$inspection->id}}" id="observaciones_salida_emergencia" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <h5 class="mt-3">Estado de las Escaleras</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>¿Dispone de escaleras?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="escaleras_{{$inspection->id}}" id="escaleras_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="escaleras_{{$inspection->id}}" id="escaleras_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="escaleras_{{$inspection->id}}" id="escaleras_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Condición de las Escaleras :</label>
                                                <select name="condicion_escaleras_{{$inspection->id}}" id="condicion_escaleras" class="form-select">
                                                    <option value="bueno">Bueno</option>
                                                    <option value="regular">Regular</option>
                                                    <option value="malo">Malo</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label>¿Se encuentran señalizadas??</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="señalizadas_{{$inspection->id}}" id="señalizadas_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="señalizadas_{{$inspection->id}}" id="señalizadas_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="señalizadas_{{$inspection->id}}" id="señalizadas_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Condición de la Señalización :</label>
                                                <select name="condicion_señalizacion_{{$inspection->id}}" id="condicion_señalizacion" class="form-select">
                                                    <option value="bueno">Bueno</option>
                                                    <option value="regular">Regular</option>
                                                    <option value="malo">Malo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Disponen de barandas?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="barandas_{{$inspection->id}}" id="barandas_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="barandas_{{$inspection->id}}" id="barandas_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="barandas_{{$inspection->id}}" id="barandas_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Condición de las Barandas:</label>
                                                <select name="condicion_barandas_{{$inspection->id}}" id="condicion_barandas_{{$inspection->id}}" class="form-select">
                                                    <option value="bueno">Bueno</option>
                                                    <option value="regular">Regular</option>
                                                    <option value="malo">Malo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Poseen cinta antideslizante?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="antideslizante_{{$inspection->id}}" id="antideslizante_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="antideslizante_{{$inspection->id}}" id="antideslizante_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="antideslizante_{{$inspection->id}}" id="antideslizante_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Condición de los Antideslizantes :</label>
                                                <select name="condicion_antideslizantes_{{$inspection->id}}" id="condicion_antideslizantes" class="form-select">
                                                    <option value="bueno">Bueno</option>
                                                    <option value="regular">Regular</option>
                                                    <option value="malo">Malo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_antideslizante_{{$inspection->id}}" id="observaciones_antideslizante" rows="3"></textarea>
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
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="iluminacion_emergencia_{{$inspection->id}}" id="iluminacion_emergencia_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="iluminacion_emergencia_{{$inspection->id}}" id="iluminacion_emergencia_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="iluminacion_emergencia_{{$inspection->id}}" id="iluminacion_emergencia_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Fecha de última prueba:</label>
                                                <input type="date" class="form-control" id="fecha_ultima_prueba_{{$inspection->id}}" name="fecha_ultima_prueba_{{$inspection->id}}">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_iluminacion_emergencia_{{$inspection->id}}" id="observaciones_iluminiacion_emergencia" rows="3"></textarea>
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
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="breker_{{$inspection->id}}" id="breker_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="breker_{{$inspection->id}}" id="breker_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="breker_{{$inspection->id}}" id="breker_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Se Encuentra Identificados? :</label>
                                                <input type="text" name="identificados_{{$inspection->id}}" id="identificados" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>El sistema de cableado se encuentra protegido y sin empalme visibles: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="empalme_{{$inspection->id}}" id="empalme_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="empalme_{{$inspection->id}}" id="empalme_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Los tomas corrientes presentan evidencias de corto circuito: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="toma_corriente_{{$inspection->id}}" id="toma_corriente_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="toma_corriente_{{$inspection->id}}" id="toma_corriente_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Los tomas corrientes presentan sobrecarga: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sobrecarga_{{$inspection->id}}" id="sobrecarga_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sobrecarga_{{$inspection->id}}" id="sobrecarga_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Se Encuentra Identificado El Voltaje </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="voltaje_{{$inspection->id}}" id="voltaje_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="voltaje_{{$inspection->id}}" id="voltaje_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="voltaje_{{$inspection->id}}" id="voltaje_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Los cajetines se encuentran con su tapa y asegurados : </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cajetines_{{$inspection->id}}" id="cajetines_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cajetines_{{$inspection->id}}" id="cajetines_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Dispone De Botón De Parada De Emergencia: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="boton_{{$inspection->id}}" id="boton_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="boton_{{$inspection->id}}" id="boton_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Se Relizan Mantenimiento Preventivos a Los Equipos Electrónicos: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="mantenimiento_{{$inspection->id}}" id="mantenimiento_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="mantenimiento_{{$inspection->id}}" id="mantenimiento_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Periodicidad de los Mantenimintos :</label>
                                                <input type="text" name="periodicidad_{{$inspection->id}}" id="periodicidad" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Los Arreglos y Mantenimientos Eléctricos lo Hacen Personal Idoneo: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="personal_idoneo_{{$inspection->id}}" id="personal_idoneo_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="personal_idoneo_{{$inspection->id}}" id="personal_idoneo_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_sistema_electrico_{{$inspection->id}}" id="observaciones_sistema_electrico" rows="3"></textarea>
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
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="material_solido_{{$inspection->id}}" id="material_solido_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="material_solido_{{$inspection->id}}" id="material_solido_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Zona de Almacenamiento Retirada de Fuentes de Calor: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="Almacenamiento_solidos_{{$inspection->id}}" id="Almacenamiento_solidos_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="Almacenamiento_solidos_{{$inspection->id}}" id="Almacenamiento_solidos_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_solidos_{{$inspection->id}}" id="observaciones_solidos" rows="3"></textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Cantidad:</label>
                                                <input type="number" class="form-control" id="cantidad_solidos_{{$inspection->id}}" name="cantidad_solidos_{{$inspection->id}}">
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Material liquido inflamable (derivados del petróleo) : </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="material_liquido_{{$inspection->id}}" id="material_liquido_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="material_liquido_{{$inspection->id}}" id="material_liquido_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Zona de Almacenamiento Retirada de Fuentes de Calor: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="Almacenamiento_liquidos_{{$inspection->id}}" id="Almacenamiento_liquidos_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="Almacenamiento_liquidos_{{$inspection->id}}" id="Almacenamiento_liquidos_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_liquidos_{{$inspection->id}}" id="observaciones_liquidos" rows="3"></textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Cantidad:</label>
                                                <input type="number" class="form-control" id="cantidad_liquidos" name="cantidad_liquidos_{{$inspection->id}}">
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Material gaseoso inflamable : </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="material_gaseoso_{{$inspection->id}}" id="material_gaseoso_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="material_gaseoso_{{$inspection->id}}" id="material_gaseoso_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Zona de Almacenamiento Retirada de Fuentes de Calor: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="Almacenamiento_gaseoso_{{$inspection->id}}" id="Almacenamiento_gaseoso_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="Almacenamiento_gaseoso_{{$inspection->id}}" id="Almacenamiento_gaseoso_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_gaseoso_{{$inspection->id}}" id="observaciones_gaseoso" rows="3"></textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Cantidad:</label>
                                                <input type="number" class="form-control" id="cantidad_gaseoso" name="cantidad_gaseoso_{{$inspection->id}}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Otros Químicos Almacenados: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="quimico_{{$inspection->id}}" id="quimico_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="quimico_{{$inspection->id}}" id="quimico_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Zona de Almacenamiento Retirada de Fuentes de Calor: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="Almacenamiento_quimico_{{$inspection->id}}" id="Almacenamiento_quimico_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="Almacenamiento_quimico_{{$inspection->id}}" id="Almacenamiento_quimico_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_quimico_{{$inspection->id}}" id="observaciones_quimico" rows="3"></textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Cantidad:</label>
                                                <input type="number" class="form-control" id="cantidad_quimico_{{$inspection->id}}" name="cantidad_quimico_{{$inspection->id}}">
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
                                                <input type="text" name="otra_condicion_riesgo_{{$inspection->id}}" id="otra_condicion_riesgo_{{$inspection->id}}" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_otros_{{$inspection->id}}" id="observaciones_otros_{{$inspection->id}}" rows="3"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Recomendaciones :</label>
                                                <textarea class="form-control" name="recomendaciones_otros_{{$inspection->id}}" id="recomendaciones_otros_{{$inspection->id}}" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <h5 class="mt-3">Condición General del Concepto: </h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-5">
                                                    <label for="photos">Selecciona fotos de Evidencia:</label>
                                                    <input type="file" class="form-control" id="photos" name="photos_{{$inspection->id}}[]" accept="image/*" multiple>
                                                    <div id="preview"></div>
                                                    <div class="invalid-feedback">
                                                        Complete este campo.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Favorable?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="favorable_{{$inspection->id}}" id="favorable_si_{{$inspection->id}}" value="1">
                                                        <label class="form-check-label" for="favorable_si_{{$inspection->id}}">
                                                            Sí
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="favorable_{{$inspection->id}}" id="favorable_no_{{$inspection->id}}" value="0">
                                                        <label class="form-check-label" for="favorable_no_{{$inspection->id}}">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer del Modal -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </div><!---Div que cierra el modal body-->
                        </form>
                    </div><!--- div que cierra el modal content-->
                </div> <!-- div modal dialog--->
            </div><!--div que cierra el modal-->





            <!-----END CONCEPTO ---->
            @endforeach
        </tbody>
    </table>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/extintores.js') }}"></script>
<script src="{{ asset('js/botiquines.js') }}"></script>
<script src="{{ asset('js/imagenes.js') }}"></script>
<script>

</script>



@endsection