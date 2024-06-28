@extends('cliente.dashboard')

@section('content')

<div class="w-full border p-4 m-4">
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

    <div class="mb-3 row g-3">
        <div class="col-6">
            <label for="inspector" class="form-label">Inspector Asignado</label>
            <input type="text" class="form-control" id="inspector" value="{{ $inspection->user->nombre . ' ' . $inspection->user->apellido }}" readonly>
        </div>

        <div class="col-6">
            <label for="estado" class="form-label">Estado de la Inspección</label>
            <input type="text" class="form-control" id="estado" value="{{ $inspection->estado }}" readonly>
        </div>
    </div>


    @if ($inspection->valor)
    <div class="mb-3">
        <label for="valor" class="form-label">Valor de la Inspección</label>
        <input type="text" class="form-control" id="valor" value="$ {{ $inspection->valor }}" readonly>
    </div>
    @else
    <h6>No tiene valor asignado</h6>

    @endif



    <h4 class="mt-5">Detalle del concepto: </h4>

    @if ($inspection->concept->isEmpty())
    <h6>No tiene concepto asignado</h6>
    @else
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVerConcepto">Ver concepto <i class="fa-solid fa-magnifying-glass-plus"></i></button>
    <!-- MODAL VER DETALLE -->
    <div class="modal fade" id="modalVerConcepto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Concepto de la inspección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3 row g-3">
                        <div class="col-4">
                            <label for="fecha_concepto" class="form-label">Fecha concepto</label>
                            <input type="text" class="form-control" id="fecha_concepto" value="{{ $concept->fecha_concepto }}" readonly>
                        </div>
                        <div class="col-4">
                            <label for="carga_ocupacional_fija" class="form-label">Carga Ocupacional Fija</label>
                            <input type="text" class="form-control" id="carga_ocupacional_fija" value="{{ $concept->carga_ocupacional_fija }}" readonly>
                        </div>
                        <div class="col-4">
                            <label for="carga_ocupacional_flotante" class="form-label">Carga Ocupacional Flotante</label>
                            <input type="text" class="form-control" id="carga_ocupacional_flotante" value="{{ $concept->carga_ocupacional_flotante }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row g-3">
                        <div class="col-4">
                            <label for="anios_contruccion" class="form-label">Año de Construcción</label>
                            <input type="text" class="form-control" id="anios_contruccion" value="{{ $concept->anios_contruccion }}" readonly>
                        </div>
                        <div class="col-4">
                            <label for="nrs10" class="form-label">NSR10</label>
                            <input type="text" class="form-control" id="nrs10" value="{{ $concept->nrs10 ? 'Si' : 'No' }}" readonly>
                        </div>
                        <div class="col-4">
                            <label for="sgsst" class="form-label">Sist. de Gestion de Seguridad y Salud en el Trabajo</label>
                            <input type="text" class="form-control" id="sgsst" value="{{ $concept->sgsst ? 'Si' : 'No' }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row g-3">
                        <div class="col-4">
                            <label for="sist_automatico_incendios" class="form-label">Sist. Auto. contra Incendios</label>
                            <input type="text" class="form-control" id="sist_automatico_incendios" value="{{ $concept->sist_automatico_incendios ? 'Si' : 'No' }}" readonly>
                        </div>
                        <div class="col-4">
                            <label for="observaciones_sist_incendios" class="form-label">Observaciones</label>
                            <input type="text" class="form-control" id="observaciones_sist_incendios" placeholder="Ninguna" value="{{ $concept->observaciones_sist_incendios }}" readonly>
                        </div>
                        <div class="col-4">
                            <label for="descripcion_concepto" class="form-label">Descripción del Concepto</label>
                            <input type="text" class="form-control" id="descripcion_concepto" placeholder="Ninguna" value="{{ $concept->descripcion_concepto }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row g-3">
                        <div class="col-4">
                            <label for="hidrante" class="form-label">Hidrante</label>
                            <input type="text" class="form-control" id="hidrante" value="{{ $concept->hidrante ? 'Si' : 'No' }}" readonly>
                        </div>
                        <div class="col-4">
                            <label for="tipo_hidrante" class="form-label">Tipo de Hidrante</label>
                            <input type="text" class="form-control" id="tipo_hidrante" value="{{ $concept->tipo_hidrante }}" readonly>
                        </div>
                        <div class="col-4">
                            <label for="tipo_camilla" class="form-label">Tipo de Camilla</label>
                            <input type="text" class="form-control" id="tipo_camilla" value="{{ $concept->tipo_camilla }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row g-3">
                        <div class="col-4">
                            <label for="inmovilizador_vertical" class="form-label">Inmovilizador Vertical</label>
                            <input type="text" class="form-control" id="inmovilizador_vertical" value="{{ $concept->inmovilizador_vertical }}" readonly>
                        </div>
                        <div class="col-4">
                            <label for="capacitacion" class="form-label">Capacitación</label>
                            <input type="text" class="form-control" id="capacitacion" value="{{ $concept->capacitacion ? 'Si' : 'No' }}" readonly>
                        </div>
                        <div class="col-4">
                            <label for="capacitacion_primeros_auxilios" class="form-label">Capacitación de primeros auxilios</label>
                            <input type="text" class="form-control" id="capacitacion_primeros_auxilios" value="{{ $concept->capacitacion_primeros_auxilios ? 'Si' : 'No' }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row g-3">
                        <div class="accordion" id="accordionConcepto">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Tipos de extintor:
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionConcepto">
                                    <div class="accordion-body">


                                        @if ($concept->tipo_extintor_conceptos->isEmpty())
                                        <h6>No hay extintores</h6>
                                        @else
                                        <div class="d-flex gap-2">
                                            @foreach ($concept->tipo_extintor_conceptos as $tipo_extintor_concepto)

                                            <div class="border w-50 p-1">
                                                <p class="m-0 p-0">Extintor: {{$tipo_extintor_concepto->tipo_extintor->descripcion}}</p>
                                                <p class="m-0 p-0">Empresa Recarga: {{$tipo_extintor_concepto->empresa_recarga}}</p>
                                                <p class="m-0 p-0">Fecha Vencimiento: {{$tipo_extintor_concepto->fecha_vencimiento}}</p>

                                            </div>

                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Tipos de botiquines:
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionConcepto">
                                    <div class="accordion-body">
                                        @if ($concept->tipo_botiquin_conceptos->isEmpty())
                                        <h6>No hay botiquines</h6>
                                        @else
                                        <div class="d-flex gap-2">
                                            @foreach ($concept->tipo_botiquin_conceptos as $tipo_botiquin_concepto)

                                            <div class="border w-50 p-1">
                                                <p class="m-0 p-0">Botiquín: {{$tipo_botiquin_concepto->tipo_botiquin->descripcion}}</p>
                                                <p class="m-0 p-0">Empresa Recarga: {{$tipo_botiquin_concepto->empresa_recarga}}</p>
                                                <p class="m-0 p-0">Fecha Vencimiento: {{$tipo_botiquin_concepto->fecha_vencimiento}}</p>

                                            </div>

                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>




@endsection