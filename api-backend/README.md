# API Demo - Prueba Técnica PHP Senior

API PHP estilo "clean architecture" que implementa todas las funcionalidades básicas de la prueba: operaciones matemáticas,
procesamiento de texto, CRUD de usuarios y estadísticas basadas en stored procedures. Incluye un micro frontend estático que
sirve como tablero de pruebas.

## 🧰 Stack

- PHP 8 con autoloading vía Composer.
- MySQL orquestado con Docker Compose.
- Arquitectura dividida en `Application`, `Domain` e `Infrastructure`.
- Frontend estático (`public/frontend.html`) que consume la API usando `fetch`.

## 🚀 Cómo ejecutar el proyecto

```bash
# Instalar dependencias PHP
composer install

# Levantar MySQL en segundo plano
docker-compose up -d

# Esperar a que MySQL inicie y crear el esquema + datos de ejemplo
sleep 10
php scripts/setup-database.php

# Lanzar el servidor embebido de PHP
php -S localhost:8000 -t public
```

Ve a <http://localhost:8000/> para usar el panel y disparar cada endpoint sin Postman.

## 🔌 Endpoints disponibles

| Tipo | Endpoint | Descripción |
|------|----------|-------------|
| `GET` | `/api/calculate?expression=5+3` | Evalúa expresiones matemáticas básicas. |
| `POST` | `/api/process-text` | Procesa texto (longitud, mayúsculas, minúsculas). |
| `GET` | `/api/users` | Lista usuarios con conteo total. |
| `POST` | `/api/users` | Crea un usuario nuevo. |
| `GET` | `/api/users/{id}` | Obtiene los datos de un usuario. |
| `PUT` | `/api/users/{id}` | Actualiza nombre y correo. |
| `DELETE` | `/api/users/{id}` | Elimina un usuario. |
| `GET` | `/api/stats/user-count` | Ejecuta stored procedure sin parámetros. |
| `GET` | `/api/stats/user-stats/{id}` | Stored procedure con parámetro. |
| `GET` | `/api/health` | Health check simple. |

## 🖥️ Probar desde el frontend

`public/frontend.html` expone formularios y botones para cada flujo. Sólo necesitas tener el servidor corriendo para
ver las respuestas JSON en la sección "Última respuesta".

## 🧪 Tests manuales vía cURL

```bash
# Calculadora
curl 'http://localhost:8000/api/calculate?expression=5+3'

# Crear usuario
curl -X POST 'http://localhost:8000/api/users' \
  -H 'Content-Type: application/json' \
  -d '{"name": "Ada", "email": "ada@example.com"}'

# Obtener métricas
curl 'http://localhost:8000/api/stats/user-count'
```

Con esto tendrás un verdadero "hello world" de arquitectura limpia con todas las piezas básicas conectadas.
