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

    .fingerprint-btn {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  padding: 14px 28px;
  background: linear-gradient(135deg, #6e8efb, #4a6cf7);
  color: white;
  border: none;
  border-radius: 50px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(74, 108, 247, 0.3);
  position: relative;
  overflow: hidden;
}

.fingerprint-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: 0.5s;
}

.fingerprint-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(74, 108, 247, 0.4);
}

.fingerprint-btn:hover::before {
  left: 100%;
}

.fingerprint-btn i {
  font-size: 20px;
  transition: transform 0.3s ease;
}

.fingerprint-btn:hover i {
  transform: scale(1.1);
}
  </style>
</head>

<body>
  <div class="fondo-login">

    <div class="card row col-6 border p-4 mt-5 mx-auto">
      <h3 class="mb-4">Iniciar sesión</h3>
      @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <p class="m-0">{{ $error }}</p>
        @endforeach
      </div>
      @endif

      <form method="POST" action="{{ route('inicia-sesion') }}" class="needs-validation" novalidate>
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com" required> 
          <div class="invalid-feedback">
            Complete este campo.
          </div>
        </div>
        <div class="mb-3">
          <label for="passsord" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="passsord" name="password" placeholder="******" required>
          <div class="invalid-feedback">
            Complete este campo.
          </div>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember" name="remember">
          <label class="form-check-label" for="remember">Mantener sesión iniciada</label>
        </div>

        <div>
          <p>¿No tienes cuenta? <a href="{{route('registro')}}">Regístrate</a></p>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
         
        
      </form>
     <form action="/login-huella" method="POST">
            @csrf
            <button type="submit" class="fingerprint-btn" aria-label="Iniciar sesión con huella digital">
      <i class="fas fa-fingerprint"></i></button>
         </form> 
        </div>
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