@extends('cliente.dashboard')

@section('content')
<div class="w-full border p-4 m-4">
    <h4 class="mb-4">Histórico de Inspecciones</h4>

    @if (session('success'))
        <h6 class="alert alert-success">{{ session('success') }}</h6>
    @endif

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Fecha de Solicitud</th>
                <th scope="col">Estado</th>
                <th scope="col">Certificado</th>
            </tr>
        </thead>
        <tbody>
            @if ($inspections->isEmpty())
                <tr>
                    <td colspan="4" class="text-center">No hay inspecciones registradas.</td>
                </tr>
            @else
                @foreach ($inspections as $inspection)
                    <tr>
                        <td>{{ $inspection->id }}</td>
                        <td>{{ $inspection->fecha_solicitud }}</td>
                        <td>{{ $inspection->estado }}</td>
                        <td>
                            @if ($inspection->estado == 'FINALIZADA' && $inspection->certificado_url)
                                <a href="{{ route('cliente.descargar-certificado', $inspection->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-download"></i> Descargar Certificado
                                </a>
                            @else
                                <span class="text-muted">No disponible</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection