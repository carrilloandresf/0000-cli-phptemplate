# 0000-cli-phptemplate

Este repositorio es un "hello world" extendido para demostrar una API PHP limpia con las
operaciones básicas de negocio y un micro frontend estático que permite probarlas sin utilizar herramientas externas.

## 🧱 Estructura del proyecto

- `api-backend/`: Código fuente PHP con arquitectura limpia, servicios de aplicación, dominio y capa de infraestructura.
  - `public/index.php`: Router muy simple que expone los endpoints REST y sirve el frontend estático.
  - `public/frontend.html`: Micro front que consume la API mediante `fetch`.
  - `scripts/`: utilidades para preparar la base de datos.
  - `src/`: Servicios, modelos y adaptadores de infraestructura.

## 🚀 Puesta en marcha rápida

```bash
cd api-backend
composer install

# Levantar MySQL
docker-compose up -d
sleep 10
php scripts/setup-database.php

# Servir API + Frontend
php -S localhost:8000 -t public
```

Abre <http://localhost:8000/> en tu navegador para acceder al panel que permite lanzar todas las
funcionalidades (cálculo, texto, CRUD, stored procedures y healthcheck) con un solo click.

## 📚 Documentación adicional

Consulta el [`README` de api-backend](api-backend/README.md) para obtener más detalles sobre endpoints,
configuración de base de datos y ejemplo de payloads.
