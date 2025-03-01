<!DOCTYPE html>
<html>
<head>
    <title>Certificado de Riesgo</title>
</head>
<body>
    <h1>Certificado de Riesgo</h1>
    <p>Establecimiento: {{ $inspection->company->nombre_establecimiento }}</p>
    <p>Fecha de Inspección: {{ $inspection->fecha_solicitud }}</p>
    <p>Estado: {{ $inspection->estado }}</p>
    <!-- Agrega más detalles según sea necesario -->
</body>
</html>