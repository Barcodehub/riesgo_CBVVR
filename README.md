<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Página web para la gestión integral del proceso de inspecciones de protección contra incendios y seguridad humana del departamento de Gestión del Riesgo.

Sistema desarrollado con el framework de laravel


cliente(Crear empresa)->admin(infoEstablecimiento)->cliente(solicitudInspeccion)->admin(asignarInspector)->inspector(DarConcepto)->Finalizar->Descargar




Implementaciones completadas:
R31: Generacion de certificado de inspecciones por parte del inspector
R35: Agregado descarga de Certificado de inspecciones por parte del cliente
R39: Historico de certificados
-envio de puertos -
(boton crear huella biometrica simulado)
(simulador test_login biometrico - simula el login con la huella (sin hardware))
(ejecutar test, +boton)


instalar compose-artisan...
npm install
php artisan storage:link
npm run dev


Correr migraciones, si falla, revisar con status cual falta y agregar una a una manualmente las que se puedan
si quedan una o dos sin que funcione manualmente, conectarse a la bd localmente y hacer un DROP (eliminar la tabla) y volver a ejecutar el comando para migrar manualmente... correr el XAMPP

por si falla la ejecucion con php-serve:
php artisan config:clear
php artisan config:cache
php artisan serve --env=local




crear huella
test_server.py se puede probar la comunicacion = 1 con la simulacion de servicio de huella biometrico
curl -X POST http://127.0.0.1:8000/api/huella/crear/1
en el test de python = 1

crear huella con el boton

probar comunicacion hacia el puerto:
echo login | ncat 127.0.0.1 1234
1




curl -X POST http://127.0.0.1:8000/api/login-huella