# Configuración de los Proyectos mvcapi y mvccurl

## Requisitos previos

- PHP 7.4 o superior con soporte para PDO MySQL
- Servidor Apache con mod_rewrite habilitado
- MySQL o MariaDB
- Composer (para instalar las dependencias)

## Pasos de instalación

### 1. Instalar dependencias con Composer

En la carpeta raíz de **mvcapi**:

```bash
cd c:\Users\Vespertino\Documents\DAW2_Marcos\DesarrolloServidor\DWES\UD5_AC2_APIREST\mvcapi
composer install
```

En la carpeta raíz de **mvccurl**:

```bash
cd c:\Users\Vespertino\Documents\DAW2_Marcos\DesarrolloServidor\DWES\UD5_AC2_APIREST\mvccurl
composer install
```

### 2. Configurar la base de datos

#### Opción A: Usando phpMyAdmin o un gestor gráfico (recomendado)

1. Abre tu gestor de BD (phpMyAdmin, MySQL Workbench, DBeaver, etc.)
2. Selecciona la base de datos `test`
3. Copia el contenido del archivo `insert_data.sql` (está en la raíz de UD5_AC2_APIREST)
4. Pégalo en la ventana de SQL y ejecuta

#### Opción B: Usando línea de comandos

```bash
cd c:\Users\Vespertino\Documents\DAW2_Marcos\DesarrolloServidor\DWES\UD5_AC2_APIREST
mysql -h localhost -u root -p test < insert_data.sql
```

#### Opción C: Script automático en el navegador

1. Abre en el navegador: `http://miwww/UD5_AC2_APIREST/mvcapi/public/setup_data.php`
2. Este script creará las tablas e insertará los datos automáticamente

**SQL para ejecutar manualmente:**

```sql
-- Crear tabla de coches
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    color VARCHAR(30) NOT NULL,
    owner VARCHAR(80) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla de artículos
CREATE TABLE IF NOT EXISTS articulos (
    id_articulo INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    contenido TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos de ejemplo en coches
INSERT INTO cars (brand, model, color, owner) VALUES
('Volkswagen', 'Polo', 'Negro', 'Rebeca'),
('Toyota', 'Corolla', 'Blanco', 'Juan'),
('Ford', 'Fiesta', 'Rojo', 'María'),
('Honda', 'Civic', 'Azul', 'Pedro'),
('BMW', 'Serie 3', 'Gris', 'Ana');

-- Insertar datos de ejemplo en artículos
INSERT INTO articulos (titulo, contenido) VALUES
('Primer Artículo', 'Contenido del primer artículo'),
('Segundo Artículo', 'Contenido del segundo artículo'),
('Tercer Artículo', 'Contenido del tercer artículo');
```

### 3. Configurar las URLs en config.php

En **mvcapi/app/config/config.php**, verifica:

- Las credenciales de base de datos (DB_HOST, DB_USUARIO, DB_PASSWORD, DB_NOMBRE)
- Las URLs (RUTA_URL) deben apuntar a tu servidor: `http://miwww/UD5_AC2_APIREST/mvcapi/`

En **mvccurl/app/config/config.php**, verifica:

- Las URLs (RUTA_URL) deben apuntar a tu servidor: `http://miwww/UD5_AC2_APIREST/mvccurl/`
- La URL de la API (API_BASE_URL) debe apuntar a: `http://miwww/UD5_AC2_APIREST/mvcapi`

### 4. Acceder a los proyectos

**mvcapi (Servidor API REST):**

- Inicio: `http://miwww/UD5_AC2_APIREST/mvcapi/`
- Contacto: `http://miwww/UD5_AC2_APIREST/mvcapi/Paginas/contacto`
- Artículos: `http://miwww/UD5_AC2_APIREST/mvcapi/Articulos/index`
- Alta de coche: `http://miwww/UD5_AC2_APIREST/mvcapi/Paginas/cars_form`
- API Debug: `http://miwww/UD5_AC2_APIREST/mvcapi/apicar/debug`

**mvccurl (Cliente cURL):**

- Inicio (Listado de coches desde API): `http://miwww/UD5_AC2_APIREST/mvccurl/`
- Contacto: `http://miwww/UD5_AC2_APIREST/mvccurl/Paginas/contacto`
- Alta de coche: `http://miwww/UD5_AC2_APIREST/mvccurl/Paginas/cars_form`

### 5. Autenticación de la API

Las credenciales para acceder a la API REST son:

- Usuario: `profesor`
- Contraseña: `1234`

Estas credenciales se encuentran en `config/config.php` de ambos proyectos bajo las constantes `API_BASIC_USER` y `API_BASIC_PASS`.

## Estructura de carpetas

```
mvcapi/
├── app/
│   ├── config/         # Configuración (config.php)
│   ├── controladores/  # ApiCar, Articulos, Paginas
│   ├── modelos/        # Car, Articulo
│   ├── librerias/      # Core, Controlador, Db
│   ├── vistas/         # Templates HTML
│   ├── bd/             # Scripts SQL
│   └── iniciador.php   # Archivo de inicialización
├── public/
│   ├── index.php       # Punto de entrada
│   ├── .htaccess       # Reescritura de URLs
│   └── css/            # Estilos
├── vendor/             # Dependencias de Composer
└── composer.json       # Configuración de Composer

mvccurl/ (estructura similar)
```

## Endpoints de la API REST (mvcapi)

### Autenticación

Todos los endpoints requieren Basic Auth con usuario: `profesor` y contraseña: `1234`

### Endpoints de Coches

- **GET /apicar/cars** - Obtener lista de coches

  ```bash
  curl -u profesor:1234 http://miwww/UD5_AC2_APIREST/mvcapi/apicar/cars
  ```

- **POST /apicar/cars** - Crear nuevo coche

  ```bash
  curl -u profesor:1234 -X POST -H "Content-Type: application/json" \
    -d '{"brand":"Audi","model":"A3","color":"Plateado","owner":"Carlos"}' \
    http://miwww/UD5_AC2_APIREST/mvcapi/apicar/cars
  ```

- **GET /apicar/car/{id}** - Obtener coche por ID

  ```bash
  curl -u profesor:1234 http://miwww/UD5_AC2_APIREST/mvcapi/apicar/car/1
  ```

- **PUT /apicar/car/{id}** - Actualizar coche

  ```bash
  curl -u profesor:1234 -X PUT -H "Content-Type: application/json" \
    -d '{"brand":"Audi","model":"A4","color":"Negro","owner":"Carlos"}' \
    http://miwww/UD5_AC2_APIREST/mvcapi/apicar/car/1
  ```

- **DELETE /apicar/car/{id}** - Eliminar coche

  ```bash
  curl -u profesor:1234 -X DELETE http://miwww/UD5_AC2_APIREST/mvcapi/apicar/car/1
  ```

- **GET /apicar/debug** - Ver información de depuración de la petición HTTP

## Depuración y diagnóstico

### Si mvccurl no muestra coches:

1. **Verifica que los datos existan**:
   - Abre: `http://miwww/UD5_AC2_APIREST/mvccurl/public/test_api.php`
   - Este script te mostrará exactamente qué devuelve la API de mvcapi

2. **Si la API devuelve vacío**:
   - Ve a `http://miwww/UD5_AC2_APIREST/mvcapi/public/setup_data.php`
   - Esto insertará automáticamente 5 coches de prueba

3. **Si sigue sin haber datos**:
   - Verifica que la BD `test` existe
   - Verifica en `config/config.php` los datos de conexión: DB_HOST, DB_USUARIO, DB_PASSWORD, DB_NOMBRE
   - Comprueba que la tabla `cars` existe ejecutando: `SELECT * FROM cars;`

### Scripts de diagnóstico:

- **mvcapi**: `http://miwww/UD5_AC2_APIREST/mvcapi/public/diagnostico.php`
  - Verifica que todas las clases están disponibles
  - Verifica conexión a BD
  - Verifica que las vistas existen

- **mvccurl**: `http://miwww/UD5_AC2_APIREST/mvccurl/public/diagnostico.php`
  - Verifica que cURL está habilitado
  - Verifica que puede conectar con la API

- **Prueba de API**: `http://miwww/UD5_AC2_APIREST/mvccurl/public/test_api.php`
  - Muestra exactamente qué devuelve la API en formato JSON
  - Útil para ver si hay errores

### Scripts de utilidad:

- **Setup de datos**: `http://miwww/UD5_AC2_APIREST/mvcapi/public/setup_data.php`
  - Crea las tablas automáticamente
  - Inserta 5 coches y 3 artículos de ejemplo

## Notas importantes

1. **Composer es obligatorio**: Sin ejecutar `composer install`, el autoloader no funcionará y los controladores no se cargarán.

2. **Base de datos**: Ambos proyectos usan la misma base de datos `test`. Asegúrate de que exista y esté configurada correctamente en `config/config.php`.

3. **Rutas**: Las rutas en `.htaccess` están configuradas para `/UD5_AC2_APIREST/`. Si tu estructura es diferente, actualiza `RewriteBase`.

4. **Módulo mod_rewrite**: Apache debe tener habilitado `mod_rewrite` para que las reescrituras de URL funcionen correctamente.

5. **Permisos**: Asegúrate de que las carpetas `public/` y `app/` tengan permisos de lectura/ejecución.

6. **cURL es obligatorio para mvccurl**: La extensión cURL debe estar habilitada en PHP para que mvccurl pueda conectarse a mvcapi.
