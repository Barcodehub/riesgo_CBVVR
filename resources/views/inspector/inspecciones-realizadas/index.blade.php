@extends('inspector.dashboard')

@section('content')

<div class="w-full border p-4 m-4">

    @if (session('success'))
    <h6 class="alert alert-success">{{ session('success') }}</h6>
    @endif

    <h4 class="mb-4">Inspecciones Realizadas: </h4>

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
                        <a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{$inspection->id}}">Ver detalle <i class="ps-2 fa-solid fa-magnifying-glass-plus"></i></a>
                        @if ($inspection->estado != 'FINALIZADA')
                        <a class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalFinalizar{{$inspection->id}}">Finalizar <i class="ps-2 fa-solid fa-plus"></i></a>
                        @endif
                    </div>
                </td>
            </tr>

            <!-- MODAL VER DETALLE -->
            <div class="modal fade" id="modal{{$inspection->id}}" tabindex="-1" aria-labelledby="modalVerDetalle" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalVerDetalle">Ver detalle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="text-center mb-3">
                                @if ($inspection->company->documents->isEmpty())
                                <h6>No tiene foto cargada</h6>
                                @else
                                @foreach ($inspection->company->documents as $document)

                                @if($document->tipo_documento == 'FOTO_FACHADA')
                                <img src="{{ asset('storage/documentos/' . $document->archivo) }}" alt="Foto de la fachada" width="150" />
                                @endif
                                @endforeach
                                @endif
                            </div>

                            <div class="mb-3 row">
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

            <!-- MODAL FINALIZAR INSPECCION -->
            <div class="modal fade" id="modalFinalizar{{$inspection->id}}" tabindex="-1" role="dialog" aria-labelledby="modalFinalizar" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalFinalizar">Finalizar Proceso</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Está seguro que desea generar el certificado de riesgo al establecimiento <strong>{{ $inspection->company->razon_social }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                            <form action="{{ route('inspector.finalizar', [$inspection->id]) }}" method="POST">
                                @method('PATCH')
                                @csrf
                                <button type="submit" class="btn btn-primary">Sí, generar</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>


@endsection