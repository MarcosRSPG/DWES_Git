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
    `CodCat` int NOT NULL,
    `Nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `Descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
    `CodPed` int NOT NULL,
    `Fecha` date DEFAULT NULL,
    `Enviado` tinyint(1) DEFAULT NULL,
    `Restaurante` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidosproductos`
--

CREATE TABLE `pedidosproductos` (
    `CodPedProd` int NOT NULL,
    `Pedido` int DEFAULT NULL,
    `Producto` int DEFAULT NULL,
    `Unidades` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
    `CodProd` int NOT NULL,
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
    `CodRes` int NOT NULL,
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