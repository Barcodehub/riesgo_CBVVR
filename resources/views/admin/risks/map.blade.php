@extends('admin.dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h1>Mapa de Empresas con Riesgos</h1>
                <div id="map" style="height: 80vh; width: 100%;"></div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Información detallada</h5>
                    </div>
                    <div class="card-body" id="info-panel">
                        <p class="text-muted">Seleccione una empresa en el mapa para ver detalles</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .marker-high-risk {
                background-color: red;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                opacity: 0.7;
            }
            .marker-medium-risk {
                background-color: orange;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                opacity: 0.7;
            }
            .marker-low-risk {
                background-color: green;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                opacity: 0.7;
            }
            .risk-badge {
                font-size: 0.8em;
                margin-right: 5px;
                margin-bottom: 5px;
            }
        </style>
    @endpush

    
    @push('scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.maps.access_token') }}&libraries=places&v=weekly" async defer></script>
        <script>
            let map;
            let markers = [];
            const mapData = @json($mapData);

            function initMap() {
                // Centro inicial (promedio de todas las ubicaciones o ubicación por defecto)
                const centerLat = mapData.reduce((sum, company) => sum + company.lat, 0) / mapData.length || 4.5709;
                const centerLng = mapData.reduce((sum, company) => sum + company.lng, 0) / mapData.length || -74.2973;
                
                map = new google.maps.Map(document.getElementById("map"), {
                    center: { lat: centerLat, lng: centerLng },
                    zoom: 12,
                });

                // Crear marcadores para cada empresa
                mapData.forEach(company => {
                    // Determinar el peor riesgo para el color del marcador
                    const hasHighRisk = company.risks.some(r => r.severity === 'alta');
                    const hasMediumRisk = company.risks.some(r => r.severity === 'media');
                    
                    const markerClass = hasHighRisk ? 'marker-high-risk' : 
                                      hasMediumRisk ? 'marker-medium-risk' : 'marker-low-risk';
                    
                    const marker = new google.maps.Marker({
                        position: { lat: company.lat, lng: company.lng },
                        map: map,
                        title: company.name,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            fillColor: hasHighRisk ? 'red' : hasMediumRisk ? 'orange' : 'green',
                            fillOpacity: 0.7,
                            strokeWeight: 0,
                            scale: 10
                        }
                    });
                    
                    // InfoWindow content
                    const content = `
                        <div class="map-info-window">
                            <h5>${company.name}</h5>
                            <p><strong>Establecimiento:</strong> ${company.establishment}</p>
                            <p><strong>Dirección:</strong> ${company.address}</p>
                            
                            <h6 class="mt-3">Riesgos (${company.risks.length})</h6>
                            <div class="d-flex flex-wrap">
                                ${company.risks.map(risk => `
                                    <span class="badge risk-badge 
                                        ${risk.severity === 'alta' ? 'bg-danger' : 
                                          risk.severity === 'media' ? 'bg-warning' : 'bg-success'}">
                                        ${risk.name}
                                    </span>
                                `).join('')}
                            </div>
                            
                            <h6 class="mt-3">Inspecciones (${company.inspections.length})</h6>
                            <ul class="list-unstyled">
                                ${company.inspections.map(insp => `
                                    <li>
                                        <small>${insp.date} - ${insp.status} (${insp.inspector})</small>
                                    </li>
                                `).join('')}
                            </ul>
                            
                            <a href="/companies/${company.id}" class="btn btn-sm btn-primary mt-2">Ver detalles</a>
                        </div>
                    `;
                    
                    const infowindow = new google.maps.InfoWindow({
                        content: content
                    });
                    
                    marker.addListener('click', () => {
                        // Cerrar todas las otras ventanas de información
                        markers.forEach(m => {
                            if (m.infowindow) m.infowindow.close();
                        });
                        
                        infowindow.open(map, marker);
                        updateInfoPanel(company);
                    });
                    
                    // Guardar referencia al marcador y su infowindow
                    marker.infowindow = infowindow;
                    markers.push(marker);
                });
            }
            
            function updateInfoPanel(company) {
                const panel = document.getElementById('info-panel');
                
                panel.innerHTML = `
                    <h4>${company.name}</h4>
                    <p><strong>Establecimiento:</strong> ${company.establishment}</p>
                    <p><strong>Dirección:</strong> ${company.address}</p>
                    
                    <h5 class="mt-4">Riesgos</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Nombre</th>
                                    <th>Severidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${company.risks.map(risk => `
                                    <tr>
                                        <td>${risk.type}</td>
                                        <td>${risk.name}</td>
                                        <td>
                                            <span class="badge ${risk.severity === 'alta' ? 'bg-danger' : 
                                              risk.severity === 'media' ? 'bg-warning' : 'bg-success'}">
                                                ${risk.severity}
                                            </span>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    
                    <h5 class="mt-4">Inspecciones</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Inspector</th>
                                    <th>Estado</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${company.inspections.map(insp => `
                                    <tr>
                                        <td>${insp.date}</td>
                                        <td>${insp.inspector}</td>
                                        <td>${insp.status}</td>
                                        <td>$${insp.value}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-grid gap-2 mt-3">
                        <a href="/companies/${company.id}" class="btn btn-primary">Ver empresa</a>
                        <a href="/risks?company_id=${company.id}" class="btn btn-secondary">Ver riesgos</a>
                    </div>
                `;
            }
            
            // Inicializar el mapa cuando la API esté cargada
            window.initMap = initMap;
        </script>
    @endpush
@endsection