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
