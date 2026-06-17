# API de Gestión de Hoteles - Decameron

## Descripción

Sistema API REST para gestionar hoteles, tipos de habitaciones y acomodaciones con validaciones según los criterios especificados.

## Base URL

```
/api
```

## Endpoints

### 1. Catálogos (Sin autenticación)

#### Obtener catálogo completo

```
GET /api/catalogos
```

Respuesta:

```json
{
    "tipos_habitacion": [
        { "id": 1, "nombre": "ESTANDAR" },
        { "id": 2, "nombre": "JUNIOR" },
        { "id": 3, "nombre": "SUITE" }
    ],
    "acomodaciones": [
        {
            "id": 1,
            "nombre": "SENCILLA",
            "descripcion": "Habitación con cama sencilla"
        },
        {
            "id": 2,
            "nombre": "DOBLE",
            "descripcion": "Habitación con cama doble"
        },
        {
            "id": 3,
            "nombre": "TRIPLE",
            "descripcion": "Habitación con tres camas"
        },
        {
            "id": 4,
            "nombre": "CUADRUPLE",
            "descripcion": "Habitación con cuatro camas"
        }
    ]
}
```

#### Obtener tipos de habitación

```
GET /api/catalogos/tipos-habitacion
```

#### Obtener acomodaciones

```
GET /api/catalogos/acomodaciones
```

---

### 2. Hoteles

#### Listar todos los hoteles

```
GET /api/hoteles
```

Respuesta:

```json
[
    {
        "id": 1,
        "nombre": "DECAMERON CARTAGENA",
        "direccion": "CALLE 23 58-25",
        "ciudad": "CARTAGENA",
        "nit": "12345678-9",
        "numero_habitaciones": 42,
        "habitaciones": [
            {
                "id": 1,
                "cantidad": 25,
                "tipoHabitacion": { "id": 1, "nombre": "ESTANDAR" },
                "acomodacion": { "id": 1, "nombre": "SENCILLA" }
            },
            {
                "id": 2,
                "cantidad": 12,
                "tipoHabitacion": { "id": 2, "nombre": "JUNIOR" },
                "acomodacion": { "id": 3, "nombre": "TRIPLE" }
            },
            {
                "id": 3,
                "cantidad": 5,
                "tipoHabitacion": { "id": 1, "nombre": "ESTANDAR" },
                "acomodacion": { "id": 2, "nombre": "DOBLE" }
            }
        ]
    }
]
```

#### Crear un nuevo hotel

```
POST /api/hoteles
Content-Type: application/json

{
  "nombre": "DECAMERON CARTAGENA",
  "direccion": "CALLE 23 58-25",
  "ciudad": "CARTAGENA",
  "nit": "12345678-9",
  "numero_habitaciones": 42
}
```

Respuesta (201):

```json
{
    "message": "Hotel creado exitosamente",
    "hotel": {
        "id": 1,
        "nombre": "DECAMERON CARTAGENA",
        "direccion": "CALLE 23 58-25",
        "ciudad": "CARTAGENA",
        "nit": "12345678-9",
        "numero_habitaciones": 42,
        "created_at": "2026-06-17T...",
        "updated_at": "2026-06-17T..."
    }
}
```

#### Obtener hotel específico

```
GET /api/hoteles/{id}
```

#### Actualizar hotel

```
PUT /api/hoteles/{id}
Content-Type: application/json

{
  "nombre": "DECAMERON CARTAGENA ACTUALIZADO",
  "numero_habitaciones": 50
}
```

#### Eliminar hotel

```
DELETE /api/hoteles/{id}
```

---

### 3. Habitaciones del Hotel

#### Listar habitaciones de un hotel

```
GET /api/hoteles/{hotelId}/habitaciones
```

Respuesta:

```json
{
    "hotel": {
        "id": 1,
        "nombre": "DECAMERON CARTAGENA",
        "numero_habitaciones": 42
    },
    "habitaciones": [
        {
            "id": 1,
            "hotel_id": 1,
            "tipo_habitacion_id": 1,
            "acomodacion_id": 1,
            "cantidad": 25,
            "tipoHabitacion": { "id": 1, "nombre": "ESTANDAR" },
            "acomodacion": { "id": 1, "nombre": "SENCILLA" }
        }
    ],
    "total_configuradas": 42,
    "capacidad_disponible": 0
}
```

#### Asignar habitaciones a un hotel

```
POST /api/hoteles/{hotelId}/habitaciones
Content-Type: application/json

{
  "tipo_habitacion_id": 1,
  "acomodacion_id": 1,
  "cantidad": 25
}
```

Respuesta (201):

```json
{
    "message": "Habitaciones asignadas exitosamente",
    "hotel_habitacion": {
        "id": 1,
        "hotel_id": 1,
        "tipo_habitacion_id": 1,
        "acomodacion_id": 1,
        "cantidad": 25,
        "tipoHabitacion": { "id": 1, "nombre": "ESTANDAR" },
        "acomodacion": { "id": 1, "nombre": "SENCILLA" }
    },
    "total_configuradas": 25,
    "capacidad_disponible": 17
}
```

#### Actualizar cantidad de habitaciones

```
PUT /api/hoteles/{hotelId}/habitaciones/{habitacionId}
Content-Type: application/json

{
  "cantidad": 30
}
```

#### Eliminar asignación de habitaciones

```
DELETE /api/hoteles/{hotelId}/habitaciones/{habitacionId}
```

---

## Validaciones

### Tipos de Habitación vs Acomodaciones Válidas

| Tipo Habitación | Acomodaciones Válidas   |
| --------------- | ----------------------- |
| ESTANDAR        | SENCILLA, DOBLE         |
| JUNIOR          | TRIPLE, CUADRUPLE       |
| SUITE           | SENCILLA, DOBLE, TRIPLE |

### Criterios de Validación

1. **Nombre de hotel único**: No pueden existir dos hoteles con el mismo nombre
2. **Combinaciones únicas**: No pueden repetirse combinaciones de tipo de habitación + acomodación en el mismo hotel
3. **Capacidad máxima**: La suma de cantidad de habitaciones no puede superar el `numero_habitaciones` configurado para el hotel
4. **Tipo-Acomodación válida**: Solo se permiten las combinaciones definidas en la tabla anterior

---

## Códigos de Error

- `200`: OK - Solicitud exitosa
- `201`: Created - Recurso creado exitosamente
- `400`: Bad Request - Solicitud inválida
- `404`: Not Found - Recurso no encontrado
- `422`: Unprocessable Entity - Error de validación

---

## Instalación y Configuración

### 1. Configurar Base de Datos

```bash
# Crear archivo .env si no existe
cp .env.example .env

# Generar clave de la aplicación
php artisan key:generate
```

### 2. Ejecutar Migraciones

```bash
php artisan migrate
```

### 3. Cargar Catálogos Iniciales

```bash
php artisan db:seed --class=CatalogoSeeder
```

O para ejecutar todos los seeders:

```bash
php artisan db:seed
```

### 4. Iniciar servidor de desarrollo

```bash
php artisan serve
```

---

## Ejemplos de Uso

### Crear un hotel con habitaciones

1. **Crear el hotel**

```bash
curl -X POST http://localhost:8000/api/hoteles \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "DECAMERON CARTAGENA",
    "direccion": "CALLE 23 58-25",
    "ciudad": "CARTAGENA",
    "nit": "12345678-9",
    "numero_habitaciones": 42
  }'
```

2. **Asignar habitaciones Estándar Sencilla (25 habitaciones)**

```bash
curl -X POST http://localhost:8000/api/hoteles/1/habitaciones \
  -H "Content-Type: application/json" \
  -d '{
    "tipo_habitacion_id": 1,
    "acomodacion_id": 1,
    "cantidad": 25
  }'
```

3. **Asignar habitaciones Junior Triple (12 habitaciones)**

```bash
curl -X POST http://localhost:8000/api/hoteles/1/habitaciones \
  -H "Content-Type: application/json" \
  -d '{
    "tipo_habitacion_id": 2,
    "acomodacion_id": 3,
    "cantidad": 12
  }'
```

4. **Asignar habitaciones Estándar Doble (5 habitaciones)**

```bash
curl -X POST http://localhost:8000/api/hoteles/1/habitaciones \
  -H "Content-Type: application/json" \
  -d '{
    "tipo_habitacion_id": 1,
    "acomodacion_id": 2,
    "cantidad": 5
  }'
```

5. **Obtener el hotel con todas sus habitaciones**

```bash
curl http://localhost:8000/api/hoteles/1
```

---

## Testing

Puedes usar herramientas como Postman, Insomnia o cURL para probar los endpoints.
