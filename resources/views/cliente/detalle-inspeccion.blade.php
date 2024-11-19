@extends('cliente.dashboard')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4">Seguimiento de Inspecciones</h2>

        <!-- Acordeón para cada empresa -->
        <div class="accordion" id="accordionEmpresas">
            @foreach ($companies as $company)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-{{ $loop->index }}">
                        <button class="accordion-button {{ $loop->index > 0 ? 'collapsed' : '' }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loop->index }}"
                            aria-expanded="{{ $loop->index === 0 ? 'true' : 'false' }}"
                            aria-controls="collapse-{{ $loop->index }}">
                            {{ $company->razon_social }} - {{ $company->direccion }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $loop->index }}"
                        class="accordion-collapse collapse {{ $loop->index === 0 ? 'show' : '' }}"
                        aria-labelledby="heading-{{ $loop->index }}" data-bs-parent="#accordionEmpresas">
                        <div class="accordion-body">

                            <!-- Información de la Empresa -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="telefono-{{ $loop->index }}" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono-{{ $loop->index }}"
                                        value="{{ $company->telefono }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="email-{{ $loop->index }}" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email-{{ $loop->index }}"
                                        value="{{ $company->email }}" readonly>
                                </div>
                            </div>

                            <!-- Inspecciones -->
                            <h4 class="mt-3">Inspecciones</h4>
                            @if ($company->inspections->isEmpty())
                                <p>No hay inspecciones registradas para esta empresa.</p>
                            @else
                                <div class="row g-3">
                                    @foreach ($company->inspections as $inspeccion)
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">Estado: {{ $inspeccion->estado }}</h5>
                                                    <p class="card-text">
                                                        <strong>Fecha:</strong> {{ $inspeccion->fecha_solicitud }}<br>
                                                        <strong>Inspector:</strong>
                                                        {{ $inspeccion->user->nombre ?? 'No asignado' }}<br>
                                                        <strong>Valor:</strong> ${{ number_format($inspeccion->valor, 2) }}
                                                    </p>
                                                    <!-- Botón que activa el modal -->
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#detalleModal-{{ $inspeccion->id }}">
                                                        Ver Detalle
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="detalleModal-{{ $inspeccion->id }}" tabindex="-1"
                                            aria-labelledby="detalleModalLabel-{{ $inspeccion->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="detalleModalLabel-{{ $inspeccion->id }}">Detalles de la
                                                            Inspección</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Estado:</strong> {{ $inspeccion->estado }}</p>
                                                        <p><strong>Fecha de Solicitud:</strong>
                                                            {{ $inspeccion->fecha_solicitud }}</p>
                                                        <p><strong>Inspector:</strong>
                                                            {{ $inspeccion->user->nombre ?? 'No asignado' }}</p>
                                                        <p><strong>Valor:</strong>
                                                            ${{ number_format($inspeccion->valor, 2) }}</p>
                                                        @php
                                                            // Si no hay un concepto, asignamos el concepto de la inspección
                                                            $concept = $concept ?? $inspeccion->concept;
                                                        @endphp
                                                        @if ($inspeccion->concept && !$inspeccion->concept->isEmpty())
                                                            <p><strong>Fecha Concepto:</strong>
                                                                {{ $inspeccion->concept->fecha_concepto }}</p>
                                                            <p><strong>Fecha de Vencimiento:</strong>
                                                                {{ \Carbon\Carbon::parse($inspeccion->concept->fecha_concepto)->addYear()->format('Y-m-d') }}
                                                            </p>
                                                        @else
                                                            <p>No hay conceptos asociados a esta inspección.</p>
                                                        @endif

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
