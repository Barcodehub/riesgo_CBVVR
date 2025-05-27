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
    @stack('scripts')
    <nav class="navbar bg-dark border-bottom border-body navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('companies.index') }}">Empresas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inspections.index') }}">Inspecciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('roles.index') }}">Roles</a>
                    </li>
                    </li>
                        <li class="nav-item">
                        <a class="nav-link" href="{{ route('risks.index') }}">Riesgos</a> <!-- Nuevo enlace -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('extintores.index') }}">Tipo de Extintores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kits.index') }}">Tipo de Botiquines</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{{ route('risks.map') }}">Mapa de Riesgos</a>
                </li>

                    <li class="nav-item">
                        
                        
                        <form action="{{ route('huella.create', ['id' => 1]) }}" method="POST">
                            @csrf
                            <button type="submit">Crear Huella</button>
                        </form>

                    </li>


                </ul>
                <span class="navbar-text d-flex">
                    <span class="me-4">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</span>
                    <a class="nav-link" href="{{ route('logout') }}"><i class="fa-solid fa-power-off me-4"></i></a>
                </span>
            </div>
        </div>
    </nav>



    @yield('content')


    @if ($errors->any())
    <div class="w-full p-4">
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            <p class="m-0">{{ $error }}</p>
            @endforeach
        </div>
    </div>
    @endif


    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            console.log("hola", forms)

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

</body>

</html>