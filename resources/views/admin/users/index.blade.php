@extends('admin.dashboard')

@section('content')

<div class="w-full border p-4 m-4">

    @if (session('success'))
    <h6 class="alert alert-success">{{ session('success') }}</h6>
    @endif

    <h4 class="mb-4">Gestión de Usuarios: </h4>

    <div class="w-6 my-4">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            Crear usuario <i class="ps-2 fa-solid fa-plus"></i>
        </a>
    </div>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Documento</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Disponibilidad</th>
                <th scope="col">Email</th>
                <th scope="col">Rol</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if ($users->isEmpty())
            <tr>
                <td colspan="3" class="text-center">
                    No hay usuarios
                </td>
            </tr>
            @endif
            @foreach ($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->nombre}}</td>
                <td>{{$user->apellido}}</td>
                <td>{{$user->documento}}</td>
                <td>{{$user->telefono}}</td>
                <td>{{$user->disponibilidad == '1' ? 'Activo' : 'Inactivo'}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->role->nombre}}</td>
                <td>
                    <div class="flex justify-between items-center gap-2">
                        <a class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{$user->id}}">Editar</a>
                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{$user->id}}">Cambiar Estado</a>
                    </div>
                </td>
            </tr>
            <!-- MODAL ELIMINAR -->
            <div class="modal fade" id="modal{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cambiar disponibilidad</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Está seguro que desea cambiar la disponibilidad al usuario <strong>{{ $user->nombre }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                            <form action="{{ route('users.changeState', [$user->id]) }}" method="POST">
                                @method('PATCH')
                                @csrf
                                <button type="submit" class="btn btn-primary">Aceptar</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL EDITAR -->
            <div class="modal fade" id="editUserModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">Editar usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('users.update', [$user->id]) }}" class="needs-validation-update" novalidate>
                                @method('PATCH')
                                @csrf

                                <div class="mb-3 row g-3">
                                    <div class="col-6">
                                        <label for="nombre" class="form-label">Nombre *</label>
                                        <input type="text" class="form-control mb-2" name="nombre" id="nombre" placeholder="Escriba el nombre..." value="{{ $user->nombre }}" required>
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                    <div class="col-6">

                                        <label for="apellido" class="form-label">Apellido</label>
                                        <input type="text" class="form-control mb-2" name="apellido" id="apellido" placeholder="Escriba el apellido..." value="{{ $user->apellido }}" required>
                                        <div class="invalid-feedback">
                                            Complete este campo.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="documento" class="form-label">Documento *</label>
                                    <input type="text" class="form-control mb-2" name="documento" id="documento" placeholder="Escriba el documento..." value="{{ $user->documento }}" required>
                                    <div class="invalid-feedback">
                                        Complete este campo.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono *</label>
                                    <input type="text" class="form-control mb-2" name="telefono" id="telefono" placeholder="Escriba el teléfono..." value="{{ $user->telefono }}" required>
                                    <div class="invalid-feedback">
                                        Complete este campo.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control mb-2" name="email" id="email" placeholder="Escriba el contenido..." value="{{ $user->email }}" required>
                                    <div class="invalid-feedback">
                                        Por favor, ingrese un correo electrónico.
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label for="rol_id" class="form-label">Rol *</label>
                                    <select class="form-select" name="rol_id" id="rol_id" required>
                                        @foreach ($roles as $rol)
                                        <option value="{{ $rol->id }}" {{ $user->rol_id == $rol->id ? 'selected' : '' }}>{{ $rol->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione un rol.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch my-2">
                                        <label class="form-check-label" for="disponibilidad">Disponibilidad </label>
                                        <input class="form-check-input" type="checkbox" role="switch" id="disponibilidad" name="disponibilidad" {{ $user->disponibilidad ? 'checked' : '' }}>
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


<!-- Modal Crear user -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModalLabel">Crear Nuevo usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <form action="{{ route('users.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-3 row g-3">

                        <div class="col-6">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" placeholder="Escriba el nombre..." class="form-control" id="nombre" name="nombre" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>

                        <div class="col-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" placeholder="Escriba el apellido..." class="form-control" id="apellido" name="apellido" required>
                            <div class="invalid-feedback">
                                Complete este campo.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="documento" class="form-label">Documento *</label>
                        <input type="text" placeholder="Escriba el documento..." class="form-control" id="documento" name="documento" required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono *</label>
                        <input type="text" placeholder="Escriba el telefono..." class="form-control" id="telefono" name="telefono" required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" placeholder="Escriba el email..." class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">
                            Por favor, ingrese un correo electrónico
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña *</label>
                        <input type="password" placeholder="Escriba el password..." class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="rol_id" class="form-label">Rol *</label>
                        <select class="form-select" name="rol_id" id="rol_id" required>
                            <option selected disabled value="">Seleccione</option>
                            @foreach ($roles as $rol)
                            <option value="{{$rol->id}}">{{$rol->nombre}}</option>
                            @endforeach
                        </select>

                        <div class="invalid-feedback">
                            Seleccione un rol.
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch my-2">
                            <label class="form-check-label" for="disponibilidad">Disponibilidad </label>
                            <input class="form-check-input" type="checkbox" role="switch" id="disponibilidad" name="disponibilidad" checked>
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
            console.log("hola", forms)
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