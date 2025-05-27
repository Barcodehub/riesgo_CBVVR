@extends('admin.dashboard')

@section('content')
    <h1>{{ isset($risk) ? 'Editar' : 'Crear' }} Riesgo</h1>

    <form action="{{ isset($risk) ? route('risks.update', $risk->id) : route('risks.store') }}" method="POST">
        @csrf
        @if(isset($risk))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="company_id" class="form-label">Empresa</label>
            <select name="company_id" id="company_id" class="form-control" required>
                <option value="">Seleccione una empresa</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ (isset($risk) && $risk->company_id == $company->id) ? 'selected' : '' }}>
                        {{ $company->razon_social }} - {{ $company->nombre_establecimiento }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nombre del Riesgo</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $risk->name ?? old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ $risk->description ?? old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="risk_type" class="form-label">Tipo de Riesgo</label>
            <input type="text" class="form-control" id="risk_type" name="risk_type" value="{{ $risk->risk_type ?? old('risk_type') }}" required>
        </div>

        <div class="mb-3">
            <label for="severity" class="form-label">Severidad</label>
            <select name="severity" id="severity" class="form-control" required>
                <option value="baja" {{ (isset($risk) && $risk->severity == 'baja') ? 'selected' : '' }}>Baja</option>
                <option value="media" {{ (isset($risk) && $risk->severity == 'media') ? 'selected' : '' }}>Media</option>
                <option value="alta" {{ (isset($risk) && $risk->severity == 'alta') ? 'selected' : '' }}>Alta</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="mitigation_measures" class="form-label">Medidas de Mitigación</label>
            <textarea class="form-control" id="mitigation_measures" name="mitigation_measures" rows="3">{{ $risk->mitigation_measures ?? old('mitigation_measures') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Ubicación (Google Maps)</label>
            <div id="map" style="height: 400px; width: 100%;"></div>
            <input type="hidden" id="latitude" name="latitude" value="{{ $risk->latitude ?? old('latitude') }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ $risk->longitude ?? old('longitude') }}">
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($risk) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('risks.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    @push('scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.maps.access_token') }}&callback=initMap&libraries=places&v=weekly" async defer></script>
        <script>
            function initMap() {
                const initialLat = {{ $risk->latitude ?? '4.5709' }};
                const initialLng = {{ $risk->longitude ?? '-74.2973' }};
                
                const map = new google.maps.Map(document.getElementById("map"), {
                    center: { lat: initialLat, lng: initialLng },
                    zoom: 15,
                });

                let marker = new google.maps.Marker({
                    position: { lat: initialLat, lng: initialLng },
                    map: map,
                    draggable: true
                });

                // Actualizar coordenadas cuando el marcador se mueve
                google.maps.event.addListener(marker, 'dragend', function() {
                    document.getElementById('latitude').value = marker.getPosition().lat();
                    document.getElementById('longitude').value = marker.getPosition().lng();
                });

                // Permitir al usuario hacer clic en el mapa para colocar el marcador
                google.maps.event.addListener(map, 'click', function(event) {
                    marker.setPosition(event.latLng);
                    document.getElementById('latitude').value = event.latLng.lat();
                    document.getElementById('longitude').value = event.latLng.lng();
                });

                // Autocompletado para la dirección
                const input = document.createElement('input');
                input.type = 'text';
                input.placeholder = 'Buscar dirección';
                input.style.marginTop = '10px';
                input.style.padding = '5px';
                input.style.width = '100%';
                
                document.getElementById('map').parentNode.insertBefore(input, document.getElementById('map').nextSibling);
                
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', map);
                
                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    if (!place.geometry) {
                        return;
                    }
                    
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }
                    
                    marker.setPosition(place.geometry.location);
                    document.getElementById('latitude').value = place.geometry.location.lat();
                    document.getElementById('longitude').value = place.geometry.location.lng();
                });
            }
        </script>
    @endpush
@endsection