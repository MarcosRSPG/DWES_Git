# Guía rápida: adaptar un MVC de Mascotas a Coches

> Objetivo: que la app muestre **coches** en lugar de **mascotas** manteniendo la misma estructura MVC y estilos.

## 1) Cambios en la Base de Datos

### 1.1. Nueva tabla `coches`

Crea una tabla equivalente a `mascotas`, pero con campos de coches. Ejemplo:

- `id` (CHAR(36), PK) **si es GeoID/UUID**
- `marca` (VARCHAR)
- `modelo` (VARCHAR)
- `matricula` (VARCHAR)
- `anio` (INT)
- `foto_url` (VARCHAR)
- `id_persona` (INT) _(si quieres mantener relación con personas)_

> Nota: si usas GeoID/UUID, trata el `id` como **string** en PHP (modelo/controlador) y no como entero.

### 1.2. Datos de prueba

Inserta algunos coches para ver el listado.

---

## 2) Modelo (app/modelos)

### 2.1. Crear `CocheModelo.php`

Copia `MascotaModelo.php` y reemplaza:

- Nombre de clase a `CocheModelo`
- Tabla `mascotas` → `coches`
- Campos: `nombre`, `tipo`, `fecha_nacimiento` → `marca`, `modelo`, `matricula`, `anio`

#### Métodos típicos

- `obtenerTodos()`
- `obtenerPorId($id)`
- `crear($datos)`
- `actualizar($id, $datos)`
- `eliminar($id)`

---

## 3) Controlador (app/controladores)

### 3.1. Crear `Coche.php`

Copia `Mascota.php` y reemplaza:

- Nombre de clase a `Coche`
- Instancia de modelo: `new CocheModelo()`
- Métodos: `mascotas()` → `coches()`
- Rutas y vistas: `mascotas`, `registro`, `editar`, `detalles` → versiones para coches

#### Ejemplo de rutas

- `/Coche/coches`
- `/Coche/crear`
- `/Coche/editar/{id}`
- `/Coche/ver/{id}`
- `/Coche/eliminar/{id}`

---

## 4) Vistas (app/vistas/paginas)

### 4.1. Duplicar vistas de mascotas

Copia las vistas de mascotas y renómbralas:

- `mascotas.php` → `coches.php`
- `registro.php` → `registro-coche.php` _(o `crear.php`)_
- `editar.php` → `editar-coche.php`
- `detalles.php` → `detalles-coche.php`

### 4.2. Cambiar campos en formularios

Reemplaza los inputs de mascota por coches:

- `nombre` → `marca`
- `tipo` → `modelo`
- `fecha_nacimiento` → `anio` (tipo number)
- `foto_url` se mantiene
- `id_persona` se mantiene si aplica

### 4.3. Cambiar columnas del listado

En el `<table>` cambia cabeceras y valores:

- Nombre → Marca
- Tipo → Modelo
- Fecha nacimiento → Año

---

## 5) Navegación

### 5.1. Menú principal

En la vista de inicio cambia el enlace:

- `Mascotas` → `Coches`
- URL: `/Mascota/mascotas` → `/Coche/coches`

---

## 6) Rutas / Core

No hace falta tocar el router si ya funciona con `/Controlador/metodo/parametros`.
Solo asegúrate de que el controlador `Coche` exista.

---

## 7) Estilos (CSS)

No es necesario cambiar nada. El CSS es genérico y funcionará igual.

---

## 8) Comprobación final

Checklist:

- [ ] Tabla `coches` creada y con datos
- [ ] `CocheModelo.php` funciona con tabla `coches`
- [ ] `Coche.php` tiene métodos CRUD
- [ ] Vistas nuevas para coches con campos actualizados
- [ ] Menú apunta a coches
- [ ] Prueba CRUD completo

---

## Extra: si quieres mantener ambos (Mascotas y Coches)

Puedes dejar ambos controladores y modelos, y añadir dos menús separados.

---

**Resultado:** tendrás exactamente la misma app, pero mostrando coches en lugar de mascotas, reutilizando estructura, sesión, estilos y layout.
