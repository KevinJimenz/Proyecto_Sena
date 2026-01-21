-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-01-2026 a las 01:27:46
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
-- Base de datos: `gestionsena`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `duracion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades_has_perfilinstructores`
--

CREATE TABLE `actividades_has_perfilinstructores` (
  `id` int(11) NOT NULL,
  `actividad` int(11) NOT NULL,
  `perfilInstructor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades_has_recursos`
--

CREATE TABLE `actividades_has_recursos` (
  `id` int(11) NOT NULL,
  `actividad` int(11) NOT NULL,
  `recurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ambientes`
--

CREATE TABLE `ambientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(355) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `recursos` varchar(500) NOT NULL,
  `disponible` tinyint(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ambientes`
--

INSERT INTO `ambientes` (`id`, `nombre`, `capacidad`, `recursos`, `disponible`) VALUES
(1, 'Convergentes S4', 10, 'Televisor, Mesas, Computadores, Tablero, Ventilador, Aire Acondicionado', 1),
(2, 'Convergentes S3', 20, 'Televisor, Tablero, Aire Acondicionado, Mesas', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aprendices`
--

CREATE TABLE `aprendices` (
  `id` int(11) NOT NULL,
  `cedula` bigint(20) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `edad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competencias`
--

CREATE TABLE `competencias` (
  `id` int(11) NOT NULL,
  `codigo` bigint(20) DEFAULT NULL,
  `tipo` varchar(100) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `perfil` int(11) NOT NULL,
  `duracion` bigint(20) NOT NULL,
  `cantidadRaps` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `competencias`
--

INSERT INTO `competencias` (`id`, `codigo`, `tipo`, `nombre`, `perfil`, `duracion`, `cantidadRaps`) VALUES
(3, 121212, 'xd', 'xd', 4, 28, 2),
(4, 11212, 'dadadadad', 'Kevin', 4, 30, 3),
(6, 122222, 'xd', 'pruebaaaaaa', 6, 12, 1),
(7, 4444444444444444, 'dsds', 'qloq', 4, 22, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competencias_has_raps`
--

CREATE TABLE `competencias_has_raps` (
  `id` int(11) NOT NULL,
  `competencia` int(11) NOT NULL,
  `rap` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `competencias_has_raps`
--

INSERT INTO `competencias_has_raps` (`id`, `competencia`, `rap`) VALUES
(40, 4, 1),
(41, 4, 11),
(42, 4, 12),
(57, 3, 15),
(58, 3, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallesaldos`
--

CREATE TABLE `detallesaldos` (
  `id` int(11) NOT NULL,
  `valor` bigint(100) NOT NULL,
  `rubroPresupuestal` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equiposejecutores`
--

CREATE TABLE `equiposejecutores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equiposejecutores`
--

INSERT INTO `equiposejecutores` (`id`, `nombre`) VALUES
(16, 'Lalo'),
(17, 'dandadan');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equiposejecutores_has_instructores`
--

CREATE TABLE `equiposejecutores_has_instructores` (
  `id` int(11) NOT NULL,
  `equipoEjecutor` int(11) NOT NULL,
  `instructor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equiposejecutores_has_instructores`
--

INSERT INTO `equiposejecutores_has_instructores` (`id`, `equipoEjecutor`, `instructor`) VALUES
(30, 17, 3),
(31, 17, 4),
(32, 16, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichas`
--

CREATE TABLE `fichas` (
  `id` int(11) NOT NULL,
  `codigoFicha` bigint(20) NOT NULL,
  `instructorLider` int(11) NOT NULL,
  `cantidadAprendices` int(11) NOT NULL,
  `jornada` varchar(100) NOT NULL,
  `equipoEjecutor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fichas`
--

INSERT INTO `fichas` (`id`, `codigoFicha`, `instructorLider`, `cantidadAprendices`, `jornada`, `equipoEjecutor`) VALUES
(2, 2671333, 3, 30, 'Jornada Vespertina', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichas_has_aprendices`
--

CREATE TABLE `fichas_has_aprendices` (
  `id` int(11) NOT NULL,
  `ficha` int(11) NOT NULL,
  `aprendiz` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_has_fichas`
--

CREATE TABLE `horarios_has_fichas` (
  `id` varchar(45) NOT NULL,
  `horario` int(11) NOT NULL,
  `ficha` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_has_instructores`
--

CREATE TABLE `horarios_has_instructores` (
  `id` int(11) NOT NULL,
  `horario` int(11) NOT NULL,
  `instructor` int(11) NOT NULL,
  `competencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instructores`
--

CREATE TABLE `instructores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `numeroDocumento` bigint(20) NOT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `correo` varchar(255) NOT NULL,
  `perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `instructores`
--

INSERT INTO `instructores` (`id`, `nombre`, `numeroDocumento`, `telefono`, `correo`, `perfil`) VALUES
(3, 'Walter Arias', 1313131, 132131, 'walariax@gmail.com', 4),
(4, 'Juan Camilo Vanegas', 11414141414, 1414141414112, 'jcvanegas@gmail.com', 4),
(5, 'Bladimir Silva', 40198401948, 194184, 'bladimirSilva@gmail.com', 4),
(7, 'Wilfred Valero Angel', 1120484244, 3134424512, 'wilfredvalero@gmail.com', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instructores_has_competencias`
--

CREATE TABLE `instructores_has_competencias` (
  `id` int(11) NOT NULL,
  `instructor` int(11) NOT NULL,
  `competencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `instructores_has_competencias`
--

INSERT INTO `instructores_has_competencias` (`id`, `instructor`, `competencia`) VALUES
(2, 3, 3),
(3, 3, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes`
--

CREATE TABLE `participantes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `numeroIdentidad` bigint(20) NOT NULL,
  `especialidad` varchar(355) NOT NULL,
  `nombreCentro` varchar(255) NOT NULL,
  `regional` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfilinstructores`
--

CREATE TABLE `perfilinstructores` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfilinstructores`
--

INSERT INTO `perfilinstructores` (`id`, `descripcion`) VALUES
(4, 'Ingeniero en Sistemas'),
(5, 'Bilingüe '),
(6, 'Etica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id` int(11) NOT NULL,
  `codigo` bigint(20) NOT NULL,
  `lineaTecnologica` varchar(255) NOT NULL,
  `redTecnologica` varchar(255) NOT NULL,
  `redConocimiento` varchar(255) NOT NULL,
  `version_programa` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ambiente` int(11) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL,
  `duracionGeneral` bigint(20) NOT NULL,
  `duracionEP` bigint(20) NOT NULL,
  `duracionEL` bigint(20) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `certificacion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas`
--

INSERT INTO `programas` (`id`, `codigo`, `lineaTecnologica`, `redTecnologica`, `redConocimiento`, `version_programa`, `nombre`, `ambiente`, `fechaInicio`, `fechaFin`, `duracionGeneral`, `duracionEP`, `duracionEL`, `tipo`, `certificacion`) VALUES
(2, 123456, 'prueba', 'prueba', 'prueba', 10, 'Analisis y Desarrollo de Software', 1, '2025-04-17', '2025-04-17', 27, 6, 21, 'xd', 'Tecnologo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_has_competencias`
--

CREATE TABLE `programas_has_competencias` (
  `id` int(11) NOT NULL,
  `programa` int(11) NOT NULL,
  `competencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas_has_competencias`
--

INSERT INTO `programas_has_competencias` (`id`, `programa`, `competencia`) VALUES
(14, 2, 3),
(15, 2, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_proyectos_fichas`
--

CREATE TABLE `programas_proyectos_fichas` (
  `id` int(11) NOT NULL,
  `Proyecto_Formativo` int(11) NOT NULL,
  `Programa` int(11) NOT NULL,
  `Ficha` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectosformativos`
--

CREATE TABLE `proyectosformativos` (
  `id` int(11) NOT NULL,
  `codigoProyecto` int(11) NOT NULL,
  `codigoPrograma` int(11) NOT NULL,
  `centroFormacion` varchar(255) NOT NULL,
  `nombreProyecto` varchar(255) NOT NULL,
  `regional` varchar(255) NOT NULL,
  `programaFormacion` varchar(255) NOT NULL,
  `tiempoEjecucion` int(11) NOT NULL,
  `empresasOinstuticiones` varchar(255) NOT NULL,
  `palabrasClaves` varchar(1000) NOT NULL,
  `totalRaps` int(11) NOT NULL,
  `especificos` int(11) NOT NULL,
  `transversales` int(11) NOT NULL,
  `tecnicos` int(11) NOT NULL,
  `planteamiento` varchar(1000) NOT NULL,
  `justicificacion` varchar(1000) NOT NULL,
  `objetivosGenerales` varchar(1000) NOT NULL,
  `objetivosEspecificos` varchar(1000) NOT NULL,
  `beneficiarios` varchar(1000) NOT NULL,
  `impactoSocial` varchar(1000) NOT NULL,
  `impactoEconomico` varchar(1000) NOT NULL,
  `impactoAmbiental` varchar(1000) NOT NULL,
  `impactoTecnologo` varchar(1000) NOT NULL,
  `restricciones` varchar(1000) NOT NULL,
  `productos` varchar(1000) NOT NULL,
  `innovacion` varchar(1000) NOT NULL,
  `valoracion` varchar(1000) NOT NULL,
  `numeroInstructores` int(11) NOT NULL,
  `numeroAprendices` int(11) NOT NULL,
  `descripcionAmbiente` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectosformativos_has_actividades`
--

CREATE TABLE `proyectosformativos_has_actividades` (
  `id` int(11) NOT NULL,
  `proyectoFormativo` int(11) NOT NULL,
  `actividad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectosformativos_has_participantes`
--

CREATE TABLE `proyectosformativos_has_participantes` (
  `id` int(11) NOT NULL,
  `proyectoFormativo` int(11) NOT NULL,
  `participante` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectosformativos_has_raps`
--

CREATE TABLE `proyectosformativos_has_raps` (
  `id` int(11) NOT NULL,
  `proyectoFornativo` int(11) NOT NULL,
  `rap` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectosformativos_has_rubropresupuestal`
--

CREATE TABLE `proyectosformativos_has_rubropresupuestal` (
  `id` int(11) NOT NULL,
  `proyectoFornativo` int(11) NOT NULL,
  `rubroPresupuestal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `raps`
--

CREATE TABLE `raps` (
  `id` int(11) NOT NULL,
  `codigo` bigint(55) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `duracion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `raps`
--

INSERT INTO `raps` (`id`, `codigo`, `nombre`, `duracion`) VALUES
(1, 2432424, 'Reunir la informacion necesario para la toma de requerimientos', 5),
(11, 14141414, 'prueba', 14),
(12, 21211212, 'fafafaf', 11),
(14, 323232, 'prueba 2', 12),
(15, 11212, 'Kevin', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `unidadMedida` varchar(255) NOT NULL,
  `codigoOrions` int(11) DEFAULT NULL,
  `valorUnitario` int(11) NOT NULL,
  `valorTotal` int(11) NOT NULL,
  `fuenteRecurso` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubropresupuestal`
--

CREATE TABLE `rubropresupuestal` (
  `id` int(11) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `valor` bigint(20) NOT NULL,
  `rubroPresupuestal` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `actividades_has_perfilinstructores`
--
ALTER TABLE `actividades_has_perfilinstructores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Actividades_has_perfilInstructores_Actividades1` (`actividad`),
  ADD KEY `fk_Actividades_has_perfilInstructores_perfilInstructores1` (`perfilInstructor`);

--
-- Indices de la tabla `actividades_has_recursos`
--
ALTER TABLE `actividades_has_recursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Actividades_has_Recursos_Actividades1` (`actividad`),
  ADD KEY `fk_Actividades_has_Recursos_Recursos1` (`recurso`);

--
-- Indices de la tabla `ambientes`
--
ALTER TABLE `ambientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aprendices`
--
ALTER TABLE `aprendices`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `competencias`
--
ALTER TABLE `competencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Competencias_perfilInstructores1` (`perfil`);

--
-- Indices de la tabla `competencias_has_raps`
--
ALTER TABLE `competencias_has_raps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Competencias_has_Raps_Competencias1` (`competencia`),
  ADD KEY `fk_Competencias_has_Raps_Raps1` (`rap`);

--
-- Indices de la tabla `detallesaldos`
--
ALTER TABLE `detallesaldos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `equiposejecutores`
--
ALTER TABLE `equiposejecutores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `equiposejecutores_has_instructores`
--
ALTER TABLE `equiposejecutores_has_instructores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_equiposEjecutores_has_Instructores_equiposEjecutores1` (`equipoEjecutor`),
  ADD KEY `fk_equiposEjecutores_has_Instructores_Instructores1` (`instructor`);

--
-- Indices de la tabla `fichas`
--
ALTER TABLE `fichas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Fichas_Instructores1` (`instructorLider`),
  ADD KEY `fk_Fichas_equiposEjecutores1` (`equipoEjecutor`);

--
-- Indices de la tabla `fichas_has_aprendices`
--
ALTER TABLE `fichas_has_aprendices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Fichas_has_Aprendices_Fichas` (`ficha`),
  ADD KEY `fk_Fichas_has_Aprendices_Aprendices1` (`aprendiz`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horarios_has_fichas`
--
ALTER TABLE `horarios_has_fichas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Horarios_has_Fichas_Horarios1` (`horario`),
  ADD KEY `fk_Horarios_has_Fichas_Fichas1` (`ficha`);

--
-- Indices de la tabla `horarios_has_instructores`
--
ALTER TABLE `horarios_has_instructores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Horarios_has_Instructores_Horarios1` (`horario`),
  ADD KEY `fk_Horarios_has_Instructores_Instructores1` (`instructor`);

--
-- Indices de la tabla `instructores`
--
ALTER TABLE `instructores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Instructores_perfilInstructores1` (`perfil`);

--
-- Indices de la tabla `instructores_has_competencias`
--
ALTER TABLE `instructores_has_competencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Instructores_has_Competencias_Instructores1` (`instructor`),
  ADD KEY `fk_Instructores_has_Competencias_Competencias1` (`competencia`);

--
-- Indices de la tabla `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `perfilinstructores`
--
ALTER TABLE `perfilinstructores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Programas_Ambientes1` (`ambiente`);

--
-- Indices de la tabla `programas_has_competencias`
--
ALTER TABLE `programas_has_competencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Programas_has_Competencias_Programas1` (`programa`),
  ADD KEY `fk_Programas_has_Competencias_Competencias1` (`competencia`);

--
-- Indices de la tabla `programas_proyectos_fichas`
--
ALTER TABLE `programas_proyectos_fichas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Programas_Proyectos_Fichas_proyectosFormativos1` (`Proyecto_Formativo`),
  ADD KEY `fk_Programas_Proyectos_Fichas_Fichas1` (`Ficha`),
  ADD KEY `fk_Programas_Proyectos_Fichas_Programas1` (`Programa`);

--
-- Indices de la tabla `proyectosformativos`
--
ALTER TABLE `proyectosformativos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyectosformativos_has_actividades`
--
ALTER TABLE `proyectosformativos_has_actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proyectosFormativos_has_Actividades_proyectosFormativos1` (`proyectoFormativo`),
  ADD KEY `fk_proyectosFormativos_has_Actividades_Actividades1` (`actividad`);

--
-- Indices de la tabla `proyectosformativos_has_participantes`
--
ALTER TABLE `proyectosformativos_has_participantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proyectosFormativos_has_participantes_proyectosFormativos1` (`proyectoFormativo`),
  ADD KEY `fk_proyectosFormativos_has_participantes_participantes1` (`participante`);

--
-- Indices de la tabla `proyectosformativos_has_raps`
--
ALTER TABLE `proyectosformativos_has_raps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proyectosFormativos_has_Raps_proyectosFormativos1` (`proyectoFornativo`),
  ADD KEY `fk_proyectosFormativos_has_Raps_Raps1` (`rap`);

--
-- Indices de la tabla `proyectosformativos_has_rubropresupuestal`
--
ALTER TABLE `proyectosformativos_has_rubropresupuestal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proyectosFormativos_has_rubroPresupuestal_proyectosFormati1` (`proyectoFornativo`),
  ADD KEY `fk_proyectosFormativos_has_rubroPresupuestal_rubroPresupuestal1` (`rubroPresupuestal`);

--
-- Indices de la tabla `raps`
--
ALTER TABLE `raps`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rubropresupuestal`
--
ALTER TABLE `rubropresupuestal`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ambientes`
--
ALTER TABLE `ambientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `aprendices`
--
ALTER TABLE `aprendices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `competencias`
--
ALTER TABLE `competencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `competencias_has_raps`
--
ALTER TABLE `competencias_has_raps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `equiposejecutores`
--
ALTER TABLE `equiposejecutores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `equiposejecutores_has_instructores`
--
ALTER TABLE `equiposejecutores_has_instructores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `fichas`
--
ALTER TABLE `fichas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `fichas_has_aprendices`
--
ALTER TABLE `fichas_has_aprendices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `instructores`
--
ALTER TABLE `instructores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `instructores_has_competencias`
--
ALTER TABLE `instructores_has_competencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `perfilinstructores`
--
ALTER TABLE `perfilinstructores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `programas`
--
ALTER TABLE `programas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `programas_has_competencias`
--
ALTER TABLE `programas_has_competencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `programas_proyectos_fichas`
--
ALTER TABLE `programas_proyectos_fichas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proyectosformativos`
--
ALTER TABLE `proyectosformativos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proyectosformativos_has_raps`
--
ALTER TABLE `proyectosformativos_has_raps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `raps`
--
ALTER TABLE `raps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades_has_perfilinstructores`
--
ALTER TABLE `actividades_has_perfilinstructores`
  ADD CONSTRAINT `fk_Actividades_has_perfilInstructores_Actividades1` FOREIGN KEY (`actividad`) REFERENCES `actividades` (`id`),
  ADD CONSTRAINT `fk_Actividades_has_perfilInstructores_perfilInstructores1` FOREIGN KEY (`perfilInstructor`) REFERENCES `perfilinstructores` (`id`);

--
-- Filtros para la tabla `actividades_has_recursos`
--
ALTER TABLE `actividades_has_recursos`
  ADD CONSTRAINT `fk_Actividades_has_Recursos_Actividades1` FOREIGN KEY (`actividad`) REFERENCES `actividades` (`id`),
  ADD CONSTRAINT `fk_Actividades_has_Recursos_Recursos1` FOREIGN KEY (`recurso`) REFERENCES `recursos` (`id`);

--
-- Filtros para la tabla `competencias`
--
ALTER TABLE `competencias`
  ADD CONSTRAINT `fk_Competencias_perfilInstructores1` FOREIGN KEY (`perfil`) REFERENCES `perfilinstructores` (`id`);

--
-- Filtros para la tabla `competencias_has_raps`
--
ALTER TABLE `competencias_has_raps`
  ADD CONSTRAINT `fk_Competencias_has_Raps_Competencias1` FOREIGN KEY (`competencia`) REFERENCES `competencias` (`id`),
  ADD CONSTRAINT `fk_Competencias_has_Raps_Raps1` FOREIGN KEY (`rap`) REFERENCES `raps` (`id`);

--
-- Filtros para la tabla `equiposejecutores_has_instructores`
--
ALTER TABLE `equiposejecutores_has_instructores`
  ADD CONSTRAINT `fk_equiposEjecutores_has_Instructores_Instructores1` FOREIGN KEY (`instructor`) REFERENCES `instructores` (`id`),
  ADD CONSTRAINT `fk_equiposEjecutores_has_Instructores_equiposEjecutores1` FOREIGN KEY (`equipoEjecutor`) REFERENCES `equiposejecutores` (`id`);

--
-- Filtros para la tabla `fichas`
--
ALTER TABLE `fichas`
  ADD CONSTRAINT `fk_Fichas_Instructores1` FOREIGN KEY (`instructorLider`) REFERENCES `instructores` (`id`),
  ADD CONSTRAINT `fk_Fichas_equiposEjecutores1` FOREIGN KEY (`equipoEjecutor`) REFERENCES `equiposejecutores` (`id`);

--
-- Filtros para la tabla `fichas_has_aprendices`
--
ALTER TABLE `fichas_has_aprendices`
  ADD CONSTRAINT `fk_Fichas_has_Aprendices_Aprendices1` FOREIGN KEY (`aprendiz`) REFERENCES `aprendices` (`id`),
  ADD CONSTRAINT `fk_Fichas_has_Aprendices_Fichas` FOREIGN KEY (`ficha`) REFERENCES `fichas` (`id`);

--
-- Filtros para la tabla `horarios_has_fichas`
--
ALTER TABLE `horarios_has_fichas`
  ADD CONSTRAINT `fk_Horarios_has_Fichas_Fichas1` FOREIGN KEY (`ficha`) REFERENCES `fichas` (`id`),
  ADD CONSTRAINT `fk_Horarios_has_Fichas_Horarios1` FOREIGN KEY (`horario`) REFERENCES `horarios` (`id`);

--
-- Filtros para la tabla `horarios_has_instructores`
--
ALTER TABLE `horarios_has_instructores`
  ADD CONSTRAINT `fk_Horarios_has_Instructores_Horarios1` FOREIGN KEY (`horario`) REFERENCES `horarios` (`id`),
  ADD CONSTRAINT `fk_Horarios_has_Instructores_Instructores1` FOREIGN KEY (`instructor`) REFERENCES `instructores` (`id`);

--
-- Filtros para la tabla `instructores`
--
ALTER TABLE `instructores`
  ADD CONSTRAINT `fk_Instructores_perfilInstructores1` FOREIGN KEY (`perfil`) REFERENCES `perfilinstructores` (`id`);

--
-- Filtros para la tabla `instructores_has_competencias`
--
ALTER TABLE `instructores_has_competencias`
  ADD CONSTRAINT `fk_Instructores_has_Competencias_Competencias1` FOREIGN KEY (`competencia`) REFERENCES `competencias` (`id`),
  ADD CONSTRAINT `fk_Instructores_has_Competencias_Instructores1` FOREIGN KEY (`instructor`) REFERENCES `instructores` (`id`);

--
-- Filtros para la tabla `programas`
--
ALTER TABLE `programas`
  ADD CONSTRAINT `fk_Programas_Ambientes1` FOREIGN KEY (`ambiente`) REFERENCES `ambientes` (`id`);

--
-- Filtros para la tabla `programas_has_competencias`
--
ALTER TABLE `programas_has_competencias`
  ADD CONSTRAINT `fk_Programas_has_Competencias_Competencias1` FOREIGN KEY (`competencia`) REFERENCES `competencias` (`id`),
  ADD CONSTRAINT `fk_Programas_has_Competencias_Programas1` FOREIGN KEY (`programa`) REFERENCES `programas` (`id`);

--
-- Filtros para la tabla `programas_proyectos_fichas`
--
ALTER TABLE `programas_proyectos_fichas`
  ADD CONSTRAINT `fk_Programas_Proyectos_Fichas_Fichas1` FOREIGN KEY (`Ficha`) REFERENCES `fichas` (`id`),
  ADD CONSTRAINT `fk_Programas_Proyectos_Fichas_Programas1` FOREIGN KEY (`Programa`) REFERENCES `programas` (`id`),
  ADD CONSTRAINT `fk_Programas_Proyectos_Fichas_proyectosFormativos1` FOREIGN KEY (`Proyecto_Formativo`) REFERENCES `proyectosformativos` (`id`);

--
-- Filtros para la tabla `proyectosformativos_has_actividades`
--
ALTER TABLE `proyectosformativos_has_actividades`
  ADD CONSTRAINT `fk_proyectosFormativos_has_Actividades_Actividades1` FOREIGN KEY (`actividad`) REFERENCES `actividades` (`id`),
  ADD CONSTRAINT `fk_proyectosFormativos_has_Actividades_proyectosFormativos1` FOREIGN KEY (`proyectoFormativo`) REFERENCES `proyectosformativos` (`id`);

--
-- Filtros para la tabla `proyectosformativos_has_participantes`
--
ALTER TABLE `proyectosformativos_has_participantes`
  ADD CONSTRAINT `fk_proyectosFormativos_has_participantes_participantes1` FOREIGN KEY (`participante`) REFERENCES `participantes` (`id`),
  ADD CONSTRAINT `fk_proyectosFormativos_has_participantes_proyectosFormativos1` FOREIGN KEY (`proyectoFormativo`) REFERENCES `proyectosformativos` (`id`);

--
-- Filtros para la tabla `proyectosformativos_has_raps`
--
ALTER TABLE `proyectosformativos_has_raps`
  ADD CONSTRAINT `fk_proyectosFormativos_has_Raps_Raps1` FOREIGN KEY (`rap`) REFERENCES `raps` (`id`),
  ADD CONSTRAINT `fk_proyectosFormativos_has_Raps_proyectosFormativos1` FOREIGN KEY (`proyectoFornativo`) REFERENCES `proyectosformativos` (`id`);

--
-- Filtros para la tabla `proyectosformativos_has_rubropresupuestal`
--
ALTER TABLE `proyectosformativos_has_rubropresupuestal`
  ADD CONSTRAINT `fk_proyectosFormativos_has_rubroPresupuestal_proyectosFormati1` FOREIGN KEY (`proyectoFornativo`) REFERENCES `proyectosformativos` (`id`),
  ADD CONSTRAINT `fk_proyectosFormativos_has_rubroPresupuestal_rubroPresupuestal1` FOREIGN KEY (`rubroPresupuestal`) REFERENCES `rubropresupuestal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
