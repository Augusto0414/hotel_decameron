# 🏨 Hotel Decameron - Setup Rápido

## Requisitos Previos

- PHP 8.2+
- Composer
- SQLite o MySQL/PostgreSQL
- Node.js (para Vite - opcional)

## 1. Instalación

```bash
# Instalar dependencias PHP
composer install

# Instalar dependencias Node (opcional)
npm install

# Copiar archivo de configuración
cp .env.example .env

# Generar clave de la aplicación
php artisan key:generate
```

## 2. Configurar Base de Datos

**Opción A: SQLite (Recomendado para desarrollo)**

Edita `.env`:

```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/database.sqlite
```

O simplemente crea el archivo:

```bash
touch database/database.sqlite
```

**Opción B: MySQL/PostgreSQL**

Edita `.env` con tus credenciales:

```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=hotel_decameron
DB_USERNAME=root
DB_PASSWORD=
```

## 3. Ejecutar Migraciones y Seeders

```bash
# Ejecutar migraciones
php artisan migrate

# Cargar catálogos (tipos de habitación y acomodaciones)
php artisan db:seed --class=CatalogoSeeder

# OPCIONAL: Cargar datos de ejemplo
php artisan db:seed --class=HotelEjemploSeeder

# O todos a la vez
php artisan db:seed
```

## 4. Iniciar Servidor

```bash
php artisan serve
```

La API estará disponible en: `http://localhost:8000/api`

---

## 📋 Pruebas Rápidas

### 1. Ver Catálogos

```bash
curl http://localhost:8000/api/catalogos
```

### 2. Listar Hoteles (si cargó HotelEjemploSeeder)

```bash
curl http://localhost:8000/api/hoteles
```

### 3. Crear Nuevo Hotel

```bash
curl -X POST http://localhost:8000/api/hoteles \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "DECAMERON SANTA MARTA",
    "direccion": "AVENIDA PRINCIPAL 100",
    "ciudad": "SANTA MARTA",
    "nit": "98765432-1",
    "numero_habitaciones": 50
  }'
```

### 4. Asignar Habitaciones (requiere ID del hotel)

```bash
curl -X POST http://localhost:8000/api/hoteles/1/habitaciones \
  -H "Content-Type: application/json" \
  -d '{
    "tipo_habitacion_id": 1,
    "acomodacion_id": 1,
    "cantidad": 20
  }'
```

### 5. Ver Habitaciones de un Hotel

```bash
curl http://localhost:8000/api/hoteles/1/habitaciones
```

---

## 🔧 Problemas Comunes

### Error: "Class 'Acomodacion' not found"

```bash
# Asegúrate de que las migraciones se ejecutaron
php artisan migrate --fresh
php artisan db:seed
```

### Error: "SQLSTATE[HY000]: General error: 1 table hotel_habitacion already exists"

```bash
# Hacer rollback y remigrar
php artisan migrate:rollback
php artisan migrate
php artisan db:seed
```

### Error: "Port 8000 already in use"

```bash
# Usar otro puerto
php artisan serve --port=8001
```

---

## 📚 Documentación Completa

Ver `API_DOCUMENTATION.md` para:

- Todos los endpoints disponibles
- Ejemplos de request/response
- Validaciones y reglas de negocio
- Códigos de error

---

## 🎯 Estructura del Proyecto

```
.
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── HotelController.php
│   │       ├── HotelHabitacionController.php
│   │       └── CatalogoController.php
│   └── Models/
│       ├── Hotel.php
│       ├── HotelHabitacion.php
│       ├── TipoHabitacion.php
│       └── Acomodacion.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── CatalogoSeeder.php
│       ├── HotelEjemploSeeder.php
│       └── DatabaseSeeder.php
├── routes/
│   ├── api.php
│   └── web.php
├── API_DOCUMENTATION.md
└── README.SETUP.md (este archivo)
```

---

## ✨ Características Implementadas

✅ CRUD de Hoteles
✅ Asignación de Habitaciones
✅ Validación de Combinaciones Tipo-Acomodación
✅ Validación de Capacidad Máxima
✅ Prevención de Duplicados
✅ Catálogos Públicos
✅ Migraciones Sincronizadas
✅ Seeders para Datos Iniciales

---

## 🚀 Siguiente Paso (Opcional)

Si necesitas:

- **Autenticación**: Descomentar middleware en routes/api.php
- **Tests**: Crear archivos en tests/Feature/
- **Frontend**: Usar Vite con `npm run dev`
- **CI/CD**: Agregar GitHub Actions

¡Listo para empezar! 🎉
