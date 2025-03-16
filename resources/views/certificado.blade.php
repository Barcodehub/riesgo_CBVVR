<!DOCTYPE html>
<html>
<head>
    <title>Certificado de Inspección</title>
    <style>
        /* Ajustes para tamaño Carta */
        @page {
            size: letter; /* Tamaño Carta (8.5 x 11 pulgadas) */
            margin: 20mm; /* Márgenes en milímetros */
        }

        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        /* Contenedor del certificado */
        .certificado {
            max-width: 612px; /* 8.5 pulgadas en 72 DPI */
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            page-break-inside: avoid; /* Evita cortes dentro del certificado */
        }

        /* Encabezado */
        .encabezado {
            text-align: center;
            margin-bottom: 15px;
        }

        .encabezado img {
            width: 100%;
            max-width: 500px;
            height: auto;
        }

        .encabezado h1 {
            font-size: 20px;
            color: #333;
            margin-top: 5px;
        }

        /* Cuerpo del certificado */
        .cuerpo h2 {
            font-size: 18px;
            color: #555;
            margin-bottom: 5px;
        }

        .cuerpo p {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
            word-wrap: break-word;
            overflow: hidden;
        }

        .detalle {
            margin-top: 10px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        /* Pie de página */
        .pie {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="certificado">
        <!-- Encabezado con imagen -->
        <div class="encabezado">
            <img src="{{ public_path('images/encabezado2.png') }}" alt="Encabezado del Certificado">
            <h1>Certificado de Inspección</h1>
            {{-- <h2>Cuerpo de Bomberos Voluntarios de Villa del Rosario</h2>
            <p>NIT: 807000496-7</p> --}}
        </div>

        <!-- Cuerpo del certificado -->
        <div class="cuerpo">
            <!-- Información del Establecimiento -->
            <div class="seccion">
                <h3>Información del Establecimiento</h3>
                <p><strong>Establecimiento:</strong> {{ $inspection->company->nombre_establecimiento }}</p>
                <p><strong>Dirección:</strong> {{ $inspection->company->direccion }}</p>
                <p><strong>Teléfono:</strong> {{ $inspection->company->telefono }}</p>
                <p><strong>Actividad Comercial:</strong> {{ $inspection->company->actividad_comercial }}</p>
            </div>

            <!-- Detalles de la Inspección -->
            <div class="seccion">
                <h3>Detalles de la Inspección</h3>
                <p><strong>Fecha de Inspección:</strong> {{ $inspection->fecha_solicitud }}</p>
                <p><strong>Inspector:</strong> {{ $inspection->user->nombre }} {{ $inspection->user->apellido }}</p>
                <p><strong>Estado:</strong> {{ $inspection->estado }}</p>
            </div>

            <!-- Resultados de la Inspección -->
            @if ($concept)
                <div class="seccion">
                    <h3>Resultados de la Inspección (Observaciones)</h3>
                    <p>Equipo Contra Incendios</p>
                    <p><strong>Sistema Automatico:</strong> {{ optional($concept->equipo_incendio)->observaciones_sa ?? 'N/A' }}</p>
                    <p><strong>Hidrantes:</strong> {{ optional($concept->equipo_incendio)->observaciones_hyr ?? 'N/A' }}</p>
                    <p><strong>Extintores:</strong> {{ optional($concept->equipo_incendio)->observaciones ?? 'N/A' }}</p>
                    <p><strong>Sistema Eléctrico:</strong> {{ optional($concept->sistema_electrico)->observaciones ?? 'N/A' }}</p>
                    <p><strong>Sistema de Iluminación:</strong> {{ optional($concept->sistema_iluminacion)->observaciones ?? 'N/A' }}</p>
                    <p><strong>Rutas de Evacuación:</strong> {{ optional($concept->ruta_evacuacion)->observaciones ?? 'N/A' }}</p>
                    <p><strong>Primeros Auxilios:</strong> {{ optional($concept->primeros_auxilios)->observaciones ?? 'N/A' }}</p>
                    <p>Almacenamiento de Combustibles</p>
                    <p><strong>Material Sólido Ordinario:</strong> {{ optional($concept->almacenamiento)->observaciones_1 ?? 'N/A' }}</p>
                    <p><strong>Material liquido inflamable:</strong> {{ optional($concept->almacenamiento)->observaciones_2 ?? 'N/A' }}</p>
                    <p><strong>Material gaseoso inflamable:</strong> {{ optional($concept->almacenamiento)->observaciones_3 ?? 'N/A' }}</p>
                    <p><strong>Otros Quimicos:</strong> {{ optional($concept->almacenamiento)->observaciones_4 ?? 'N/A' }}</p>

                  
                  
                    <p><strong>Otras Condiciones:</strong> {{ optional($concept->otras_condiciones)->observacion ?? 'N/A' }}</p>                                        
                </div>

                <!-- Recomendaciones -->
                <div class="seccion">
                    <h3>Recomendaciones</h3>
                    <p>{{ $concept->recomendaciones }}</p>
                </div>
            @else
                <div class="seccion">
                    <p>No se encontró un concepto asociado a esta inspección.</p>
                </div>
            @endif
        </div>

        <!-- Pie de página -->
        <div class="pie">
            <p>Este certificado es generado automáticamente por el sistema de inspecciones.</p>
            <p>Fecha de emisión: {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>