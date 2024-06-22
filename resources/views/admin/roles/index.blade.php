@extends('admin.dashboard')

@section('content')

<div class="w-full border p-4 m-4">

    @if (session('success'))
    <h6 class="alert alert-success">{{ session('success') }}</h6>
    @endif

    @error('nombre')
    <h6 class="alert alert-danger">{{ $message }}</h6>
    @enderror


    <div class="w-6 my-4">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            Crear rol <i class="ps-2 fa-solid fa-plus"></i>
        </a>
    </div>


    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $rol)
            <tr>
                <td>{{$rol->id}}</td>
                <td>{{$rol->nombre}}</td>
                <td>
                    <div class="flex justify-between items-center gap-2">
                        <a class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{$rol->id}}">Ver usuarios</a>
                    </div>
                </td>
            </tr>
            <!-- MODAL VER USUARIOS -->
            <div class="modal fade" id="modal{{$rol->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ver usuarios</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if($rol->users->isEmpty())
                            No tiene usuarios
                            @else
                            <div class="card">

                                <ul class="list-group list-group-flush">
                                    @foreach ($rol->users as $user)
                                    <li class="list-group-item">{{ $user->nombre }} {{ $user->apellido }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL EDITAR -->
            <div class="modal fade" id="editRoleModal{{$rol->id}}" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editRoleModalLabel">Editar Rol</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('roles.update', [$rol->id]) }}">
                                @method('PATCH')
                                @csrf

                                <div class="mb-3 col">
                                    @error('nombre')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control mb-2" name="nombre" id="exampleFormControlInput1" placeholder="Escriba el contenido..." value="{{ $rol->nombre }}">


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


<!-- Modal Crear Rol -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModalLabel">Crear Nuevo Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre">
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection