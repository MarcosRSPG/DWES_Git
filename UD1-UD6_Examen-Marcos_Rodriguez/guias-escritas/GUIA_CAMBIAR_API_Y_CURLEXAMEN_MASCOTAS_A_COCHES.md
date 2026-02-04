# Guía rápida: adaptar **API** y **curlexamen** de Mascotas a Coches

> Objetivo: cambiar el recurso completo de **mascotas** a **coches** en la API y en el cliente `curlexamen`.

---

## 1) API (servidor)

### 1.1. Base de datos

Crea tabla `coches` con los nuevos campos:

- `id` (CHAR(36), PK) **si es GeoID/UUID**
- `marca` (VARCHAR)
- `modelo` (VARCHAR)
- `matricula` (VARCHAR)
- `anio` (INT)
- `foto_url` (VARCHAR)
- `id_persona` (INT, opcional)

> Nota: GeoID/UUID → `id` es **string** en PHP.

### 1.2. Modelo

Duplica `MascotaModelo` y adapta:

- Clase → `CocheModelo`
- Tabla → `coches`
- Campos → `marca`, `modelo`, `matricula`, `anio`, `foto_url`, `id_persona`

### 1.3. Controlador

Duplica `MascotaController` y adapta:

- Clase → `CocheController`
- Endpoints → `/coches`

### 1.4. Rutas

Registra rutas de coches:

- `GET /coches`
- `GET /coches/{id}`
- `POST /coches`
- `PUT /coches/{id}`
- `DELETE /coches/{id}`

### 1.5. Validación/JSON

Sustituye reglas por campos de coche y responde JSON con `marca`, `modelo`, `matricula`, `anio`, etc.

---

## 2) Cliente **curlexamen**

> **Importante**: El enunciado dice _mvcexamen_, pero el proyecto correcto es **curlexamen**.

### 2.1. Configuración base

Revisa la URL base del API si es distinta.

### 2.2. Funciones de llamadas cURL

Busca las funciones que consumen la API de mascotas y duplica/renombra:

- `getMascotas()` → `getCoches()`
- `getMascota($id)` → `getCoche($id)`
- `crearMascota()` → `crearCoche()`
- `actualizarMascota()` → `actualizarCoche()`
- `eliminarMascota()` → `eliminarCoche()`

Y cambia rutas:

- `/mascotas` → `/coches`

### 2.3. Vistas/plantillas

Si hay vistas o HTML en curlexamen:

- Cambia títulos y labels de mascotas a coches
- Sustituye campos: `nombre/tipo/fecha_nacimiento` → `marca/modelo/matricula/anio`
- Ajusta la tabla de listados

### 2.4. Formularios

Actualiza los inputs para que envíen:

- `marca`, `modelo`, `matricula`, `anio`, `foto_url`, `id_persona`

---

## 3) Checklist final

- [ ] API: tabla `coches` creada
- [ ] API: `CocheModelo` y `CocheController`
- [ ] API: rutas `/coches` registradas
- [ ] curlexamen: endpoints apuntan a `/coches`
- [ ] curlexamen: formularios y tablas adaptadas
- [ ] CRUD probado OK

---

**Resultado:** API + curlexamen funcionando con **coches** en lugar de **mascotas**.
