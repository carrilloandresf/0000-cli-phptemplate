# API Demo - Prueba Técnica PHP Senior

Una API minimalista con arquitectura limpia, CRUD completo y stored procedures.

## 📁 Estructura del proyecto

```
.
├── composer.json
├── docker-compose.yml
├── public/
├── scripts/
├── src/
└── vendor/ (generado por Composer)
```

Todas las carpetas necesarias para ejecutar la API están ahora en la raíz del repositorio. No es necesario navegar a un subdirectorio separado.

## 🚀 Inicio Rápido

### 1. Levantar MySQL

```bash
docker-compose up -d
```

### 2. Configurar Base de Datos

```bash
sleep 10
php scripts/setup-database.php
```

### 3. Iniciar Servidor

```bash
composer dump-autoload
php -S localhost:8000 -t public
```

## 📊 Endpoints Disponibles

### Cálculos

`GET /api/calculate?expression=5+3`

### Texto

`POST /api/process-text`
```json
{"text": "hello world"}
```

### CRUD Users

- `GET /api/users` - Listar
- `POST /api/users` - Crear
- `GET /api/users/{id}` - Obtener
- `PUT /api/users/{id}` - Actualizar
- `DELETE /api/users/{id}` - Eliminar

### Stats (Stored Procedures)

- `GET /api/stats/user-count` - SP sin parámetros
- `GET /api/stats/user-stats/{id}` - SP con parámetros

### Health Check

`GET /api/health`
