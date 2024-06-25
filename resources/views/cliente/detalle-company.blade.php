@extends('cliente.dashboard')

@section('content')

<div class="w-full border p-4 m-4">
<div class="mb-3 row g-3">

<div class="col-6">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" placeholder="Escriba el nombre..." class="form-control" id="nombre" name="nombre">
</div>

<div class="col-6">
    <label for="representante_legal" class="form-label">Representante Legal</label>
    <input type="text" placeholder="Escriba el nombre del representante legal" class="form-control" id="representante_legal" name="representante_legal">
</div>
</div>

<div class="mb-3 row g-3">
<div class="col-6">
    <label for="nit" class="form-label">Nit</label>
    <input type="text" placeholder="Escriba el nit" class="form-control" id="nit" name="nit">
</div>

<div class="col-6">
    <label for="direccion" class="form-label">Dirección</label>
    <input type="text" placeholder="Escriba la dirección" class="form-control" id="direccion" name="direccion">
</div>
</div>

<div class="mb-3 row g-3">

<div class="col-6">
    <label for="telefono" class="form-label">Teléfono</label>
    <input type="text" placeholder="Escriba el número de teléfono" class="form-control" id="telefono" name="telefono">
</div>

<div class="col-6">
    <label for="email" class="form-label">Email</label>
    <input type="email" placeholder="Escriba el correo electrónico" class="form-control" id="email" name="email">
</div>
</div>

<div class="mb-3">
<label for="actividad_comercial" class="form-label">Actividad Comercial</label>
<input type="text" placeholder="Escriba la actividad comercial" class="form-control" id="actividad_comercial" name="actividad_comercial">
</div>

<div class="mb-3 row g-3">

<div class="col-6">
    <label for="ancho_dimensiones" class="form-label">Dimensiones Ancho</label>
    <input type="number" placeholder="Digite las medidas del ancho" class="form-control" id="ancho_dimensiones" name="ancho_dimensiones">
</div>

<div class="col-6">
    <label for="largo_dimensiones" class="form-label">Dimensiones Largo</label>
    <input type="number" placeholder="Digite las medidas del largo" class="form-control" id="largo_dimensiones" name="largo_dimensiones">
</div>
</div>

<div class="mb-3">
<label for="num_pisos" class="form-label">Número de Pisos</label>
<select class="form-select" placeholder="Seleccione" name="num_pisos" id="num_pisos">
    @foreach ($opcionesPisos as $opcion)
    <option value="{{ $opcion }}">{{$opcion}}</option>
    @endforeach
</select>
</div>


<div class="mb-3">
<label for="rut" class="form-label">Copia del RUT, vigencia no superior los treinta (30) días *</label>
<input class="form-control" type="file" id="rut" accept=".pdf" name="rut">
</div>
<div class="mb-3">
<label for="camara_comercio" class="form-label">Copia del certificado de existencia y representación Legal (Cámara de comercio), vigencia no superior los treinta (30) días. *</label>
<input class="form-control" type="file" id="camara_comercio" accept=".pdf" name="camara_comercio">
</div>



<div class="mb-3">
<label for="cedula" class="form-label">Copia de la cédula de ciudadanía del representante legal *</label>
<input class="form-control" type="file" id="cedula" name="cedula" accept=".pdf">
</div>
<div class="mb-5">
<label for="fachada" class="form-label">Fotografía de la fachada del establecimiento a inspeccionar *</label>
<input class="form-control" type="file" id="fachada" name="fachada" accept="image/*">
</div>
</div>


@endsection