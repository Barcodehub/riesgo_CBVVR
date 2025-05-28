@extends('admin.dashboard')
@section('content')

<div class="w-full border p-4 m-4">
    <h1 class="text-2xl font-bold mb-4">Detalles del Riesgo</h1>
    
    <div class="card mb-6">
        <div class="card-body">
            <h5 class="card-title text-xl font-semibold">{{ $risk->name }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">
                {{ $risk->company ? $risk->company->razon_social : 'Empresa no asignada' }}
            </h6>
            
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <p><strong class="font-semibold">Tipo:</strong> {{ $risk->risk_type }}</p>
                    <p>
                        <strong class="font-semibold">Severidad:</strong> 
                        <span class="badge bg-{{ 
                            $risk->severity == 'alta' ? 'danger' : 
                            ($risk->severity == 'media' ? 'warning' : 'success') 
                        }}">
                            {{ ucfirst($risk->severity) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p><strong class="font-semibold">Descripción:</strong> {{ $risk->description }}</p>
                    <p><strong class="font-semibold">Medidas de Mitigación:</strong> {{ $risk->mitigation_measures ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div id="map" style="height: 400px; width: 100%;" class="mb-4"></div>
            
       
        </div>
    </div>

@if($risk->concepts->count() > 0)
    <h3 class="text-xl font-bold mb-4">Conceptos de Inspección Relacionados</h3>
    
    <div class="accordion" id="conceptsAccordion">
        @foreach($risk->concepts as $index => $concept)
        <div class="accordion-item mb-4">
            <h2 class="accordion-header" id="heading{{ $index }}">
                <button class="accordion-button collapsed bg-gray-200 p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                    <div>
                        <h4 class="font-semibold">Concepto #{{ $concept->id }} - {{ $concept->fecha_concepto }}</h4>
                        <p class="text-sm mb-0">
                            Inspección #{{ $concept->inspection->id }} | 
                            Estado: 
                            @if($concept->favorable)
                                <span class="badge bg-success">Favorable</span>
                            @else
                                <span class="badge bg-danger">No Favorable</span>
                            @endif
                        </p>
                    </div>
                </button>
            </h2>
            
            <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#conceptsAccordion">
                <div class="accordion-body card-body">
                    <!-- Información del Establecimiento -->
                    @if($concept->infoestablecimiento)
                    <div class="mb-4">
                        <h5 class="font-semibold border-b pb-1">Información del Establecimiento</h5>
                        <!-- Agrega aquí los campos específicos que deseas mostrar -->
                    </div>
                    @endif
                    
                    <!-- Características de Construcción -->
                    @if($concept->construccion)
                    <div class="mb-4">
                        <h5 class="font-semibold border-b pb-1">Características de Construcción</h5>
                        <p><strong>Año:</strong> {{ $concept->construccion->anio_construccion }}</p>
                        <p><strong>Cumple NRS:</strong> {{ $concept->construccion->nrs ? 'Sí' : 'No' }}</p>
                        <p><strong>Cumple SST:</strong> {{ $concept->construccion->sst ? 'Sí' : 'No' }}</p>
                    </div>
                    @endif
                    
                    <!-- Sistema Eléctrico -->
                    @if($concept->sistema_electrico)
                    <div class="mb-4">
                        <h5 class="font-semibold border-b pb-1">Sistema Eléctrico</h5>
                        <p><strong>Breker identificado:</strong> {{ $concept->sistema_electrico->caja_distribucion_breker ? 'Sí' : 'No' }}</p>
                        <p><strong>Mantenimiento preventivo:</strong> {{ $concept->sistema_electrico->mantenimiento_preventivo ? 'Sí' : 'No' }}</p>
                        <p><strong>Periodicidad:</strong> {{ $concept->sistema_electrico->periodicidad }}</p>
                    </div>
                    @endif
                    
                    <!-- Equipos contra Incendio -->
                    @if($concept->equipo_incendio)
                    <div class="mb-4">
                        <h5 class="font-semibold border-b pb-1">Equipos contra Incendio</h5>
                        <p><strong>Sistema automático:</strong> {{ $concept->equipo_incendio->sistema_automatico ? 'Sí' : 'No' }}</p>
                        <p><strong>Red contra incendios:</strong> {{ $concept->equipo_incendio->red_contra_incendios ? 'Sí' : 'No' }}</p>
                        <p><strong>Hidrantes:</strong> {{ $concept->equipo_incendio->hidrantes ? 'Sí' : 'No' }}</p>
                        
                        @if($concept->equipo_incendio->extintores)
                        <h6 class="font-semibold mt-2">Extintores</h6>
                        <ul>
                            @foreach($concept->equipo_incendio->extintores as $extintor)
                            <li>{{ $extintor->tipo->nombre }} - Cantidad: {{ $extintor->cantidad }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Primeros Auxilios -->
                    @if($concept->primeros_auxilios)
                    <div class="mb-4">
                        <h5 class="font-semibold border-b pb-1">Primeros Auxilios</h5>
                        <p><strong>Botiquín:</strong> {{ $concept->primeros_auxilios->botiquin ? 'Sí' : 'No' }}</p>
                        <p><strong>Camilla:</strong> {{ $concept->primeros_auxilios->camilla ? 'Sí' : 'No' }}</p>
                        
                        @if($concept->primeros_auxilios->botiquines)
                        <h6 class="font-semibold mt-2">Botiquines</h6>
                        <ul>
                            @foreach($concept->primeros_auxilios->botiquines as $botiquin)
                            <li>{{ $botiquin->tipo->nombre }} - Cantidad: {{ $botiquin->cantidad }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Recomendaciones -->
                    <div class="mt-4">
                        <h5 class="font-semibold border-b pb-1">Recomendaciones</h5>
                        <p>{{ $concept->recomendaciones }}</p>
                    </div>
                    
                    <!-- Archivos Adjuntos -->
                    @if($concept->archivos->count() > 0)
                    <div class="mt-4">
                        <h5 class="font-semibold border-b pb-1">Archivos Adjuntos</h5>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach($concept->archivos as $archivo)
                            <div class="border p-2 rounded">
                                <a href="{{ asset($archivo->url) }}" target="_blank" class="block">
                                    <i class="fas fa-file-image text-3xl text-blue-500 mb-1"></i>
                                    <span class="text-sm truncate">{{ basename($archivo->url) }}</span>
                                </a>
                                <a href="{{ asset($archivo->url) }}" download class="text-xs text-blue-500">Descargar</a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info">No hay conceptos de inspección asociados a este riesgo.</div>
    @endif
</div>  
 
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