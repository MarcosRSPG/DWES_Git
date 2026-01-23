/* docker run -d --name dwes_db -e MYSQL_ROOT_PASSWORD=rpwd -e MYSQL_DATABASE=gestorrestaurantes -p 8000:3306 -v dwes_data:/var/lib/mysql mysql */

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestorrestaurantes`
--
CREATE DATABASE IF NOT EXISTS `gestorrestaurantes` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gestorrestaurantes`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
    `CodCat` varchar(200) NOT NULL,
    `Nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `Descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
    `CodPed` varchar(200) NOT NULL,
    `Fecha` date DEFAULT NULL,
    `Enviado` tinyint(1) DEFAULT NULL,
    `Restaurante` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidosproductos`
--

CREATE TABLE `pedidosproductos` (
    `CodPedProd` varchar(200) NOT NULL,
    `Pedido` int DEFAULT NULL,
    `Producto` int DEFAULT NULL,
    `Unidades` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
    `CodProd` varchar(200) NOT NULL,
    `Nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `Descripcion` text COLLATE utf8mb4_general_ci,
    `Peso` decimal(10,2) DEFAULT NULL,
    `Stock` int DEFAULT NULL,
    `Categoria` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restaurantes`
--

CREATE TABLE `restaurantes` (
    `CodRes` varchar(200) NOT NULL,
    `Correo` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `Clave` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `Pais` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `CP` int DEFAULT NULL,
    `Ciudad` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `Direccion` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Índices de la tabla `categorias`
--
ALTER TABLE `categorias`
    ADD PRIMARY KEY (`CodCat`);

--
-- Índices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
    ADD PRIMARY KEY (`CodPed`),
    ADD KEY `Restaurante` (`Restaurante`);

--
-- Índices de la tabla `pedidosproductos`
--
ALTER TABLE `pedidosproductos`
    ADD PRIMARY KEY (`CodPedProd`),
    ADD KEY `Pedido` (`Pedido`),
    ADD KEY `Producto` (`Producto`);

--
-- Índices de la tabla `productos`
--
ALTER TABLE `productos`
    ADD PRIMARY KEY (`CodProd`),
    ADD KEY `Categoria` (`Categoria`);

--
-- Índices de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
    ADD PRIMARY KEY (`CodRes`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
    ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`Restaurante`) REFERENCES `restaurantes` (`CodRes`);

--
-- Filtros para la tabla `pedidosproductos`
--
ALTER TABLE `pedidosproductos`
    ADD CONSTRAINT `pedidosproductos_ibfk_1` FOREIGN KEY (`Pedido`) REFERENCES `pedidos` (`CodPed`),
    ADD CONSTRAINT `pedidosproductos_ibfk_2` FOREIGN KEY (`Producto`) REFERENCES `productos` (`CodProd`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
    ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`Categoria`) REFERENCES `categorias` (`CodCat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


/* =========================================================
   0) FIX: tipos de columnas FK para usar geoIDs (varchar)
   ========================================================= */

-- Si ya existen las FK, hay que quitarlas antes de cambiar tipos
ALTER TABLE pedidosproductos DROP FOREIGN KEY pedidosproductos_ibfk_1;
ALTER TABLE pedidosproductos DROP FOREIGN KEY pedidosproductos_ibfk_2;
ALTER TABLE productos        DROP FOREIGN KEY productos_ibfk_1;
ALTER TABLE pedidos          DROP FOREIGN KEY pedidos_ibfk_1;

-- Cambiar columnas FK de INT -> VARCHAR(200)
ALTER TABLE pedidos
    MODIFY Restaurante VARCHAR(200) NULL;

ALTER TABLE productos
    MODIFY Categoria VARCHAR(200) NULL;

ALTER TABLE pedidosproductos
    MODIFY Pedido   VARCHAR(200) NULL,
    MODIFY Producto VARCHAR(200) NULL;

-- Re-crear FKs
ALTER TABLE pedidos
    ADD CONSTRAINT pedidos_ibfk_1
        FOREIGN KEY (Restaurante) REFERENCES restaurantes (CodRes);

ALTER TABLE productos
    ADD CONSTRAINT productos_ibfk_1
        FOREIGN KEY (Categoria) REFERENCES categorias (CodCat);

ALTER TABLE pedidosproductos
    ADD CONSTRAINT pedidosproductos_ibfk_1
        FOREIGN KEY (Pedido) REFERENCES pedidos (CodPed),
    ADD CONSTRAINT pedidosproductos_ibfk_2
        FOREIGN KEY (Producto) REFERENCES productos (CodProd);


/* =========================================================
   1) IMPORTS: datos de ejemplo con geoIDs aleatorios
   (orden: categorias -> restaurantes -> productos -> pedidos -> pedidosproductos)
   ========================================================= */

START TRANSACTION;

-- CATEGORÍAS (3)
INSERT INTO categorias (CodCat, Nombre, Descripcion) VALUES
                                                         ('cat_9mQpZ1sA3KxL8dR2tYwH', 'BebidasAlcoholicas', 'Bebidas con alcohol'),
                                                         ('cat_U2nL6pVb0QfG3zX9sC1a', 'BebidasNoAlcoholicas', 'Bebidas sin alcohol'),
                                                         ('cat_B8xT4hJ2wK7pN1rQ5sZd', 'Comida', 'Comidas y platos');

-- RESTAURANTES (2)
INSERT INTO restaurantes (CodRes, Correo, Clave, Pais, CP, Ciudad, Direccion) VALUES
                                                                                  ('res_R9kLm2n3Pq4St5Uv6Wx7', 'pedidos@tabernalarioja.es', 'rioja2025', 'España', 26001, 'Logroño', 'C/ Laurel 12'),
                                                                                  ('res_J3hG8kL1pQ6sT2vB9nM0', 'contacto@alfarogrill.es', 'alfaro2025', 'España', 26540, 'Alfaro', 'Av. Zaragoza 44');

-- PRODUCTOS (10)
INSERT INTO productos (CodProd, Nombre, Descripcion, Peso, Stock, Categoria) VALUES
-- Alcohol
('prd_A1b2C3d4E5f6G7h8I9j0', 'Cerveza artesana 33cl', 'Cerveza rubia artesanal (33cl).', 0.33, 120, 'cat_9mQpZ1sA3KxL8dR2tYwH'),
('prd_K1l2M3n4O5p6Q7r8S9t0', 'Vino tinto crianza', 'Botella 75cl, D.O. Rioja.', 0.75, 60,  'cat_9mQpZ1sA3KxL8dR2tYwH'),
('prd_U1v2W3x4Y5z6a7B8c9D0', 'Sidra natural', 'Botella 70cl, sidra natural.', 0.70, 40,  'cat_9mQpZ1sA3KxL8dR2tYwH'),

-- Sin alcohol
('prd_E1f2G3h4I5j6K7l8M9n0', 'Agua mineral 50cl', 'Botella 50cl.', 0.50, 300, 'cat_U2nL6pVb0QfG3zX9sC1a'),
('prd_O1p2Q3r4S5t6U7v8W9x0', 'Refresco cola 33cl', 'Lata 33cl.',      0.33, 200, 'cat_U2nL6pVb0QfG3zX9sC1a'),
('prd_Y1z2A3b4C5d6E7f8G9h0', 'Zumo naranja 20cl', 'Brik 20cl.',       0.20, 150, 'cat_U2nL6pVb0QfG3zX9sC1a'),

-- Comida
('prd_I1j2K3l4M5n6O7p8Q9r0', 'Hamburguesa completa', 'Ternera, queso, lechuga, tomate.', 0.35, 80, 'cat_B8xT4hJ2wK7pN1rQ5sZd'),
('prd_S1t2U3v4W5x6Y7z8A9b0', 'Patatas bravas', 'Ración con salsa brava.',              0.30, 90, 'cat_B8xT4hJ2wK7pN1rQ5sZd'),
('prd_C1d2E3f4G5h6I7j8K9l0', 'Ensalada mixta', 'Lechuga, tomate, atún, cebolla.',      0.25, 70, 'cat_B8xT4hJ2wK7pN1rQ5sZd'),
('prd_M1n2O3p4Q5r6S7t8U9v0', 'Bocadillo de pollo', 'Pollo, pimiento, alioli.',          0.28, 65, 'cat_B8xT4hJ2wK7pN1rQ5sZd');

-- PEDIDOS (4)
INSERT INTO pedidos (CodPed, Fecha, Enviado, Restaurante) VALUES
                                                              ('ped_1aZ9xC8vB7nM6kL5jH4', '2025-12-15', 1, 'res_R9kLm2n3Pq4St5Uv6Wx7'),
                                                              ('ped_2qW3eR4tY5uI6oP7aS8', '2025-12-16', 0, 'res_R9kLm2n3Pq4St5Uv6Wx7'),
                                                              ('ped_3dF6gH7jK8lZ9xC1vB2', '2025-12-16', 1, 'res_J3hG8kL1pQ6sT2vB9nM0'),
                                                              ('ped_4mN7bV6cX5zA4sD3fG2', '2025-12-17', 0, 'res_J3hG8kL1pQ6sT2vB9nM0');

-- PEDIDOSPRODUCTOS (líneas de pedido)
INSERT INTO pedidosproductos (CodPedProd, Pedido, Producto, Unidades) VALUES
                                                                          ('ppp_aA1sS2dD3fF4gG5hH6j', 'ped_1aZ9xC8vB7nM6kL5jH4', 'prd_I1j2K3l4M5n6O7p8Q9r0', 2),
                                                                          ('ppp_kK7lL8qQ9wW1eE2rR3t', 'ped_1aZ9xC8vB7nM6kL5jH4', 'prd_S1t2U3v4W5x6Y7z8A9b0', 1),
                                                                          ('ppp_yY4uU5iI6oO7pP8aA9s', 'ped_1aZ9xC8vB7nM6kL5jH4', 'prd_O1p2Q3r4S5t6U7v8W9x0', 2),

                                                                          ('ppp_dD1fF2gG3hH4jJ5kK6l', 'ped_2qW3eR4tY5uI6oP7aS8', 'prd_C1d2E3f4G5h6I7j8K9l0', 1),
                                                                          ('ppp_zZ7xX8cC9vV1bB2nN3m', 'ped_2qW3eR4tY5uI6oP7aS8', 'prd_E1f2G3h4I5j6K7l8M9n0', 2),

                                                                          ('ppp_qQ1wW2eE3rR4tT5yY6u', 'ped_3dF6gH7jK8lZ9xC1vB2', 'prd_M1n2O3p4Q5r6S7t8U9v0', 2),
                                                                          ('ppp_iI7oO8pP9aA1sS2dD3f', 'ped_3dF6gH7jK8lZ9xC1vB2', 'prd_K1l2M3n4O5p6Q7r8S9t0', 1),

                                                                          ('ppp_gG4hH5jJ6kK7lL8zZ9x', 'ped_4mN7bV6cX5zA4sD3fG2', 'prd_U1v2W3x4Y5z6a7B8c9D0', 2),
                                                                          ('ppp_cC1vV2bB3nN4mM5aA6s', 'ped_4mN7bV6cX5zA4sD3fG2', 'prd_Y1z2A3b4C5d6E7f8G9h0', 3);

COMMIT;
