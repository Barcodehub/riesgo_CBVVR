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
    <nav class="navbar bg-dark border-bottom border-body navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('cliente.dashboard') }}">Cliente</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cliente.datosEmpresa') }}">Datos de Empresa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cliente.datosEmpresa') }}">Ver Cotización</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <a class="nav-link" href="{{ route('logout') }}"><i class="fa-solid fa-power-off me-4"></i></a>
                </span>
            </div>

            
        </div>
    </nav>

    <div class="w-full mx-5 my-4">
        @auth

        @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            <p class="m-0">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <h3>Hola cliente {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</h3>

        @endauth

    </div>


    @yield('content')
</body>

</html>