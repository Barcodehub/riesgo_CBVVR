# Sistema de Inspecciones Municipales

Sistema desarrollado con el framework Laravel para la gestión integral de inspecciones municipales, generación automática de certificados de riesgo y geolocalización de empresas. (Integración de modulos y despliegue: practicas de Ingenieria de sistemas elaborada para Bomberos de Villa del Rosario)

## 📋 Objetivos Específicos

- Extender el módulo de inspecciones para incluir la generación automática de certificados de riesgo al finalizar las evaluaciones
- Desarrollar un módulo que permita gestionar riesgos asociados a las empresas del municipio, integrando servicios de geolocalización mediante Google Maps
- Integrar la conexión del aplicativo con el software de registro de minutas, asegurando la sincronización de datos
- Configurar el despliegue del sistema en ambiente de producción cumpliendo con los requisitos de disponibilidad y rendimiento

## 🚀 Características Principales

### Módulo de Inspecciones
- Generación automática de certificados de inspección
- Flujo completo desde solicitud hasta descarga
- Histórico de certificados generados
- Integración biométrica simulada para autenticación

### Módulo de Riesgos con Google Maps
- Visualización de empresas y riesgos en mapa interactivo
- Marcadores colorizados por nivel de riesgo:
  - 🔴 Rojo: Riesgo alto
  - 🟠 Naranja: Riesgo medio
  - 🟢 Verde: Riesgo bajo
- Filtrado por tipo y severidad de riesgos
- Panel de información detallada de cada empresa

## 🔄 Flujo de Inspecciones para generar el Certificado

1. **Cliente**: Crear empresa
2. **Admin**: Registrar información del establecimiento
3. **Cliente**: Solicitar inspección
4. **Admin**: Asignar inspector
5. **Inspector**: Generar concepto
6. **Sistema**: Finalizar inspección y generar certificado
7. **Cliente/Inspector**: Descargar certificado

## 📋 Requisitos Técnicos

- PHP 8.0+
- MySQL 5.7+
- Composer
- Node.js 14+
- API Key de Google Maps
- Python (Para el simulador biometrico)

## 🛠️ Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/Barcodehub/riesgo_CBVVR.git
cd riesgo_CBVVR
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar base de datos
- Crear base de datos MySQL con el nombre especificado en el archivo `.env`
- Configurar las credenciales de base de datos en `.env`

### 4. Ejecutar migraciones
```bash
php artisan migrate
```

### 5. Configurar almacenamiento
```bash
php artisan storage:link
```

### 6. Compilar assets
```bash
npm run dev
```

### 7. Iniciar servidor de desarrollo
```bash
php artisan serve
```

### O Correr desde el Docker (ejemplo maquina virtual Fedora):

- copia el archivo .env
```bash
cp .env.example .env
```

- Asegurate de incluir la ip de tu maquina virtual en el .env `APP_URL=http://localhost` segun corresponda
- Asegurate de incluir la ip de tu maquina virtual en el archivo `vite.config.js`:

```bash
 hmr: {
            host: 'localhost', #Cambiar a ip de mq virtual
        },
```

- Desactiva temporalmente sobre el proyecto:
```bash
sudo setenforce 0
sudo systemctl stop firewalld
```

- Corre los contenedores
```bash
docker-compose up --build -d 
```

- Accede al contenedor de la app
```bash
docker exec -it riesgo_cbvvr-app-1 bash
composer install
php artisan key:generate
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

- Corre las migraciones
```bash
docker-compose exec app php artisan migrate
```

## 🔧 Solución de Problemas

Si el servidor no inicia correctamente en local, ejecutar:
```bash
php artisan config:clear
php artisan config:cache
php artisan serve --env=local
```

## 🔐 Sistema Biométrico (DigitalPersona) -> app Java (no usar con docker)

### Configuración del hardware biométrico
El sistema integra autenticación por huella digital usando dispositivos DigitalPersona.

1. **Configuración del servidor biométrico**:
   - Configura el host y puerto en `.env`:
     ```
     BIO_HOST=127.0.0.1 
     BIO_PORT=8080
     ```

2. **Ejecutar servidor biométrico**:
   ```bash
   java-service/run.bat

3. Crear huella biométrica:

   - Los usuarios pueden registrar huellas desde la interfaz de cada uno.

4. Login biométrico:

   - En la pantalla de login, usar el botón de huella digital
   - El sistema validará contra las huellas registradas en la base de datos

5. Si se desea usar con Docker, emular una VM con windows 10 o 11, (armar el docker si se desea), ejecutar y comunicar la ip de la vm con la otra (en Host)


## 🗺️ Integración con Google Maps

Para utilizar las funcionalidades de geolocalización:

1. Obtener API Key de Google Maps
2. Configurar la clave en el archivo `.env`:
   ```
   MAPS_ACCESS_TOKEN=tu_api_key_aqui
   ```
3. Habilitar los siguientes servicios en Google Cloud Console:
   - Maps JavaScript API
   - Geocoding API
   - Places API

## 📊 Funcionalidades Implementadas

### ✅ Completadas
- **R31**: Generación de certificado de inspecciones por parte del inspector
- **R35**: Descarga de certificado de inspecciones por parte del cliente
- **R39**: Histórico de certificados
- Integración con hardware biométrico DigitalPersona
- Corrección de migraciones y migración a MySQL
- CRUD de riesgos con geolocalización
- Simulador biométrico funcional
- Integración con Google Maps

### 🔄 En Desarrollo
- Despliegue en ambiente de producción

### Cambios Futuros Planificados
- **Hardware Biométrico Real**: Implementar dispositivo físico de huella dactilar
  - Si el hardware usa un puerto distinto, configurarlo en el archivo `.env`
  - Reemplazar el botón actual por un diseño de huella personalizado
  - Integración directa con el dispositivo físico

## 🚀 Despliegue

### Ambiente de Desarrollo
```bash
npm run dev
php artisan serve
```

### Ambiente de Producción
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📝 Notas Importantes

- Crear la base de datos antes de ejecutar las migraciones
- Asegurarse de tener configurado correctamente el archivo `.env`
- El sistema biométrico funciona en modo simulación para pruebas
- Los certificados se generan automáticamente al finalizar las inspecciones


**Desarrollado con ❤️ usando Laravel**