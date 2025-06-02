@echo off
setlocal

:: Configuración de rutas
set JAVA_HOME="C:\Program Files\Eclipse Adoptium\jdk-8.0.372.7-hotspot"
set PATH=%JAVA_HOME%\bin;%PATH%

:: Usar DLLs de la carpeta local
set DLL_PATH=%~dp0dll


:: Ejecutar el servidor
echo Iniciando servidor biométrico...
echo Ruta DLLs: %DLL_PATH%

java -Djava.library.path="%DLL_PATH%" ^
     -cp "target\classes;lib\*" ^
     com.digitalpersona.integration.BiometricServer

endlocal