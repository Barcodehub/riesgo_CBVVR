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

    <div class="card row col-6 border p-4 mt-5 mx-auto">
      @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <p class="m-0">{{ $error }}</p>
        @endforeach
      </div>
      @endif
      <form method="POST" action="{{ route('inicia-sesion') }}">
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com">
        </div>
        <div class="mb-3">
          <label for="passsord" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="passsord" name="password" placeholder="******">
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember" name="remember">
          <label class="form-check-label" for="remember">Mantener sesión iniciada</label>
        </div>

        <div>
          <p>¿No tienes cuenta? <a href="{{route('registro')}}">Regístrate</a></p>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
      </form>

    </div>

  </div>

</body>