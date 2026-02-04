# 🏥 Sistema MVC Veterinaria - Apuntes para Examen

Sistema completo de gestión veterinaria desarrollado con arquitectura MVC (Modelo-Vista-Controlador) en PHP.

## 📋 Características

- ✅ **Autenticación de veterinarios** con contraseñas hasheadas
- ✅ **CRUD completo de mascotas**
- ✅ **CRUD completo de personas (dueños)**
- ✅ **Relaciones entre mascotas y dueños**
- ✅ **Interfaz responsive y moderna**
- ✅ **Arquitectura MVC profesional**
- ✅ **Sin autenticación de base de datos** (solo login de aplicación)

## 🚀 Instalación

### 1. Requisitos Previos

- PHP 8.0 o superior
- MySQL/MariaDB
- Apache con mod_rewrite habilitado
- Composer

### 2. Configuración de Base de Datos

Ejecutar el script SQL que está en `/api-server/bd/bd.sql`:

```sql
-- La base de datos ya debe estar creada con las tablas:
-- - veterinarios
-- - mascotas
-- - personas
```

### 3. Configuración de la Aplicación

Editar `app/config/config.ini`:

```ini
[database]
host = "localhost"
port = 8000
user = "root"
pass = "rpwd"
dbname = "examen"
charset = "utf8mb4"
```

### 4. Instalar Dependencias

```bash
cd mvcapuntes
composer install
```

## 📁 Estructura del Proyecto

```
mvcapuntes/
├── app/
│   ├── config/
│   │   ├── config.php       # Configuración principal
│   │   └── config.ini       # Configuración de BD
│   ├── controladores/
│   │   ├── Paginas.php      # Login/Logout
│   │   ├── Mascota.php      # CRUD mascotas
│   │   └── Persona.php      # CRUD personas
│   ├── modelos/
│   │   ├── VeterinarioModelo.php
│   │   ├── MascotaModelo.php
│   │   └── PersonaModelo.php
│   ├── librerias/
│   │   ├── Core.php         # Enrutador
│   │   ├── Controlador.php  # Clase base
│   │   └── Db.php           # Conexión BD
│   ├── vistas/
│   │   ├── inc/
│   │   │   ├── header.php
│   │   │   └── footer.php
│   │   └── paginas/
│   │       ├── login.php
│   │       ├── mascotas.php
│   │       ├── mascota_form.php
│   │       ├── mascota_detalle.php
│   │       ├── personas.php
│   │       ├── persona_form.php
│   │       └── persona_mascotas.php
│   └── iniciador.php
├── public/
│   ├── css/
│   │   └── estilos.css
│   ├── index.php            # Punto de entrada
│   └── .htaccess
├── vendor/
├── .htaccess
└── composer.json
```

## 🔐 Credenciales de Acceso

**Veterinario:**

- Email: `garcia@vet.com`
- Contraseña: `1234`

**Veterinario 2:**

- Email: `ruiz@vet.com`
- Contraseña: `5678` (hasheada)

## 🎯 Rutas Principales

### Autenticación

- `GET /` → Redirige a login
- `GET /Paginas/login` → Formulario de login
- `POST /Paginas/login` → Procesar login
- `GET /Paginas/logout` → Cerrar sesión

### Mascotas

- `GET /Mascota/mascotas` → Listar todas las mascotas
- `GET /Mascota/crear` → Formulario crear mascota
- `POST /Mascota/crear` → Guardar nueva mascota
- `GET /Mascota/editar/{id}` → Formulario editar
- `POST /Mascota/editar/{id}` → Actualizar mascota
- `GET /Mascota/ver/{id}` → Ver detalle
- `GET /Mascota/eliminar/{id}` → Eliminar mascota

### Personas (Dueños)

- `GET /Persona/personas` → Listar todas las personas
- `GET /Persona/crear` → Formulario crear persona
- `POST /Persona/crear` → Guardar nueva persona
- `GET /Persona/editar/{id}` → Formulario editar
- `POST /Persona/editar/{id}` → Actualizar persona
- `GET /Persona/mascotas/{id}` → Ver mascotas de una persona
- `GET /Persona/eliminar/{id}` → Eliminar persona

## 🏗️ Arquitectura MVC

### Modelo (app/modelos/)

- Gestiona la lógica de negocio y acceso a datos
- Interactúa directamente con la base de datos
- Retorna datos procesados a los controladores

### Vista (app/vistas/)

- Presenta la información al usuario
- HTML con PHP embebido
- Recibe datos desde controladores

### Controlador (app/controladores/)

- Gestiona las peticiones del usuario
- Coordina modelo y vista
- Maneja la lógica de la aplicación

### Librerías (app/librerias/)

- **Core**: Enrutador principal (analiza URL y carga controlador)
- **Controlador**: Clase base para todos los controladores
- **Db**: Clase de conexión y operaciones de base de datos

## 💾 Base de Datos

### Tabla: veterinarios

```sql
- id (VARCHAR)
- nombre (VARCHAR)
- email (VARCHAR) UNIQUE
- clave (VARCHAR) # Contraseña hasheada
```

### Tabla: mascotas

```sql
- id (VARCHAR)
- nombre (VARCHAR)
- tipo (VARCHAR)
- fecha_nacimiento (DATE)
- foto_url (VARCHAR)
- id_persona (VARCHAR) FK → personas.id
```

### Tabla: personas

```sql
- id (VARCHAR)
- nombre (VARCHAR)
- apellidos (VARCHAR)
- telefono (VARCHAR)
- email (VARCHAR)
```

## 🔒 Seguridad

- ✅ **Contraseñas hasheadas** con `password_hash()` y `password_verify()`
- ✅ **Sesiones PHP** para mantener estado de login
- ✅ **Validación de acceso** en cada página protegida con `requireLogin()`
- ✅ **Prepared statements** para prevenir SQL Injection
- ✅ **htmlspecialchars()** en todas las salidas para prevenir XSS

## 📝 Notas de Estudio

### Flujo de una petición MVC:

1. **Usuario** accede a URL: `/Mascota/mascotas`
2. **Core.php** analiza la URL:
   - Controlador: `Mascota`
   - Método: `mascotas`
3. **Core** instancia el controlador y ejecuta el método
4. **Controlador** (`Mascota.php`):
   - Verifica login con `requireLogin()`
   - Llama al modelo: `$this->modelo('MascotaModelo')`
   - Obtiene datos: `$mascotas = $modelo->obtenerTodas()`
   - Carga la vista: `$this->vista('paginas/mascotas', $datos)`
5. **Vista** (`mascotas.php`):
   - Incluye header
   - Muestra datos en HTML
   - Incluye footer

### Crear un nuevo CRUD:

1. **Crear Modelo** en `app/modelos/NuevoModelo.php`
2. **Crear Controlador** en `app/controladores/Nuevo.php`
3. **Crear Vistas** en `app/vistas/paginas/nuevo_*.php`
4. **Actualizar navegación** en `app/vistas/inc/header.php`

### Conexión a BD:

```php
$db = new Db();
$db->query('SELECT * FROM tabla WHERE id = :id');
$db->bind(':id', $valor);
$resultado = $db->registro(); // Un registro
$resultados = $db->registros(); // Múltiples registros
```

## 🎨 CSS y Diseño

- Diseño **responsive** (móvil y escritorio)
- Colores principales:
  - Navbar: `#2c3e50`
  - Primario: `#3498db`
  - Éxito: `#27ae60`
  - Peligro: `#e74c3c`
  - Advertencia: `#f39c12`

## 🐛 Solución de Problemas

### Error 500 - Internal Server Error

- Verificar que mod_rewrite esté habilitado
- Comprobar permisos de archivos
- Revisar logs de Apache

### No se cargan los estilos CSS

- Verificar que la ruta en `config.php` sea correcta
- Comprobar que el archivo `public/css/estilos.css` exista

### Error de conexión a BD

- Verificar credenciales en `config.ini`
- Comprobar que MySQL esté corriendo
- Verificar que la base de datos exista

## 📚 Conceptos Clave para el Examen

1. **MVC**: Modelo, Vista, Controlador
2. **Autoload PSR-4**: Carga automática de clases
3. **Namespaces**: Organización de código
4. **PDO**: PHP Data Objects para BD
5. **Prepared Statements**: Seguridad SQL
6. **Sessions**: Manejo de estado
7. **Password Hashing**: bcrypt con `password_hash()`
8. **Routing**: Enrutamiento de URLs
9. **OOP**: Programación Orientada a Objetos
10. **CRUD**: Create, Read, Update, Delete

## ✅ Checklist de Funcionalidades

- [x] Login con validación
- [x] Logout y destrucción de sesión
- [x] Listar mascotas con información de dueño
- [x] Crear nueva mascota
- [x] Editar mascota existente
- [x] Ver detalle de mascota
- [x] Eliminar mascota
- [x] Listar personas/dueños
- [x] Crear nueva persona
- [x] Editar persona existente
- [x] Ver mascotas de una persona
- [x] Eliminar persona
- [x] Navegación intuitiva
- [x] Mensajes de error y validación
- [x] Diseño responsive
- [x] Contraseñas hasheadas

## 🚀 Próximos Pasos (Mejoras Opcionales)

- [ ] Paginación de resultados
- [ ] Búsqueda y filtros
- [ ] Historial médico de mascotas
- [ ] Citas veterinarias
- [ ] Subida de imágenes
- [ ] Exportar a PDF
- [ ] Envío de emails
- [ ] API REST

---

**Desarrollado como material de estudio para examen de recuperación**  
**Fecha:** Febrero 2026
