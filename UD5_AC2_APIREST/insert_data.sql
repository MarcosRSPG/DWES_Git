-- Script SQL para insertar datos de prueba en mvcapi
-- Copia y pega esto en tu gestor de BD (phpMyAdmin, MySQL Workbench, etc.)
-- Asegúrate de estar en la BD correcta (nombre: test)

-- 1. Crear tabla de coches (si no existe)
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    color VARCHAR(30) NOT NULL,
    owner VARCHAR(80) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Crear tabla de artículos (si no existe)
CREATE TABLE IF NOT EXISTS articulos (
    id_articulo INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    contenido TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Limpiar datos anteriores (opcional - comenta si quieres mantener datos)
-- TRUNCATE TABLE cars;
-- TRUNCATE TABLE articulos;

-- 4. Insertar coches de ejemplo
INSERT INTO cars (brand, model, color, owner) VALUES 
('Volkswagen', 'Polo', 'Negro', 'Rebeca'),
('Toyota', 'Corolla', 'Blanco', 'Juan'),
('Ford', 'Fiesta', 'Rojo', 'María'),
('Honda', 'Civic', 'Azul', 'Pedro'),
('BMW', 'Serie 3', 'Gris', 'Ana');

-- 5. Insertar artículos de ejemplo
INSERT INTO articulos (titulo, contenido) VALUES 
('Primer Artículo', 'Contenido del primer artículo'),
('Segundo Artículo', 'Contenido del segundo artículo'),
('Tercer Artículo', 'Contenido del tercer artículo');

-- 6. Verificar datos insertados
SELECT 'Coches insertados:' as tipo;
SELECT COUNT(*) as total FROM cars;

SELECT 'Artículos insertados:' as tipo;
SELECT COUNT(*) as total FROM articulos;

-- Mostra todos los coches
SELECT 'Lista de coches:' as tipo;
SELECT * FROM cars;

-- Muestra todos los artículos
SELECT 'Lista de artículos:' as tipo;
SELECT * FROM articulos;
