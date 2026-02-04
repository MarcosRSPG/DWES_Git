# Guía rápida: adaptar la API de Mascotas a Coches

> Objetivo: que la API responda con **coches** en lugar de **mascotas**, manteniendo la misma arquitectura.

## 1) Base de datos

### 1.1. Tabla `coches`

Crea la tabla con campos típicos de coche. Ejemplo:

- `id` (CHAR(36), PK) **si es GeoID/UUID**
- `marca` (VARCHAR)
- `modelo` (VARCHAR)
- `matricula` (VARCHAR)
- `anio` (INT)
- `foto_url` (VARCHAR)
- `id_persona` (INT) _(opcional)_

> Nota: si usas GeoID/UUID, el `id` es **string** en PHP.

### 1.2. Datos de prueba

Inserta varios coches para probar los endpoints.

---

## 2) Estructura típica de una API MVC (referencia)

En la mayoría de ejercicios, la API tiene:

- `app/` → controladores, modelos, middlewares
- `public/index.php` → front controller
- `app/config/` → config, conexión BD

---

## 3) Modelo

### 3.1. Crear `CocheModelo`

Duplica el modelo de mascotas y adapta:

- Nombre de clase: `MascotaModelo` → `CocheModelo`
- Tabla: `mascotas` → `coches`
- Campos: `nombre`, `tipo`, `fecha_nacimiento` → `marca`, `modelo`, `matricula`, `anio`

Métodos habituales:

- `obtenerTodos()`
- `obtenerPorId($id)`
- `crear($datos)`
- `actualizar($id, $datos)`
- `eliminar($id)`

---

## 4) Controlador

### 4.1. Crear `CocheController`

Duplica el controlador de mascotas y adapta:

- Clase: `MascotaController` → `CocheController`
- Modelo: `new CocheModelo()`
- Rutas: `/mascotas` → `/coches`

Endpoints típicos:

- `GET /coches` → lista
- `GET /coches/{id}` → detalle
- `POST /coches` → crear
- `PUT /coches/{id}` → actualizar
- `DELETE /coches/{id}` → eliminar

---

## 5) Rutas

### 5.1. Actualiza el router

Registra las rutas nuevas en tu archivo de rutas. Ejemplos:

- `GET /coches` → `CocheController@index`
- `GET /coches/{id}` → `CocheController@show`
- `POST /coches` → `CocheController@create`
- `PUT /coches/{id}` → `CocheController@update`
- `DELETE /coches/{id}` → `CocheController@delete`

---

## 6) Validación y JSON

### 6.1. Valida campos nuevos

Sustituye reglas de mascotas por coches:

- `marca` (obligatorio)
- `modelo` (obligatorio)
- `matricula` (única si aplica)
- `anio` (numérico)

### 6.2. Respuesta JSON

Asegúrate de devolver JSON con los nuevos campos.

---

## 7) Pruebas rápidas

- `GET /coches` debe devolver array de coches
- `POST /coches` crea un coche
- `GET /coches/{id}` devuelve detalle
- `PUT /coches/{id}` actualiza
- `DELETE /coches/{id}` elimina

---

## 8) Checklist final

- [ ] Tabla `coches` creada
- [ ] Modelo `CocheModelo` adaptado
- [ ] Controlador `CocheController` adaptado
- [ ] Rutas nuevas registradas
- [ ] Validación y respuestas JSON actualizadas
- [ ] CRUD probado OK

---

**Resultado:** misma API, nuevo recurso `coches`.
