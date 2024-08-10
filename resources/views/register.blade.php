<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion del Riesgo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/1193c03dcb.js" crossorigin="anonymous"></script>
    <style>
        .fondo-login {
            background-image: url('/images/login.avif');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="fondo-login">

        <div class="card row col-8 border p-4 mt-5 mx-auto">
            <h3>Formulario de registro</h3>

            @if ($errors->any())
            <div class="alert alert-danger">
                <p class="m-0">{{ $errors->first() }}</p>
            </div>
            @endif

            <form method="POST" action="{{route('validar-registro')}}" class="needs-validation" novalidate>
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" placeholder="Digite este campo" id="nombre" name="nombre" required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="apellido" class="form-label">Apellido *</label>
                        <input type="text" class="form-control" placeholder="Digite este campo" id="apellido" name="apellido" required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>
    
                    <div class="col-md-6">
                        <label for="documento" class="form-label">Número de documento *</label>
                        <input type="text" class="form-control" placeholder="Digite este campo" id="documento" name="documento" required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>
    
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono *</label>
                        <input type="text" class="form-control" placeholder="Digite este campo" id="telefono" name="telefono" required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>
    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" placeholder="Digite este campo" id="email" name="email" required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" placeholder="Digite este campo" id="password" name="password" required>
                        <div class="invalid-feedback">
                            Complete este campo.
                        </div>
                    </div>
    
    
                    <div>
                        <p>¿Ya tienes cuenta? <a href="{{route('login')}}">Inicia sesión</a></p>
                    </div>
    
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </div>
            </form>
        </div>
    </div>
</body>

<script>
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>