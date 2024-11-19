@extends('admin.dashboard')

@section('content')

<div class="w-full border p-4 m-4">

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
                        @if ($inspection->concept->first())
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

            <!-- MODAL VER DETALLE -->
            @if ($inspection->concept->first())

            <div class="modal fade" id="modalVerConcepto{{$inspection->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <input type="text" class="form-control" id="fecha_concepto" value="{{ $inspection->concept->first()->fecha_concepto }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="carga_ocupacional_fija" class="form-label">Carga Ocupacional Fija</label>
                                    <input type="text" class="form-control" id="carga_ocupacional_fija" value="{{ $inspection->concept->first()->carga_ocupacional_fija }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="carga_ocupacional_flotante" class="form-label">Carga Ocupacional Flotante</label>
                                    <input type="text" class="form-control" id="carga_ocupacional_flotante" value="{{ $inspection->concept->first()->carga_ocupacional_flotante }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3 row g-3">
                                <div class="col-4">
                                    <label for="anios_contruccion" class="form-label">Año de Construcción</label>
                                    <input type="text" class="form-control" id="anios_contruccion" value="{{ $inspection->concept->first()->anios_contruccion }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="nrs10" class="form-label">NSR10</label>
                                    <input type="text" class="form-control" id="nrs10" value="{{ $inspection->concept->first()->nrs10 ? 'Si' : 'No' }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="sgsst" class="form-label">Sist. de Gestion de Seguridad y Salud en el Trabajo</label>
                                    <input type="text" class="form-control" id="sgsst" value="{{ $inspection->concept->first()->sgsst ? 'Si' : 'No' }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3 row g-3">
                                <div class="col-4">
                                    <label for="sist_automatico_incendios" class="form-label">Sist. Auto. contra Incendios</label>
                                    <input type="text" class="form-control" id="sist_automatico_incendios" value="{{ $inspection->concept->first()->sist_automatico_incendios ? 'Si' : 'No' }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="observaciones_sist_incendios" class="form-label">Observaciones</label>
                                    <input type="text" class="form-control" id="observaciones_sist_incendios" placeholder="Ninguna" value="{{ $inspection->concept->first()->observaciones_sist_incendios }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="descripcion_concepto" class="form-label">Descripción del Concepto</label>
                                    <input type="text" class="form-control" id="descripcion_concepto" placeholder="Ninguna" value="{{ $inspection->concept->first()->descripcion_concepto }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3 row g-3">
                                <div class="col-4">
                                    <label for="hidrante" class="form-label">Hidrante</label>
                                    <input type="text" class="form-control" id="hidrante" value="{{ $inspection->concept->first()->hidrante ? 'Si' : 'No' }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="tipo_hidrante" class="form-label">Tipo de Hidrante</label>
                                    <input type="text" class="form-control" id="tipo_hidrante" value="{{ $inspection->concept->first()->tipo_hidrante }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="tipo_camilla" class="form-label">Tipo de Camilla</label>
                                    <input type="text" class="form-control" id="tipo_camilla" value="{{ $inspection->concept->first()->tipo_camilla }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3 row g-3">
                                <div class="col-4">
                                    <label for="inmovilizador_vertical" class="form-label">Inmovilizador Vertical</label>
                                    <input type="text" class="form-control" id="inmovilizador_vertical" value="{{ $inspection->concept->first()->inmovilizador_vertical }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="capacitacion" class="form-label">Capacitación</label>
                                    <input type="text" class="form-control" id="capacitacion" value="{{ $inspection->concept->first()->capacitacion ? 'Si' : 'No' }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="capacitacion_primeros_auxilios" class="form-label">Capacitación de primeros auxilios</label>
                                    <input type="text" class="form-control" id="capacitacion_primeros_auxilios" value="{{ $inspection->concept->first()->capacitacion_primeros_auxilios ? 'Si' : 'No' }}" readonly>
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


                                                @if ($inspection->concept->first()->tipo_extintor_conceptos->isEmpty())
                                                <h6>No hay extintores</h6>
                                                @else
                                                <div class="d-flex gap-2">
                                                    @foreach ($inspection->concept->first()->tipo_extintor_conceptos as $tipo_extintor_concepto)

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
                                                @if ($inspection->concept->first()->tipo_botiquin_conceptos->isEmpty())
                                                <h6>No hay botiquines</h6>
                                                @else
                                                <div class="d-flex gap-2">
                                                    @foreach ($inspection->concept->first()->tipo_botiquin_conceptos as $tipo_botiquin_concepto)

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

                            <div class="mb-3 row g-3">
                                <div class="col-6">
                                    <label for="concepto_favorable" class="form-label">Concepto favorable: </label>
                                    <input type="text" class="form-control" id="concepto_favorable" value="{{ $inspection->concept->first()->favorable ? 'Si' : 'No' }}" readonly>
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