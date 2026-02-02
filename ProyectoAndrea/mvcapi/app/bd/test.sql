-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 31-01-2026 a las 09:03:53
-- Versión del servidor: 8.0.43
-- Versión de PHP: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `test`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
                             `id_articulo` int NOT NULL,
                             `titulo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id_articulo`, `titulo`) VALUES
                                                      (1, 'Ejemplo artículo 111'),
                                                      (2, 'Ejemplo artículo 2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cars`
--

CREATE TABLE `cars` (
                        `id` int NOT NULL,
                        `brand` varchar(50) DEFAULT NULL,
                        `model` varchar(50) DEFAULT NULL,
                        `color` varchar(30) DEFAULT NULL,
                        `owner` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `color`, `owner`) VALUES
                                                                  (1, 'Volkswagen', 'Polo', 'Negro', 'Rebeca'),
                                                                  (2, 'Toyota', 'Corolla', 'Verde', 'Marcos'),
                                                                  (3, 'Skoda', 'Octavia', 'Gris', 'Juan');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_api`
--

CREATE TABLE `usuarios_api` (
                                `id` int NOT NULL,
                                `user` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
                                `pass` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_api`
--

INSERT INTO `usuarios_api` (`id`, `user`, `pass`) VALUES
    (1, 'api', '$2y$10$NdXTUKuMSHhtEW7CdGM53OgPZ/ILwlOiy5khD7e9dXFlOfnyHed4K');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
    ADD PRIMARY KEY (`id_articulo`);

--
-- Indices de la tabla `cars`
--
ALTER TABLE `cars`
    ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_api`
--
ALTER TABLE `usuarios_api`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
    MODIFY `id_articulo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cars`
--
ALTER TABLE `cars`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios_api`
--
ALTER TABLE `usuarios_api`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
