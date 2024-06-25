@extends('admin.dashboard')

@section('content')

<div class="w-full border p-4 m-4">

    @if (session('success'))
    <h6 class="alert alert-success">{{ session('success') }}</h6>
    @endif

    <div class="w-6 my-4">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            Crear tipo de botiquín <i class="ps-2 fa-solid fa-plus"></i>
        </a>
    </div>


    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Descripción</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kits as $kit)
            <tr>
                <td>{{$kit->id}}</td>
                <td>{{$kit->descripcion}}</td>
                <td>
                    <div class="flex justify-between items-center gap-2">
                    <a class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{$kit->id}}">Editar</a>
                    <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{$kit->id}}">Eliminar</a>
                    </div>
                </td>
            </tr>
            <!-- MODAL ELIMINAR -->
            <div class="modal fade" id="modal{{$kit->id}}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Eliminar tipo de botiquín</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Al eliminar el tipo de kit <strong>{{ $kit->descripcion }}</strong> se eliminan en los conceptos creados por los inspectores.
                            ¿Está seguro que desea eliminar el tipo de kit <strong>{{ $kit->descripcion }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                            <form action="{{ route('kits.destroy', [$kit->id]) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-primary">Sí, eliminar</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL EDITAR -->
            <div class="modal fade" id="editModal{{$kit->id}}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Editar tipo de botiquín</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('kits.update', [$kit->id]) }}" class="needs-validation" novalidate>
                                @method('PATCH')
                                @csrf

                                <div class="mb-3 col">

                                    <label for="descripcion" class="form-label">Descripción *</label>
                                    <input type="text" class="form-control mb-2" name="descripcion" placeholder="Escriba la descripción" value="{{ $kit->descripcion }}" required>
                                    <div class="invalid-feedback">
                                        Complete este campo.
                                    </div>

                                    <input type="submit" value="Actualizar" class="btn btn-primary my-2" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Modal Crear Tipo de kit -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Crear Nuevo Tipo de kit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('kits.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción *</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" required>
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

@endsection