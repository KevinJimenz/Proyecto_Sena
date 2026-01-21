-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-01-2026 a las 22:20:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `registro_horas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_matriz`
--

CREATE TABLE `detalle_matriz` (
  `id` int(11) NOT NULL,
  `id_matriz` int(11) NOT NULL,
  `codigo_ficha` bigint(20) NOT NULL,
  `nombre_programa` varchar(355) NOT NULL,
  `dias_mes` varchar(255) NOT NULL,
  `rango_horas` varchar(50) NOT NULL,
  `actividad_aprendizaje` varchar(1000) NOT NULL,
  `resultados_aprendizaje` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_matriz`
--

INSERT INTO `detalle_matriz` (`id`, `id_matriz`, `codigo_ficha`, `nombre_programa`, `dias_mes`, `rango_horas`, `actividad_aprendizaje`, `resultados_aprendizaje`) VALUES
(66, 39, 2671333, 'adso', '1,2,3,4,5', '9-10-AM', 'xd', 'xd'),
(67, 39, 191489148194, 'adso', '1,2,3,4', '12-1-PM', 'xdxd', 'xd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matrices_de_horas`
--

CREATE TABLE `matrices_de_horas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `mes` varchar(15) NOT NULL,
  `tipo_matriz` varchar(50) NOT NULL,
  `total_horas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `matrices_de_horas`
--

INSERT INTO `matrices_de_horas` (`id`, `id_usuario`, `mes`, `tipo_matriz`, `total_horas`) VALUES
(37, 5, 'Julio', 'Evento de 2 Horas', 0),
(38, 5, 'Julio', 'Evento de 6 Horas', 0),
(39, 5, 'Julio', 'Evento de 1 Hora', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(1200) NOT NULL,
  `rol` varchar(15) NOT NULL,
  `tipo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `pass`, `rol`, `tipo`) VALUES
(5, 'Kevin', 'kevincamilo56@gmail.com', '$2y$10$NF6oftj5gBk1W1P0TzP5oe8Fddyp.RG7f42oBGLa2WrrDzN8XbdGS', 'Instructor', 'Instructor Tecnico'),
(6, 'Administrador', 'admin@sena.edu.co', '$2y$10$l8hD5rKEvrIFKS7dQRwS5.NXyQ3yYbIBrSGD.rRw3Dvv2XZCj03IC', 'Administrador', 'Administrador'),
(8, 'Walter Arias', 'walarias@sena.edu.co', '$2y$10$j65mv.T2yABzezu6ciOd6eQZRYnr5acEG71cC3sfD7dD1wteTlyvm', 'Instructor', 'Instructor Tecnico');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalle_matriz`
--
ALTER TABLE `detalle_matriz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_matriz_matrices` (`id_matriz`);

--
-- Indices de la tabla `matrices_de_horas`
--
ALTER TABLE `matrices_de_horas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_matrices_de_horas_usuarios` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_matriz`
--
ALTER TABLE `detalle_matriz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `matrices_de_horas`
--
ALTER TABLE `matrices_de_horas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_matriz`
--
ALTER TABLE `detalle_matriz`
  ADD CONSTRAINT `fk_detalle_matriz_matrices` FOREIGN KEY (`id_matriz`) REFERENCES `matrices_de_horas` (`id`);

--
-- Filtros para la tabla `matrices_de_horas`
--
ALTER TABLE `matrices_de_horas`
  ADD CONSTRAINT `fk_matrices_de_horas_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
