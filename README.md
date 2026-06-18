
## Instrucciones de Ejecución

Siga estos pasos para configurar y ejecutar la aplicación:

1. **Configurar las variables de entorno:**
   Antes de ejecutar la aplicación, debe quitar la extensión `.template` del archivo `.env.template` (es decir, renombrarlo a `.env`).
   Luego, abra el archivo `.env` y configure las variables correspondientes a la conexión de la base de datos:

   ```env
   DB_CONNECTION=
   DB_DATABASE=
   DB_HOST=
   DB_PASSWORD=
   DB_PORT=
   DB_SSLMODE=require
   DB_USERNAME=
   ```

2. **Ejecutar las migraciones:**
   Luego de configurar correctamente la conexión en el archivo `.env`, ejecute el siguiente comando. Esto **creará todas las tablas necesarias** en la base de datos:

   ```bash
   php artisan migrate
   ```

3. **Ejecutar la aplicación:**
   Una vez finalizadas las migraciones, inicie el servidor para que la aplicación quede completamente funcional:

   ```bash
   php artisan serve
   ```

## Endpoints de la API

A continuación se detallan las rutas disponibles en la API (todas tienen el prefijo `/api`):

### Tipos de Habitación
* `GET /api/tipos-habitacion`: Obtiene la lista de tipos de habitación.
* `POST /api/tipos-habitacion`: Crea un nuevo tipo de habitación.
* `GET /api/tipos-habitacion/{id}`: Obtiene los detalles de un tipo de habitación.
* `PUT /api/tipos-habitacion/{id}`: Actualiza un tipo de habitación.
* `DELETE /api/tipos-habitacion/{id}`: Elimina un tipo de habitación.

### Acomodaciones
* `GET /api/acomodaciones`: Obtiene la lista de acomodaciones.
* `POST /api/acomodaciones`: Crea una nueva acomodación.
* `GET /api/acomodaciones/{id}`: Obtiene los detalles de una acomodación.
* `PUT /api/acomodaciones/{id}`: Actualiza una acomodación.
* `DELETE /api/acomodaciones/{id}`: Elimina una acomodación.

### Hoteles
* `GET /api/hoteles`: Obtiene la lista de hoteles.
* `POST /api/hoteles`: Crea un nuevo hotel.
* `GET /api/hoteles/{id}`: Obtiene los detalles de un hotel.
* `PUT /api/hoteles/{id}`: Actualiza un hotel.
* `DELETE /api/hoteles/{id}`: Elimina un hotel.

### Habitaciones del Hotel
* `GET /api/hoteles/{hotelId}/habitaciones`: Obtiene las habitaciones asignadas a un hotel.
* `POST /api/hoteles/{hotelId}/habitaciones`: Asigna/Crea una nueva habitación para un hotel.
* `PUT /api/hoteles/{hotelId}/habitaciones/{habitacionId}`: Actualiza los detalles de una habitación en un hotel.
* `DELETE /api/hoteles/{hotelId}/habitaciones/{habitacionId}`: Elimina una habitación de un hotel.
