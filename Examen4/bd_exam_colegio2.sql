-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 23-11-2025 a las 19:12:26
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
-- Base de datos: `bd_exam_colegio2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `cod_asig` int(11) NOT NULL,
  `denominacion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`cod_asig`, `denominacion`) VALUES
(101, 'Matemáticas I'),
(102, 'Historia Contemporánea'),
(103, 'Programación Básica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `cod_asig` int(11) NOT NULL,
  `cod_usu` int(11) NOT NULL,
  `nota` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`cod_asig`, `cod_usu`, `nota`) VALUES
(101, 1, 8.50),
(102, 2, 7.20),
(103, 1, 6.80),
(103, 3, 9.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `cod_usu` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `clave` varchar(32) NOT NULL,
  `telefono` int(11) NOT NULL,
  `cod_postal` int(11) NOT NULL,
  `tipo` enum('tutor','alumno') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`cod_usu`, `nombre`, `usuario`, `clave`, `telefono`, `cod_postal`, `tipo`) VALUES
(1, 'Laura Gómez', 'laura', '81dc9bdb52d04dc20036dbd8313ed055', 612345678, 28001, 'alumno'),
(2, 'Carlos Ruiz', 'carlos', '81dc9bdb52d04dc20036dbd8313ed055', 698745321, 29004, 'alumno'),
(3, 'Ana Torres', 'ana', '81dc9bdb52d04dc20036dbd8313ed055', 677889900, 41002, 'alumno'),
(4, 'Miguel Santos', 'msantos', '81dc9bdb52d04dc20036dbd8313ed055', 655112233, 50018, 'tutor');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`cod_asig`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD UNIQUE KEY `cod_asig` (`cod_asig`,`cod_usu`),
  ADD KEY `cod_usu` (`cod_usu`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cod_usu`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`cod_usu`) REFERENCES `usuarios` (`cod_usu`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`cod_asig`) REFERENCES `asignaturas` (`cod_asig`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
