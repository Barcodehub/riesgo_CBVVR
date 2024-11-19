@extends('cliente.dashboard')

@section('content')

@section('content')
<div class="w-full border p-4 m-4">

    @if (session('success'))
    <h6 class="alert alert-success">{{ session('success') }}</h6>
    @endif

    <h4 class="mb-4">Empresas de {{ $clientName }}</h4>

    <div class="w-6 my-4">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            Registrar Nueva empresa <i class="ps-2 fa-solid fa-plus"></i>
        </a>
    </div>


    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre Establecimiento</th>
                <th scope="col">Dirección</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Email</th>
                <th scope="col">Actividad Comercial</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if ($companies->isEmpty())
            <tr>
                <td colspan="7" class="text-center">
                    No hay empresas
                </td>
            </tr>
            @endif
            @foreach ($companies as $company)
            <tr>
                <td>{{ $company->id }}</td>
                <td>{{ $company->nombre_establecimiento }}</td>
                <td>{{ $company->direccion }}</td>
                <td>{{ $company->telefono }}</td>
                <td>{{ $company->email }}</td>
                <td>{{ $company->actividad_comercial }}</td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modal{{ $company->id }}"> <i
                                class="fa-solid fa-magnifying-glass-plus"></i></a>
                        <a class="btn btn-warning btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editModal{{ $company->id }}"> <i class="fa-solid fa-pen"></i></a>
                        @if ($company->info_establecimiento->isEmpty())
                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#infoEstablecimientoModal{{ $company->id }}">
                            <i class="fa-solid fa-square-plus"></i>
                        </a>
                        @endif
                        @if ($company->inspections->isEmpty())
                        <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#solicitarInspeccionModal{{ $company->id }}">
                            <i class="fa-solid fa-check-to-slot">‌</i>
                        </a>
                        @endif
                    </div>
                </td>
            </tr>

            <!--- Modal Solicitar Inspección ---->

            <!-- Modal -->
            <div class="modal fade" id="solicitarInspeccionModal{{ $company->id }}" tabindex="-1" aria-labelledby="solicitarInspeccionModalLabel{{ $company->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="solicitarInspeccionModalLabel{{ $company->id }}">Solicitud de Inspección</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Deseas solicitar la inspección para la empresa <strong>{{ $company->nombre_establecimiento }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <form action="{{ route('cliente.storeInspeccion') }}" method="POST">
                                @csrf
                                
                                <input type="hidden" name="company_id" value="{{ $company->id }}">
                                <button type="submit" class="btn btn-primary">Solicitar Inspección</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal Añaidir información del establecimeinto  -->

            <div class="modal fade" id="infoEstablecimientoModal{{ $company->id }}" tabindex="-1" aria-labelledby="infoEstablecimientoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="establecimientoForm" method="POST" action="{{ route('cliente.storeEstablecimiento', $company->id) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="infoEstablecimientoModalLabel">Agregar Información del Establecimiento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Inputs del formulario -->
                                <div class="mb-3">
                                    <label for="num_pisos" class="form-label">Número de Pisos *</label>
                                    <input type="number" class="form-control" id="num_pisos" name="num_pisos" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ancho_dimensiones" class="form-label">Ancho de Dimensiones (m) *</label>
                                    <input type="number" step="0.01" class="form-control" id="ancho_dimensiones" name="ancho_dimensiones" required>
                                </div>
                                <div class="mb-3">
                                    <label for="largo_dimensiones" class="form-label">Largo de Dimensiones (m) *</label>
                                    <input type="number" step="0.01" class="form-control" id="largo_dimensiones" name="largo_dimensiones" required>
                                </div>
                                <div class="mb-3">
                                    <label for="carga_ocupacional_fija" class="form-label">Carga Ocupacional Fija *</label>
                                    <input type="number" class="form-control" id="carga_ocupacional_fija" name="carga_ocupacional_fija" required>
                                </div>
                                <div class="mb-3">
                                    <label for="carga_ocupacional_flotante" class="form-label">Carga Ocupacional Flotante *</label>
                                    <input type="number" class="form-control" id="carga_ocupacional_flotante" name="carga_ocupacional_flotante" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- MODAL VER DETALLE -->
            <div class="modal fade" id="modal{{ $company->id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ver detalle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3 row g-3">
                                <div class="col-4">
                                    <label for="nombre_establecimiento" class="form-label">Nombre del
                                        establecimiento</label>
                                    <input type="text" class="form-control" id="nombre_establecimiento"
                                        value="{{ $company->nombre_establecimiento }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="representante_legal" class="form-label">Representante Legal</label>
                                    <input type="text" class="form-control" id="representante_legal"
                                        value="{{ $company->representante_legal }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="cedula_representante" class="form-label">Documento
                                        Representante</label>
                                    <input type="text" class="form-control" id="cedula_representante"
                                        value="{{ $company->cedula_representante }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3 row g-3">
                                <div class="col-6">
                                    <label for="nit" class="form-label">NIT</label>
                                    <input type="text" class="form-control" id="nit"
                                        value="{{ $company->nit }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion"
                                        value="{{ $company->direccion }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="barrio" class="form-label">Barrio</label>
                                    <input type="text" class="form-control" id="barrio"
                                        value="{{ $company->barrio }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="razon_social" class="form-label">Razón Social</label>
                                    <input type="text" class="form-control" id="razon_social"
                                        value="{{ $company->razon_social }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3 row g-3">
                                <div class="col-6">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono"
                                        value="{{ $company->telefono }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email"
                                        value="{{ $company->email }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3 row g-3">
                                <div class="col-6">
                                    <label for="actividad_comercial" class="form-label">Actividad
                                        Comercial</label>
                                    <input type="text" class="form-control" id="actividad_comercial"
                                        value="{{ $company->actividad_comercial }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="horario_funcionamiento" class="form-label">Horario
                                        Funcionamiento</label>
                                    <input type="text" class="form-control" id="horario_funcionamiento"
                                        value="{{ $company->horario_funcionamiento }}" readonly>
                                </div>
                            </div>

                            <h6 class="mt-4">Información relacionada a esta empresa: </h6>
                            <div class="accordion" id="accordionCompany">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            Inspección:
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionCompany">
                                        <div class="accordion-body">


                                            @if ($company->inspections->isEmpty())
                                            <h6 class="mt-4">No tiene inspecciones creadas</h6>
                                            @else
                                            @foreach ($company->inspections as $inspection)
                                            <p>Inspector:
                                                {{ $inspection->user ? $inspection->user->nombre . ' ' . $inspection->user->apellido : 'No tiene inspector asignado' }}
                                            </p>
                                            <p>Fecha Solicitud Inspección:
                                                {{ $inspection->fecha_solicitud }}
                                            </p>
                                            <p>Estado Actual: {{ $inspection->estado }}</p>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                            Documentos cargados:
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionCompany">
                                        <div class="accordion-body">
                                            @if ($company->documents->isEmpty())
                                            <h6 class="mt-4">No tiene documentos cargados</h6>
                                            @else
                                            @foreach ($company->documents as $document)
                                            <div class="d-flex justify-content-between">

                                                @if ($document->tipo_documento != 'FOTO_FACHADA')
                                                <h6>{{ $document->tipo_documento }}</h6>
                                                <a href="{{ asset('storage/documentos/empresa-' . $company->id . '/' . $document->archivo) }}"
                                                    target="_blank"
                                                    download="{{ $document->archivo }}">Descargar
                                                    {{ $document->tipo_documento }}</a>
                                                @else
                                                <div>
                                                    <h6>{{ $document->tipo_documento }}</h6>
                                                    <img src="{{ asset('storage/documentos/empresa-' . $company->id . '/' . $document->archivo) }}"
                                                        alt="Foto de la fachada" width="500" />
                                                </div>
                                                @endif

                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- MODAL EDITAR -->

            <div class="modal fade" id="editModal{{ $company->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Editar Empresa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('cliente.updateCliente', [$company->id]) }}"
                                enctype="multipart/form-data" class="needs-validation" novalidate>
                                @method('PATCH')
                                @csrf

                                <div class="mb-3">

                                    <div class="mb-3 row g-3">
                                        <div class="col-4">
                                            <label for="nombre_establecimiento" class="form-label">Nombre del
                                                establecimiento</label>
                                            <input type="text" class="form-control"
                                                id="nombre_establecimiento"
                                                value="{{ $company->nombre_establecimiento }}"
                                                name="nombre_establecimiento" required>
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="representante_legal" class="form-label">Representante
                                                Legal</label>
                                            <input required type="text" class="form-control"
                                                id="representante_legal"
                                                value="{{ $company->representante_legal }}"
                                                name="representante_legal">
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="cedula_representante" class="form-label">Documento del
                                                representante</label>
                                            <input required type="text"
                                                placeholder="Escriba el documento del representante legal"
                                                class="form-control" id="cedula_representante"
                                                value="{{ $company->cedula_representante }}"
                                                name="cedula_representante">
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mb-3 row g-3">
                                        <div class="col-4">
                                            <label for="horario_funcionamiento" class="form-label">Horario
                                                funcionamiento *</label>
                                            <input required type="text"
                                                placeholder="Escriba el horario de funcionamiento"
                                                class="form-control" id="horario_funcionamiento"
                                                value="{{ $company->horario_funcionamiento }}"
                                                name="horario_funcionamiento">
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="nit" class="form-label">NIT</label>
                                            <input required type="text" class="form-control" id="nit"
                                                value="{{ $company->nit }}" name="nit">
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="direccion" class="form-label">Dirección</label>
                                            <input required type="text" class="form-control" id="direccion"
                                                value="{{ $company->direccion }}" name="direccion">
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="barrio" class="form-label">Barrio</label>
                                            <input required type="text" class="form-control" id="barrio"
                                                value="{{ $company->barrio }}" name="barrio">
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="razon_social" class="form-label">Razón Social</label>
                                            <input required type="text" class="form-control" id="razon_social"
                                                value="{{ $company->razon_social }}" name="razon_social">
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 row g-3">
                                        <div class="col-4">
                                            <label for="telefono" class="form-label">Teléfono</label>
                                            <input required type="text" class="form-control" id="telefono"
                                                value="{{ $company->telefono }}" name="telefono">
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="email" class="form-label">Email</label>
                                            <input required type="text" class="form-control" id="email"
                                                value="{{ $company->email }}" name="email">
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="actividad_comercial" class="form-label">Actividad
                                                Comercial</label>
                                            <input required type="text" class="form-control"
                                                id="actividad_comercial"
                                                value="{{ $company->actividad_comercial }}"
                                                name="actividad_comercial">
                                            <div class="invalid-feedback">
                                                Complete este campo.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="rut" class="form-label">Copia del RUT, vigencia no
                                            superior los treinta (30) días *</label>
                                        <input class="form-control" type="file" id="rut" accept=".pdf"
                                            name="rut">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>

                                        @foreach ($company->documents as $document)
                                        @if ($document->tipo_documento == 'RUT')
                                        <div class="p-2">
                                            <a href="{{ asset('storage/documentos/empresa-' . $company->id . '/' . $document->archivo) }}"
                                                target="_blank"
                                                download="{{ $document->archivo }}">{{ $document->tipo_documento }}</a>
                                        </div>
                                        @endif
                                        @endforeach

                                    </div>




                                    <div class="mb-3">
                                        <label for="camara_comercio" class="form-label">Copia del certificado de
                                            existencia y representación Legal (Cámara de comercio), vigencia no
                                            superior los treinta (30) días. *</label>
                                        <input class="form-control" type="file" id="camara_comercio"
                                            accept=".pdf" name="camara_comercio">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>

                                        @foreach ($company->documents as $document)
                                        @if ($document->tipo_documento == 'CAMARA_COMERCIO')
                                        <div class="p-2">
                                            <a href="{{ asset('storage/documentos/empresa-' . $company->id . '/' . $document->archivo) }}"
                                                target="_blank"
                                                download="{{ $document->archivo }}">{{ $document->tipo_documento }}</a>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>


                                    <div class="mb-3">
                                        <label for="cedula" class="form-label">Copia de la cédula de ciudadanía
                                            del representante legal *</label>
                                        <input class="form-control" type="file" id="cedula" name="cedula"
                                            accept=".pdf">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>

                                        @foreach ($company->documents as $document)
                                        @if ($document->tipo_documento == 'CEDULA_REPRESENTANTE')
                                        <div class="p-2">
                                            <a href="{{ asset('storage/documentos/empresa-' . $company->id . '/' . $document->archivo) }}"
                                                target="_blank"
                                                download="{{ $document->archivo }}">{{ $document->tipo_documento }}</a>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>


                                    <div class="mb-5">
                                        <label for="fachada" class="form-label">Fotografía de la fachada del
                                            establecimiento a inspeccionar *</label>
                                        <input class="form-control" type="file" id="fachada" name="fachada"
                                            accept="image/*">
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>

                                        @foreach ($company->documents as $document)
                                        @if ($document->tipo_documento == 'FOTO_FACHADA')
                                        <div class="p-2">

                                            <h6>{{ $document->tipo_documento }}</h6>
                                            <img src="{{ asset('storage/documentos/empresa-' . $company->id . '/' . $document->archivo) }}"
                                                alt="Foto de la fachada" width="500" />
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>

                                </div>


                                <input type="submit" value="Actualizar" class="btn btn-primary my-2" />
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach

        </tbody>
    </table>
</div>


<!-- Modal Crear Empresa -->

<!-- Modal Crear Empresa -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Registrar Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('cliente.storeCliente') }}" method="POST" enctype="multipart/form-data"
                    class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-3 row g-3">

                        <div class="col-4">
                            <label for="nombre_establecimiento" class="form-label">Nombre del establecimiento</label>
                            <input type="text" placeholder="Escriba el nombre..." class="form-control"
                                id="nombre_establecimiento" name="nombre_establecimiento" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>

                        <div class="col-4">
                            <label for="razon_social" class="form-label">Razón social</label>
                            <input type="text" placeholder="Escriba el nombre..." class="form-control"
                                id="razon_social" name="razon_social" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>

                        <div class="col-4">
                            <label for="representante_legal" class="form-label">Representante Legal</label>
                            <input type="text" placeholder="Escriba el nombre del representante legal"
                                class="form-control" id="representante_legal" name="representante_legal" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>

                        <div class="col-4">
                            <label for="cedula_representante" class="form-label">Documento del representante *</label>
                            <input type="text" placeholder="Escriba el documento del representante legal"
                                class="form-control" id="cedula_representante" name="cedula_representante" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row g-3">

                        <div class="col-4">
                            <label for="horario_funcionamiento" class="form-label">Horario funcionamiento *</label>
                            <input type="text" placeholder="Escriba el horario de funcionamiento"
                                class="form-control" id="horario_funcionamiento" name="horario_funcionamiento"
                                required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="nit" class="form-label">Nit</label>
                            <input type="text" placeholder="Escriba el nit" class="form-control" id="nit"
                                name="nit" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" placeholder="Escriba la dirección" class="form-control"
                                id="direccion" name="direccion" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>

                        <div class="col-4">
                            <label for="barrio" class="form-label">Barrio</label>
                            <input type="text" placeholder="Escriba la dirección" class="form-control"
                                id="barrio" name="barrio" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>

                    </div>


                    <div class="mb-3 row g-3">

                        <div class="col-4">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" placeholder="Escriba el número de teléfono" class="form-control"
                                id="telefono" name="telefono" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>

                        <div class="col-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" placeholder="Escriba el correo electrónico" class="form-control"
                                id="email" name="email" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="actividad_comercial" class="form-label">Actividad Comercial</label>
                            <input type="text" placeholder="Escriba la actividad comercial" class="form-control"
                                id="actividad_comercial" name="actividad_comercial" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="rut" class="form-label">Copia del RUT, vigencia no superior los treinta (30)
                            días *</label>
                        <input class="form-control" type="file" id="rut" accept=".pdf" name="rut"
                            required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="camara_comercio" class="form-label">Copia del certificado de existencia y
                            representación Legal (Cámara de comercio), vigencia no superior los treinta (30) días.
                            *</label>
                        <input class="form-control" type="file" id="camara_comercio" accept=".pdf"
                            name="camara_comercio" required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>



                    <div class="mb-3">
                        <label for="cedula" class="form-label">Copia de la cédula de ciudadanía del representante
                            legal *</label>
                        <input class="form-control" type="file" id="cedula" name="cedula" accept=".pdf"
                            required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="fachada" class="form-label">Fotografía de la fachada del establecimiento a
                            inspeccionar *</label>
                        <input class="form-control" type="file" id="fachada" name="fachada" accept="image/*"
                            required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation-update')
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