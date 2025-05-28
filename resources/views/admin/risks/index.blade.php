@extends('admin.dashboard')

@section('content')

<div class="w-full border p-4 m-4">
    <h1>Gestión de Riesgos</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('risks.create') }}" class="btn btn-primary mb-3">Crear Nuevo Riesgo</a>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Empresa</th>
                <th>Nombre del Riesgo</th>
                <th>Tipo</th>
                <th>Severidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($risks as $risk)
                <tr>
                    <td>{{ $risk->company->razon_social }}</td>
                    <td>{{ $risk->name }}</td>
                    <td>{{ $risk->risk_type }}</td>
                    <td>
                        <span class="badge bg-{{ 
                            $risk->severity == 'alta' ? 'danger' : 
                            ($risk->severity == 'media' ? 'warning' : 'success') 
                        }}">
                            {{ ucfirst($risk->severity) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('risks.show', $risk->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('risks.edit', $risk->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('risks.destroy', $risk->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
 </div>    
@endsection