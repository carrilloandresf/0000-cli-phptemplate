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
docker compose up -d
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

## 🧱 Usar este repositorio como template

### Opción 1: Desde la interfaz de GitHub

1. Haz clic en el botón **Use this template** del repositorio original.
2. Asigna un nombre al nuevo repositorio y, si es necesario, ajústalo a público o privado.
3. Confirma con **Create repository from template**.
4. Clona tu nuevo repositorio donde vayas a trabajar:

   ```bash
   git clone https://github.com/<tu-usuario>/<tu-nuevo-repo>.git
   cd <tu-nuevo-repo>
   ```

### Opción 2: Clonando y limpiando manualmente

1. Clona este repositorio template sin historial previo:

   ```bash
   git clone --depth=1 https://github.com/<org>/0000-cli-phptemplate.git mi-nuevo-servicio
   cd mi-nuevo-servicio
   ```

2. Elimina el remoto original y crea uno nuevo apuntando a tu repositorio vacío:

   ```bash
   rm -rf .git
   git init
   git remote add origin https://github.com/<tu-usuario>/<tu-nuevo-repo>.git
   git add .
   git commit -m "chore: bootstrap desde template"
   git push -u origin main
   ```

> 💡 Recuerda actualizar el nombre del proyecto en `composer.json`, `README.md` u otros archivos según sea necesario.

## 🛠️ Crear un nuevo servicio paso a paso

1. **Define el contrato del dominio**
   - Crea un nuevo modelo en `src/Domain/Models`. Usa los modelos existentes (`User.php`, `CalculationResult.php`) como guía.
   - Mantén métodos como `toArray()` para facilitar la serialización en las respuestas HTTP.

2. **Implementa la lógica de aplicación**
   - Añade una clase de servicio en `src/Application/Services`.
   - Inyecta dependencias necesarias (por ejemplo, `Database::connection()`) en el constructor o dentro de los métodos.
   - Valida entradas y lanza `InvalidArgumentException` cuando detectes datos inválidos; el `index.php` ya captura esta excepción y responde con HTTP 400.

3. **Registra el endpoint HTTP**
   - Edita `public/index.php` y agrega un nuevo `case` en el `switch` del router.
   - Instancia tu servicio, procesa la petición y devuelve la respuesta con `Response::json($payload, $statusCode)`.
   - Si la ruta requiere parámetros (`/api/mi-recurso/{id}`), usa `preg_match` como en los ejemplos de usuarios.

4. **Conecta con la base de datos (opcional)**
   - Si tu servicio persiste datos, usa `App\Infrastructure\Database\Database::query()` o crea métodos auxiliares para consultas preparadas.
   - Añade scripts SQL o procedimientos almacenados en `scripts/` si necesitas automatizar su creación (reutiliza `scripts/setup-database.php`).

5. **Prueba el servicio**
   - Reinicia el servidor embebido si es necesario y usa herramientas como `curl`, `HTTPie` o Postman para verificar las respuestas.
   - Añade ejemplos de requests al README o documentación específica para mantener la trazabilidad.

> ✅ Siguiendo estos pasos, cualquier nuevo servicio quedará alineado con la arquitectura hexagonal ligera del proyecto.

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
