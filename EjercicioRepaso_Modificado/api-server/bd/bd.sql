-- ================================================================
-- Base de datos: gestorrestaurantes
-- Descripción: Sistema de gestión de restaurantes con pedidos
-- ================================================================

DROP DATABASE IF EXISTS gestorrestaurantes;
CREATE DATABASE gestorrestaurantes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestorrestaurantes;

-- ================================================================
-- Tabla: restaurantes
-- Descripción: Almacena los datos de los restaurantes registrados
-- ================================================================
CREATE TABLE restaurantes (
    CodRes VARCHAR(36) PRIMARY KEY,
    Correo VARCHAR(100) NOT NULL UNIQUE,
    Clave VARCHAR(255) NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    Telefono VARCHAR(20),
    Direccion VARCHAR(200),
    FechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_correo (Correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Tabla: categorias
-- Descripción: Categorías de productos del menú
-- ================================================================
CREATE TABLE categorias (
    CodCat VARCHAR(36) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Descripcion TEXT,
    INDEX idx_nombre (Nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Tabla: productos
-- Descripción: Productos/platos del menú
-- ================================================================
CREATE TABLE productos (
    CodProd VARCHAR(36) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Descripcion TEXT,
    Precio DECIMAL(10, 2) NOT NULL,
    Categoria VARCHAR(36) NOT NULL,
    Stock INT NOT NULL DEFAULT 0,
    FechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Categoria) REFERENCES categorias(CodCat) ON DELETE CASCADE,
    INDEX idx_categoria (Categoria),
    INDEX idx_nombre (Nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Tabla: pedidos
-- Descripción: Pedidos realizados por los restaurantes
-- ================================================================
CREATE TABLE pedidos (
    CodPed VARCHAR(36) PRIMARY KEY,
    Restaurante VARCHAR(36) NOT NULL,
    FechaPedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    Estado ENUM('abierto', 'enviado', 'procesando', 'entregado', 'cancelado') DEFAULT 'abierto',
    Total DECIMAL(10, 2) DEFAULT 0.00,
    FOREIGN KEY (Restaurante) REFERENCES restaurantes(CodRes) ON DELETE CASCADE,
    INDEX idx_restaurante (Restaurante),
    INDEX idx_estado (Estado),
    INDEX idx_fecha (FechaPedido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- ================================================================
-- Tabla: usuarios_api
-- Descripción: Usuarios para autenticación Basic Auth de la API
-- ================================================================
CREATE TABLE usuarios_api (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL UNIQUE,
    pass VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Tabla: pedidosproductos
-- Descripción: Relación entre pedidos y productos (líneas de pedido)
-- ================================================================
CREATE TABLE pedidosproductos (
    CodPedProd VARCHAR(36) PRIMARY KEY,
    Pedido VARCHAR(36) NOT NULL,
    Producto VARCHAR(36) NOT NULL,
    Unidades INT NOT NULL DEFAULT 1,
    PrecioUnitario DECIMAL(10, 2) NOT NULL,
    Subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (Pedido) REFERENCES pedidos(CodPed) ON DELETE CASCADE,
    FOREIGN KEY (Producto) REFERENCES productos(CodProd) ON DELETE CASCADE,
    INDEX idx_pedido (Pedido),
    INDEX idx_producto (Producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- DATOS DE PRUEBA
-- ================================================================

-- Insertar restaurante de prueba (contraseña: password123)
INSERT INTO restaurantes (CodRes, Correo, Clave, Nombre, Telefono, Direccion) VALUES
('res-001', 'restaurante@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Restaurante El Buen Sabor', '912345678', 'Calle Mayor 123, Madrid'),
('res-002', 'admin@restaurante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'La Tasca Moderna', '923456789', 'Avenida Principal 45, Barcelona');

-- Insertar categorías
INSERT INTO categorias (CodCat, Nombre, Descripcion) VALUES
('cat-001', 'Entrantes', 'Aperitivos y entrantes variados'),
('cat-002', 'Platos Principales', 'Platos principales de la casa'),
('cat-003', 'Postres', 'Dulces y postres caseros'),
('cat-004', 'Bebidas', 'Bebidas frías y calientes'),
('cat-005', 'Ensaladas', 'Ensaladas frescas y saludables');

-- Insertar productos
INSERT INTO productos (CodProd, Nombre, Descripcion, Precio, Categoria, Stock) VALUES
-- Entrantes
('prod-001', 'Croquetas de Jamón', 'Deliciosas croquetas caseras de jamón ibérico (6 unidades)', 8.50, 'cat-001', 50),
('prod-002', 'Patatas Bravas', 'Patatas fritas con salsa brava picante', 6.00, 'cat-001', 100),
('prod-003', 'Gambas al Ajillo', 'Gambas salteadas con ajo y guindilla', 12.50, 'cat-001', 30),
('prod-004', 'Tabla de Quesos', 'Selección de quesos artesanales con membrillo', 14.00, 'cat-001', 20),

-- Platos Principales
('prod-005', 'Paella Valenciana', 'Paella tradicional con pollo, conejo y verduras', 16.50, 'cat-002', 25),
('prod-006', 'Entrecot a la Parrilla', 'Entrecot de ternera (300g) con guarnición', 22.00, 'cat-002', 15),
('prod-007', 'Merluza a la Romana', 'Lomos de merluza rebozados con patatas', 14.50, 'cat-002', 40),
('prod-008', 'Lasaña Boloñesa', 'Lasaña casera con carne y bechamel', 11.00, 'cat-002', 35),

-- Postres
('prod-009', 'Tarta de Queso', 'Cheesecake cremosa con base de galleta', 5.50, 'cat-003', 60),
('prod-010', 'Flan Casero', 'Flan de huevo con caramelo', 4.00, 'cat-003', 80),
('prod-011', 'Tiramisú', 'Postre italiano con café y mascarpone', 6.00, 'cat-003', 45),
('prod-012', 'Coulant de Chocolate', 'Bizcocho con centro fundente de chocolate', 6.50, 'cat-003', 30),

-- Bebidas
('prod-013', 'Agua Mineral', 'Botella de agua mineral (500ml)', 1.50, 'cat-004', 200),
('prod-014', 'Coca Cola', 'Refresco de cola (330ml)', 2.50, 'cat-004', 150),
('prod-015', 'Vino Tinto Reserva', 'Botella de vino tinto reserva D.O. Rioja', 18.00, 'cat-004', 40),
('prod-016', 'Cerveza Estrella', 'Cerveza nacional (330ml)', 2.80, 'cat-004', 120),

-- Ensaladas
('prod-017', 'Ensalada César', 'Lechuga, pollo, parmesano, picatostes y salsa césar', 9.50, 'cat-005', 55),
('prod-018', 'Ensalada Mixta', 'Lechuga, tomate, cebolla, atún y aceitunas', 7.50, 'cat-005', 70),
('prod-019', 'Ensalada de la Huerta', 'Tomate, pepino, pimiento, cebolla con aceite de oliva', 6.50, 'cat-005', 85),
('prod-020', 'Ensalada Caprese', 'Tomate, mozzarella fresca, albahaca y aceite de oliva', 8.50, 'cat-005', 40);

-- Insertar un pedido de ejemplo en estado abierto (carrito)
INSERT INTO pedidos (CodPed, Restaurante, Estado, Total) VALUES
('ped-001', 'res-001', 'abierto', 0.00);

-- Insertar líneas de pedido de ejemplo
INSERT INTO pedidosproductos (CodPedProd, Pedido, Producto, Unidades, PrecioUnitario, Subtotal) VALUES
('pedprod-001', 'ped-001', 'prod-001', 2, 8.50, 17.00),
('pedprod-002', 'ped-001', 'prod-013', 3, 1.50, 4.50);

-- Actualizar total del pedido
UPDATE pedidos SET Total = 21.50 WHERE CodPed = 'ped-001';

-- ================================================================
-- INFORMACIÓN IMPORTANTE
-- ================================================================
-- ================================================================
-- DATOS DE PRUEBA - USUARIOS API
-- ================================================================
-- Contraseñas en texto plano para referencia:
--   admin: admin123
--   profesor: 1234
--   usuario: password

INSERT INTO usuarios_api (user, pass, nombre_completo) VALUES
('admin', '$2y$10$knQd4c.n0UNCvVKGM7NreOTf5G07WLUDLjz4NopZdofKCOoLL14ZC', 'Administrador API'),
('profesor', '$2y$10$FqIf3.Wn4htIfrs4bNCqy.ev2RAcw2VvUin0Y2VtQdREB9vHgNrT6', 'Profesor Test'),
('usuario', '$2y$10$ArEvyPLdkdrUMfpXq/RerO8XKJxBzKhZJiKsdq4Td5aFGwlLVl5GO', 'Usuario Normal');

-- ================================================================
-- INFORMACIÓN IMPORTANTE - AUTENTICACIÓN
-- ================================================================
-- SISTEMA DE AUTENTICACIÓN (Basado en ProyectoAndrea):
-- ======================================================
-- Este sistema autentica usuarios en la BASE DE DATOS en cada petición.
-- Ya NO usa constantes API_BASIC_USER/API_BASIC_PASS en config.php
--
-- Flujo de autenticación:
-- 1. El cliente envía credenciales via Basic Auth (header Authorization)
-- 2. La API busca el usuario en la tabla usuarios_api
-- 3. Verifica la contraseña con password_verify() contra el hash en BD
-- 4. Si coincide, permite el acceso; si no, retorna 401 Unauthorized
--
-- Usuarios API disponibles:
--   - admin / admin123
--   - profesor / 1234
--   - usuario / password
--
-- IMPORTANTE: Esto es INDEPENDIENTE del login de restaurantes.
-- Son dos capas de seguridad diferentes.
--
-- Usuarios de prueba:
--   Correo: restaurante@test.com
--   Contraseña: password123
--
--   Correo: admin@restaurante.com
--   Contraseña: password123
--
-- Basic Auth de la API:
--   Usuario: admin
--   Contraseña: admin123
--
-- Puerto MySQL configurado: 8000 (ver config.php)
-- ================================================================

-- Verificar datos insertados
SELECT 'Restaurantes creados:' AS Info;
SELECT CodRes, Correo, Nombre FROM restaurantes;

SELECT 'Categorías creadas:' AS Info;
SELECT CodCat, Nombre, Descripcion FROM categorias;

SELECT 'Productos creados:' AS Info;
SELECT CodProd, Nombre, Precio, Categoria, Stock FROM productos;

SELECT 'Base de datos creada correctamente' AS Resultado;
