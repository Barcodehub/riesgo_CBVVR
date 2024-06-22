<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion del Riesgo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/1193c03dcb.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="row col-8 border p-4 mt-5 mx-auto">
        <h3>Formulario de registro</h3>
        <form class="row g-3" method="POST" action="{{route('validar-registro')}}">
            @csrf
            <div class="col-md-6">
                <label for="inputNombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="inputNombre" name="nombre">
            </div>
            <div class="col-md-6">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido">
            </div>

            <div class="col-md-6">
                <label for="documento" class="form-label">Número de documento</label>
                <input type="text" class="form-control" id="documento" name="documento">
            </div>

            <div class="col-md-6">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono">
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            

            <div>
                <p>¿Ya tienes cuenta? <a href="{{route('login')}}">Inicia sesión</a></p>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </div>
        </form>
    </div>
</body>