@extends('admin.dashboard')

@section('content')

<div class="w-full border p-4 m-4">

    @if (session('success'))
    <h6 class="alert alert-success">{{ session('success') }}</h6>
    @endif

    <h4 class="mb-4">Gestión de Roles: </h4>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if ($roles->isEmpty())
            <tr>
                <td colspan="3" class="text-center">
                    No hay roles
                </td>
            </tr>
            @endif
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
            @endforeach
        </tbody>
    </table>
</div>

@endsection