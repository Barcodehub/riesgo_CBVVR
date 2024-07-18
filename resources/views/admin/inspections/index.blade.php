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
                <th scope="col">Acciones</th>
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
                <td>
                    <div class="flex justify-between items-center gap-2">
                        <a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{$inspection->id}}">Ver detalle <i class="ps-2 fa-solid fa-magnifying-glass-plus"></i></a>
                        @if ($inspection->valor == null)
                        <a class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cotizarModal{{$inspection->id}}">Cotizar <i class="ps-2 fa-solid fa-pen"></i></a>
                            
                        @endif
                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#asignarInspectorModal{{$inspection->id}}">Inspector <i class="fa-regular fa-square-check"></i></a>
                            
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
                                @method('PATCH')
                                @csrf

                                <div class="mb-3 col">

                                    <div class="mb-3">
                                        <label for="valor" class="form-label">Valor de la inspección: </label>

                                        <input class="form-control" type="number" min="1000" name="valor" id="valor" placeholder="Digite el valor" required>
                                        <div class="invalid-feedback">
                                            El valor debe ser mayor a 1000.
                                        </div>
                                    </div>

                                </div>
                                <input type="submit" value="Guardar" class="btn btn-primary my-2" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>

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
                        <div class="modal-body">

                            @if($inspection->user)

                            <p>Inspector: {{ $inspection->user->nombre }} {{ $inspection->user->apellido }}</p>
                            <p>Fecha Asignación: {{ $inspection->fecha_asignacion_inspector }}</p>
                            <p>Estado Actual: {{ $inspection->estado }}</p>


                            @else
                            
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
                            


                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
                        </div>
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