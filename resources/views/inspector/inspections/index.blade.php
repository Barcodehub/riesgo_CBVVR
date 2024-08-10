@extends('inspector.dashboard')

@section('content')

<div class="w-full border p-4 m-4">

    @if (session('success'))
    <h6 class="alert alert-success">{{ session('success') }}</h6>
    @endif

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
                <td>{{$inspection->company->razon_social}}</td>
                <td>{{$inspection->fecha_solicitud}}</td>
                <td>{{$inspection->estado}}</td>
                <td>
                    <div class="flex justify-between items-center gap-2">
                        @if ($inspection->estado == 'SOLICITADA' || $inspection->estado == 'COTIZADA')
                        <a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#conceptoModal{{$inspection->id}}">Dar Concepto <i class="ps-2 fa-solid fa-plus"></i></a>
                        @endif
                    </div>
                </td>
            </tr>

            <!-- MODAL CREAR CONCEPTO -->
            <div class="modal fade" id="conceptoModal{{$inspection->id}}" tabindex="-1" role="dialog" aria-labelledby="conceptoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <form method="POST" id="form{{$inspection->id}}" action="{{ route('inspector.store', [$inspection->id]) }}" class="needs-validation-inspection" novalidate>

                            <div class="modal-header">
                                <h5 class="modal-title" id="conceptoModalLabel">Crear Concepto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            </div>
                            <div class="modal-body">
                                @csrf

                                <div class="mb-3 row g-3">
                                    <div class="col-4">
                                        <label for="carga_ocupacional_fija" class="form-label">Carga Ocupacional Fija</label>
                                        <input required type="text" class="form-control" id="carga_ocupacional_fija" name="carga_ocupacional_fija">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="carga_ocupacional_flotante" class="form-label">Carga Ocupacional Flotante</label>
                                        <input required type="number" class="form-control" id="carga_ocupacional_flotante" name="carga_ocupacional_flotante">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="anios_contruccion" class="form-label">Año de Construcción</label>
                                        <input required type="number" class="form-control" id="anios_contruccion" name="anios_contruccion">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row g-3">
                                    <div class="col-4">
                                        <p for="nrs10" class="form-label">NSR10</p>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="nrs10" id="nrs10Si" value="1">
                                            <label class="form-check-label" for="nrs10Si">Si</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="nrs10" id="nrs10No" value="0">
                                            <label class="form-check-label" for="nrs10No">No</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <p for="sgsst" class="form-label">Sist. de Gestion de Seguridad y Salud en el Trabajo</p>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="sgsst" id="sgsstSi" value="1">
                                            <label class="form-check-label" for="sgsstSi">Si</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="sgsst" id="sgsstNo" value="0">
                                            <label class="form-check-label" for="sgsstNo">No</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <p for="sist_automatico_incendios" class="form-label">Sist. Auto. contra Incendios</p>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="sist_automatico_incendios" id="sist_automatico_incendiosSi" value="1">
                                            <label class="form-check-label" for="sist_automatico_incendiosSi">Si</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="sist_automatico_incendios" id="sist_automatico_incendiosNo" value="0">
                                            <label class="form-check-label" for="sist_automatico_incendiosNo">No</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>


                                </div>

                                <div class="mb-3 row g-3">

                                    <div class="col-6">
                                        <label for="observaciones_sist_incendios" class="form-label">Observaciones</label>
                                        <input required type="text" class="form-control" id="observaciones_sist_incendios" placeholder="Ninguna" name="observaciones_sist_incendios">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="descripcion_concepto" class="form-label">Descripción del Concepto</label>
                                        <input required type="text" class="form-control" id="descripcion_concepto" name="descripcion_concepto">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row g-3">
                                    <div class="col-6">
                                        <p for="hidrante" class="form-label">Hidrante</p>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="hidrante" id="hidranteSi" value="1">
                                            <label class="form-check-label" for="hidranteSi">Si</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="hidrante" id="hidranteNo" value="0">
                                            <label class="form-check-label" for="hidranteNo">No</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="tipo_hidrante" class="form-label">Tipo de Hidrante</label>
                                        <input required type="text" class="form-control" id="tipo_hidrante" name="tipo_hidrante">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row g-3">

                                    <div class="col-6">
                                        <label for="tipo_camilla" class="form-label">Tipo de Camilla</label>
                                        <input required type="text" class="form-control" id="tipo_camilla" name="tipo_camilla">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="inmovilizador_vertical" class="form-label">Inmovilizador Vertical</label>
                                        <input required type="text" class="form-control" id="inmovilizador_vertical" name="inmovilizador_vertical">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row g-3">
                                    <div class="col-4">
                                        <p for="capacitacion" class="form-label">Capacitación</p>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="capacitacion" id="capacitacionSi" value="1">
                                            <label class="form-check-label" for="capacitacionSi">Si</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="capacitacion" id="capacitacionNo" value="0">
                                            <label class="form-check-label" for="capacitacionNo">No</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <p for="capacitacion_primeros_auxilios" class="form-label">Capacitación de primeros auxilios</p>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="capacitacion_primeros_auxilios" id="capacitacion_primeros_auxiliosSi" value="1">
                                            <label class="form-check-label" for="capacitacion_primeros_auxiliosSi">Si</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="capacitacion_primeros_auxilios" id="capacitacion_primeros_auxiliosNo" value="0">
                                            <label class="form-check-label" for="capacitacion_primeros_auxiliosNo">No</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <p for="favorable" class="form-label">Favorable: </p>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="favorable" id="favorableSi" value="1">
                                            <label class="form-check-label" for="favorableSi">Si</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="favorable" id="favorableNo" value="0">
                                            <label class="form-check-label" for="favorableNo">No</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row g-3 col-12">

                                    <h6>Tipos de extintor: </h6>

                                    @if ($tipos_extintor->isEmpty())
                                    <h6>No hay extintores parametrizados</h6>
                                    @else

                                    @foreach ($tipos_extintor as $tipo_extintor)
                                    <div class="col-12">

                                        <div class="form-check d-flex align-items-center gap-3">

                                            <div>
                                                <input class="form-check-input" type="checkbox" name="tipo_extintor[]" value="{{ $tipo_extintor->id }}" id="tipo_extintor_{{ $tipo_extintor->id }}" onchange="toggleFieldsTipoExtintor('{{ $tipo_extintor->id }}')">
                                            </div>
                                            <div class="w-100">
                                                <label class="form-check-label" for="tipo_extintor_{{ $tipo_extintor->id }}">
                                                    {{$tipo_extintor->descripcion}}
                                                </label>

                                                <div class="d-flex gap-3">

                                                    <div class="w-50">
                                                        <label class="form-label" for="empresa_recarga_tipo_extintor_{{ $tipo_extintor->id }}">Empresa que recarga </label>
                                                        <input type="text" class="form-control" id="empresa_recarga_tipo_extintor_{{$tipo_extintor->id}}" name="empresa_recarga_tipo_extintor[{{ $tipo_extintor->id }}]" 
                                                            disabled placeholder="Escriba el nombre de la empresa que recarga">
                                                    </div>

                                                    <div class="w-50">
                                                        <label class="form-label" for="fecha_vencimiento_tipo_extintor_{{ $tipo_extintor->id }}">Fecha de vencimiento </label>
                                                        <input type="date" class="form-control" id="fecha_vencimiento_tipo_extintor_{{$tipo_extintor->id}}" name="fecha_vencimiento_tipo_extintor[{{ $tipo_extintor->id }}]" disabled>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    @endforeach
                                    @endif


                                </div>

                                <div class="mb-3 row g-3 col-12">

                                    <h6>Tipos de botiquín: </h6>

                                    @if ($tipos_botiquin->isEmpty())
                                    <h6>No hay botiquines parametrizados</h6>
                                    @else

                                    @foreach ($tipos_botiquin as $tipo_botiquin)
                                    <div class="col-12">

                                        <div class="form-check d-flex align-items-center gap-3">

                                            <div>
                                                <input class="form-check-input" type="checkbox" name="tipo_botiquin[]" value="{{ $tipo_botiquin->id }}" id="tipo_botiquin_{{ $tipo_botiquin->id }}" onchange="toggleFieldsTipoBotiquin('{{ $tipo_botiquin->id }}')">
                                            </div>
                                            <div class="w-100">
                                                <label class="form-check-label" for="tipo_botiquin_{{ $tipo_botiquin->id }}">
                                                    {{$tipo_botiquin->descripcion}}
                                                </label>

                                                <div class="d-flex gap-3">

                                                    <div class="w-50">
                                                        <label class="form-label" for="empresa_recarga_tipo_botiquin_{{ $tipo_botiquin->id }}">Empresa que recarga </label>
                                                        <input type="text" class="form-control" id="empresa_recarga_tipo_botiquin_{{$tipo_botiquin->id}}" name="empresa_recarga_tipo_botiquin[{{ $tipo_botiquin->id }}]" 
                                                            disabled placeholder="Escriba el nombre de la empresa que recarga">
                                                    </div>

                                                    <div class="w-50">
                                                        <label class="form-label" for="fecha_vencimiento_tipo_botiquin_{{ $tipo_botiquin->id }}">Fecha de vencimiento </label>
                                                        <input type="date" class="form-control" id="fecha_vencimiento_tipo_botiquin_{{$tipo_botiquin->id}}" name="fecha_vencimiento_tipo_botiquin[{{ $tipo_botiquin->id }}]" disabled>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    @endforeach
                                    @endif

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <input type="submit" value="Crear concepto" class="btn btn-primary my-2" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>

</div>

<script>
    (() => {
        'use strict'

        const forms = document.querySelectorAll('.needs-validation-inspection');

        console.log(forms)

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                console.log(event)
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<script>
function toggleFieldsTipoExtintor(tipoExtintorId) {
    const checkbox = document.getElementById('tipo_extintor_' + tipoExtintorId);
    const empresaRecargaField = document.getElementById('empresa_recarga_tipo_extintor_' + tipoExtintorId);
    const fechaVencimientoField = document.getElementById('fecha_vencimiento_tipo_extintor_' + tipoExtintorId);

    if (checkbox.checked) {
        empresaRecargaField.removeAttribute('disabled');
        empresaRecargaField.setAttribute('required', 'required');
        fechaVencimientoField.removeAttribute('disabled');
        fechaVencimientoField.setAttribute('required', 'required');
    } else {
        empresaRecargaField.setAttribute('disabled', 'disabled');
        empresaRecargaField.removeAttribute('required');
        fechaVencimientoField.setAttribute('disabled', 'disabled');
        fechaVencimientoField.removeAttribute('required');
    }
}

function toggleFieldsTipoBotiquin(tipoBotiquinId) {
    const checkbox = document.getElementById('tipo_botiquin_' + tipoBotiquinId);
    const empresaRecargaField = document.getElementById('empresa_recarga_tipo_botiquin_' + tipoBotiquinId);
    const fechaVencimientoField = document.getElementById('fecha_vencimiento_tipo_botiquin_' + tipoBotiquinId);

    if (checkbox.checked) {
        empresaRecargaField.removeAttribute('disabled');
        empresaRecargaField.setAttribute('required', 'required');
        fechaVencimientoField.removeAttribute('disabled');
        fechaVencimientoField.setAttribute('required', 'required');
    } else {
        empresaRecargaField.setAttribute('disabled', 'disabled');
        empresaRecargaField.removeAttribute('required');
        fechaVencimientoField.setAttribute('disabled', 'disabled');
        fechaVencimientoField.removeAttribute('required');
    }
}
</script>


@endsection