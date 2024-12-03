@extends('cliente.dashboard')

@section('content')

<head>
    <link rel="stylesheet" href="{{ asset('css/cards.css') }}">

</head>

<div class="card m-4">
    <div class="card-header">
        <h4 class="card-title">Bienvenido {{ Auth::user()->nombre }}!</h4>
    </div>
</div>

<div class="d-flex justify-content-center flex-wrap gap-4 mt-4">
    <!-- Tarjeta 1 -->
    <div class="flip-card">
        <div class="flip-card-inner">
            <!-- Frente -->
            <div class="flip-card-front">
                <img src="{{ asset('images/inspeccion.jpg') }}" alt="Inspecciones" class="card-img-top">
                <h5>Inspecciones</h5>
            </div>
            <!-- Reverso -->
            <div class="flip-card-back">
                <h5>¿Por qué realizar inspecciones?</h5>
                <p>Las inspecciones ayudan a identificar riesgos potenciales y mejorar la seguridad.</p>
            </div>
        </div>
    </div>

    <!-- Tarjeta 2 -->
    <div class="flip-card">
        <div class="flip-card-inner">
            <!-- Frente -->
            <div class="flip-card-front">
                <img src="{{ asset('images/capacitacion.jpg') }}" alt="Capacitaciones" class="card-img-top">
                <h5>Capacitaciones</h5>
            </div>
            <!-- Reverso -->
            <div class="flip-card-back">
                <h5>¿Por qué capacitar al personal?</h5>
                <p>La capacitación mejora las habilidades y garantiza respuestas efectivas ante emergencias.</p>
            </div>
        </div>
    </div>

    <!-- Tarjeta 3 -->
    <div class="flip-card">
        <div class="flip-card-inner">
            <!-- Frente -->
            <div class="flip-card-front">
                <img src="{{ asset('images/certificado.jpg') }}" alt="Reportes" class="card-img-top">
                <h5>Certificados</h5>
            </div>
            <!-- Reverso -->
            <div class="flip-card-back">
                <h5>¿Por qué generar Certificación?</h5>
                <p>Para garantizar la seguridad, el cumplimiento legal y la tranquilidad en cualquier entorno.</p>
            </div>
        </div>
    </div>
</div>

@endsection