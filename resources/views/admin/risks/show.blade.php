@extends('admin.dashboard')
@section('content')
    <h1>Detalles del Riesgo</h1>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $risk->name }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{ $risk->company->razon_social }}</h6>
            
            <p class="card-text">
                <strong>Tipo:</strong> {{ $risk->risk_type }}<br>
                <strong>Severidad:</strong> 
                <span class="badge bg-{{ 
                    $risk->severity == 'alta' ? 'danger' : 
                    ($risk->severity == 'media' ? 'warning' : 'success') 
                }}">
                    {{ ucfirst($risk->severity) }}
                </span><br>
                <strong>Descripción:</strong> {{ $risk->description }}<br>
                <strong>Medidas de Mitigación:</strong> {{ $risk->mitigation_measures ?? 'N/A' }}
            </p>
            
            <div id="map" style="height: 400px; width: 100%;"></div>
            
            <div class="mt-3">
                <a href="{{ route('risks.edit', $risk->id) }}" class="btn btn-warning">Editar</a>
                <form action="{{ route('risks.destroy', $risk->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                </form>
                <a href="{{ route('risks.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>

    @if($risk->inspections->count() > 0)
        <h3 class="mt-4">Inspecciones Relacionadas</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Inspector</th>
                    <th>Estado</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($risk->inspections as $inspection)
                    <tr>
                        <td>{{ $inspection->fecha_solicitud }}</td>
                        <td>{{ $inspection->user->name }}</td>
                        <td>{{ $inspection->estado }}</td>
                        <td>${{ number_format($inspection->valor, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @push('scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.maps.access_token') }}&callback=initMap&v=weekly" async defer></script>
        <script>
            function initMap() {
                const map = new google.maps.Map(document.getElementById("map"), {
                    center: { lat: {{ $risk->latitude ?? '4.5709' }}, lng: {{ $risk->longitude ?? '-74.2973' }} },
                    zoom: 15,
                });
                
                new google.maps.Marker({
                    position: { lat: {{ $risk->latitude ?? '4.5709' }}, lng: {{ $risk->longitude ?? '-74.2973' }} },
                    map: map,
                    title: "{{ $risk->name }}"
                });
            }
        </script>
    @endpush
@endsection