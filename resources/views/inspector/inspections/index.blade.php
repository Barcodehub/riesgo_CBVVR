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
                        <form method="POST" id="form{{$inspection->id}}" action="{{ route('inspector.store', [$inspection->id]) }}" class="needs-validation" novalidate>

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
                                            <button class="nav-link" id="tab-rutas{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-rutas{{$inspection->id}}" type="button" role="tab" aria-controls="section-rutas{{$inspection->id}}" aria-selected="false">Rutas y Senderos de Evacuación</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab-iluminacion{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-iluminacion{{$inspection->id}}" type="button" role="tab" aria-controls="section-iluminacion{{$inspection->id}}" aria-selected="false">Sistema de Iluminación de Emergencia</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab-electrico{{$inspection->id}}" data-bs-toggle="tab" data-bs-target="#section-electrico{{$inspection->id}}" type="button" role="tab" aria-controls="section-electrico{{$inspection->id}}" aria-selected="false">Condiciones del Sistema Eléctrico</button>
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
                                                <input type="text" class="form-control">
                                            </div>
                                            <br>
                                            <div class="col-md-6">
                                                <label>¿Se aplicó la NRS10 apartados J y K?:</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="nrs" id="nrs1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="nrs" id="nrs2" value="0">
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
                                                        <input class="form-check-input" type="radio" name="sst" id="sst1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sst" id="sst2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sst" id="sst3" value="null">
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
                                                        <input class="form-check-input" type="radio" name="sistema_automatico" id="sistema_automatico_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sistema_automatico" id="sistema_automatico_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sistema_automatico" id="sistema_automatico_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                            <br>
                                            <div class="col-md-6">
                                                <label>Tipo De Sistema Automático:</label>
                                                <input type="text" name="tipo_sistema" id="tipo_sistema" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones Sistema Automático: </label>
                                                <textarea class="form-control" name="observaciones_S_A" id="observaciones_S_A" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Red Contra Incendios:</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="red_incendios" id="red_incendios_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="red_incendios" id="red_incendios_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="red_incendios" id="red_incendios_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo De Red Contra Incendios:</label>
                                                <input type="text" name="tipo_red" id="tipo_red" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones de Red Contra Incendio: </label>
                                                <textarea class="form-control" name="observaciones_red" id="observaciones_red" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <h5 class="mt-3">Extintores</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>¿El personal ha recibido capacitación en prevención de incendios y manejo de extintores?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="capacitacion" id="capacitacion_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="capacitacion" id="capacitacion_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones de los Extintores: </label>
                                                <textarea class="form-control" name="observaciones_extintores" id="observaciones_extintores" rows="3"></textarea>
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
                                                                    <select class="form-select" id="tipo_{{$inspection->id}}" name="tipo_{{$inspection->id}}">
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
                                                        <input class="form-check-input" type="radio" name="botiquin" id="botiquin_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="botiquin" id="botiquin_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones Botiquin:</label>
                                                <div>
                                                    <textarea class="form-control" name="observaciones_botiquin" id="observaciones_botiquin" rows="3"></textarea>
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
                                                        <input class="form-check-input" type="radio" name="camilla" id="camilla_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="camilla" id="camilla_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="camilla" id="camilla_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo Camilla :</label>
                                                <input type="text" name="tipo_camilla" id="tipo_camilla" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Dispone de inmovilizadores cervicales?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cervicales" id="cervicales_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cervicales" id="cervicales_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cervicales" id="cervicales_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo Inmovilizador cervicales :</label>
                                                <input type="text" name="tipo_cervicales" id="tipo_camilla" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Dispone de inmovilizadores de extremidades?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="extremidades" id="extremidades_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="extremidades" id="extremidades_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="extremidades" id="extremidades_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo Inmovilizador extremidades :</label>
                                                <input type="text" name="tipo_extremidades" id="tipo_camilla" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿El personal tiene capacitación en atención de primeros auxilios?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="capacitacion_PA" id="capacitacion_PA_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="capacitacion_PA" id="capacitacion_PA_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="capacitacion_PA" id="capacitacion_PA_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo de Capacitación :</label>
                                                <input type="text" name="tipo_capacitacion_PA" id="tipo_camilla" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_equipo_lesionados" id="observaciones_equipo_lesionados" rows="3"></textarea>
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
                                                        <input class="form-check-input" type="radio" name="ruta" id="ruta_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="ruta" id="ruta_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="ruta" id="ruta_3" value="null">
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
                                                        <input class="form-check-input" type="radio" name="salida" id="salida_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="salida" id="salida_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="salida" id="salida_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_salida_emergencia" id="observaciones_salida_emergencia" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <h5 class="mt-3">Estado de las Escaleras</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>¿Dispone de escaleras?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="escaleras" id="escaleras_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="escaleras" id="escaleras_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="escaleras" id="escaleras_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Condición de las Escaleras :</label>
                                                <input type="text" name="condicion_escaleras" id="condicion_escaleras" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Se encuentran señalizadas??</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="señalizadas" id="señalizadas_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="señalizadas" id="señalizadas_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="señalizadas" id="señalizadas_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Condición de la Señalización :</label>
                                                <input type="text" name="condicion_señales" id="condicion_señales" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Disponen de barandas?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="barandas" id="barandas_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="barandas" id="barandas_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="barandas" id="barandas_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Condición de las Barandas:</label>
                                                <input type="text" name="condicion_barandas" id="condicion_barandas" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Poseen cinta antideslizante?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="antideslizante" id="antideslizante_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="antideslizante" id="antideslizante_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="antideslizante" id="antideslizante_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Condición de los Antideslizantes :</label>
                                                <input type="text" name="condicion_antideslizante" id="condicion_antideslizante" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_antideslizante" id="observaciones_antideslizante" rows="3"></textarea>
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
                                                        <input class="form-check-input" type="radio" name="iluminacion_emergencia" id="iluminacion_emergencia_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="iluminacion_emergencia" id="iluminacion_emergencia_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="iluminacion_emergencia" id="iluminacion_emergencia_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Fecha de última prueba:</label>
                                                <input type="date" class="form-control" id="fecha_ultima_prueba" name="fecha_ultima_prueba">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observaciones :</label>
                                                <textarea class="form-control" name="observaciones_iluminacion_emergencia" id="observaciones_iluminiacion_emergencia" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-------Condicones del Sistema Electrico----------------------------->
                                    <div class="tab-pane fade" id="section-electrico{{$inspection->id}}" role="tabpanel" aria-labelledby="tab-electrico{{$inspection->id}}">
                                        <br>
                                        <h5 class="mt-3">Condiciones del Sistema Eléctrico</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Dispone de caja de distribución con breker: </label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="breker" id="breker_1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="breker" id="breker_2" value="0">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            No
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="breker" id="breker_3" value="null">
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            No Aplica
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>¿Se encuentran identificados? :</label>
                                                <textarea class="form-control" name="identificacion_de_breker" id="observaciones_iluminiacion_emergencia" rows="3"></textarea>
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
                        </form>
                    </div>
                </div>
            </div>



            <!-----END CONCEPTO ---->
            @endforeach
        </tbody>
    </table>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/extintores.js') }}"></script>
<script src="{{ asset('js/botiquines.js') }}"></script>
<script>

</script>



@endsection