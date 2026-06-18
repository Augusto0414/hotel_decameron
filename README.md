
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
   Luego de configurar correctamente la conexión en el archivo `.env`, ejecute las migraciones para preparar la base de datos:

   ```bash
   php artisan migrate
   ```

3. **Ejecutar la aplicación:**
   Una vez finalizadas las migraciones, inicie el servidor para que la aplicación quede completamente funcional:

   ```bash
   php artisan serve
   ```

