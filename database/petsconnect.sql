-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-04-2025 a las 06:07:48
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
-- Base de datos: `petsconnect`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `crear_guardian` (IN `p_id_usuario` INT)   BEGIN
  DECLARE v_id_perfil INT;
  DECLARE v_id_registro INT;

  -- Insertar en perfil
  INSERT INTO t_perfil (nombre, preferencia, descripcion, imagen)
  VALUES ('Perfil Guardian', 'Ninguna', 'Auto-generado', 'default.jpg');

  SET v_id_perfil = LAST_INSERT_ID();

  -- Insertar en registro
  INSERT INTO t_registro (fecha, tipo_usuario)
  VALUES (CURDATE(), 'GUARDIAN');

  SET v_id_registro = LAST_INSERT_ID();

  -- Insertar en guardian
  INSERT INTO t_guardian (id_usuario, id_registro, id_perfil)
VALUES (p_id_usuario, v_id_registro, v_id_perfil);

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_administrador`
--

CREATE TABLE `t_administrador` (
  `n_documento` int(11) NOT NULL,
  `id_registro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_administrador`
--

INSERT INTO `t_administrador` (`n_documento`, `id_registro`, `id_usuario`) VALUES
(1001327862, 3, 3),
(1111111111, 4, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_calificacion`
--

CREATE TABLE `t_calificacion` (
  `id_calificacion` int(11) NOT NULL,
  `puntaje` int(11) NOT NULL,
  `comentario` varchar(100) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `n_documento` int(11) NOT NULL,
  `nit_fundacion` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_donacion`
--

CREATE TABLE `t_donacion` (
  `id_donacion` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `total_donacion` decimal(15,2) NOT NULL,
  `n_documento` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nit_fundacion` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_estado_adopcion`
--

CREATE TABLE `t_estado_adopcion` (
  `id_estado_adopcion` int(11) NOT NULL,
  `tipo_estado` enum('ADOPTADO','EN ADOPCION','EN TRAMITE','TRANSITO') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_fundacion`
--

CREATE TABLE `t_fundacion` (
  `nit_fundacion` bigint(20) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_guardian`
--

CREATE TABLE `t_guardian` (
  `id_registro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_guardian`
--

INSERT INTO `t_guardian` (`id_registro`, `id_usuario`, `id_perfil`) VALUES
(1, 1, 1),
(2, 2, 2),
(5, 5, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_informe`
--

CREATE TABLE `t_informe` (
  `id_informe` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `contenido` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `nit_fundacion` bigint(20) NOT NULL,
  `id_estado_adopcion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_mascota`
--

CREATE TABLE `t_mascota` (
  `id_mascota` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `edad_meses` int(11) DEFAULT NULL CHECK (`edad_meses` >= 0),
  `sexo` enum('macho','hembra') NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `id_tipo_mascota` int(11) NOT NULL,
  `nit_fundacion` bigint(20) NOT NULL,
  `num_serie_vacuna` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_perfil`
--

CREATE TABLE `t_perfil` (
  `id_perfil` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `preferencia` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_perfil`
--

INSERT INTO `t_perfil` (`id_perfil`, `nombre`, `preferencia`, `descripcion`, `imagen`) VALUES
(1, 'Perfil Guardian', 'Ninguna', 'Auto-generado', 'default.jpg'),
(2, 'Perfil Guardian', 'Ninguna', 'Auto-generado', 'default.jpg'),
(3, 'Perfil Guardian', 'Ninguna', 'Auto-generado', 'default.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_proceso_adopcion`
--

CREATE TABLE `t_proceso_adopcion` (
  `id` int(11) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_actializada` datetime NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `nit_fundacion` bigint(11) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_producto`
--

CREATE TABLE `t_producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_producto` varchar(100) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `precio` decimal(15,2) NOT NULL,
  `cantidad_disponible` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_producto`
--

INSERT INTO `t_producto` (`id_producto`, `nombre`, `tipo_producto`, `descripcion`, `precio`, `cantidad_disponible`) VALUES
(1, 'dasda', 'ComidaPerro', 'dads', 11.00, 1),
(2, 'Arena del D1', 'ArenaGato', 'arena de 14kg', 20000.00, 100),
(3, 'Mirringo', 'ComidaGato', 'comida gato barato ', 1000.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_publicacion`
--

CREATE TABLE `t_publicacion` (
  `id_publicacion` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `contenido` varchar(100) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `fecha` date DEFAULT NULL,
  `nit_fundacion` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_recuperar_constrasena`
--

CREATE TABLE `t_recuperar_constrasena` (
  `id_recuperacion` int(11) NOT NULL,
  `codigo_recuperacion` varchar(32) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_expiracion` date NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_red_social`
--

CREATE TABLE `t_red_social` (
  `id_red_social` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `nit_fundacion` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_registro`
--

CREATE TABLE `t_registro` (
  `id_registro` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `tipo_usuario` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_registro`
--

INSERT INTO `t_registro` (`id_registro`, `fecha`, `tipo_usuario`) VALUES
(1, '2025-04-23', 'GUARDIAN'),
(2, '2025-04-24', 'GUARDIAN'),
(3, '2025-04-24', 'ADMIN'),
(4, '2025-04-24', 'ADMIN'),
(5, '2025-04-24', 'GUARDIAN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_tipo_mascota`
--

CREATE TABLE `t_tipo_mascota` (
  `id_tipo_mascota` int(11) NOT NULL,
  `especie` varchar(100) NOT NULL,
  `raza` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_usuario`
--

CREATE TABLE `t_usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `contrasena` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_usuario`
--

INSERT INTO `t_usuario` (`id_usuario`, `nombre`, `apellido`, `contrasena`, `email`, `direccion`, `telefono`) VALUES
(1, 'johan', 'acero', '$2y$10$CH4iOqn/H8DN47gIPn4WO.gPWP/wm1waGPBGWN5S06LZwhI1UVbIa', 'jasdj@gmail.com', '123123dasd', '1231412'),
(2, 'valentina', 'urrego', '$2y$10$MMxd5OXxXtDZgoyaFxLmGuBrnQleEW3xXFzs5QclD9xMQAUs64S8O', 'valentina@gmail.com', '123', '123'),
(3, 'Johan', 'Acero', '$2y$10$RsAyTZoih421KWccHGqvu.BDG73AMY6CMTRkitZWd64KTZoBJDrbC', 'johan@gmail.com', 'Bogotá', '123456789'),
(4, 'Pablo', 'Vela', '$2y$10$OMPvjW0mZJL/0oMXKjMWmOiED1YBP3b4SE9oDNU3Hzhw6/.63NXci', 'pablo@gmail.com', 'Bogotá', '123456789'),
(5, 'Andres', 'Miranda', '$2y$10$PFkIhQAEOslDTKUMIaEj3.8YN7jx8kNMMFUQ304cOiSP1s8CHZAXi', 'miranda@gmail.com', '123', '123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_vacuna`
--

CREATE TABLE `t_vacuna` (
  `num_serie_vacuna` bigint(20) NOT NULL,
  `nombre_vacuna` varchar(100) NOT NULL,
  `fecha_vacunacion` date NOT NULL,
  `direccion_veterinaria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `t_administrador`
--
ALTER TABLE `t_administrador`
  ADD PRIMARY KEY (`n_documento`),
  ADD KEY `fk_id_registro` (`id_registro`),
  ADD KEY `fk_id_usuario` (`id_usuario`);

--
-- Indices de la tabla `t_calificacion`
--
ALTER TABLE `t_calificacion`
  ADD PRIMARY KEY (`id_calificacion`),
  ADD KEY `fk_n_documento` (`n_documento`),
  ADD KEY `fk_nit_fundacion` (`nit_fundacion`);

--
-- Indices de la tabla `t_donacion`
--
ALTER TABLE `t_donacion`
  ADD PRIMARY KEY (`id_donacion`),
  ADD KEY `fk_dona_n_documento` (`n_documento`),
  ADD KEY `fk_dona_id_producto` (`id_producto`),
  ADD KEY `fk_dona_nit_fundacion` (`nit_fundacion`);

--
-- Indices de la tabla `t_estado_adopcion`
--
ALTER TABLE `t_estado_adopcion`
  ADD PRIMARY KEY (`id_estado_adopcion`);

--
-- Indices de la tabla `t_fundacion`
--
ALTER TABLE `t_fundacion`
  ADD PRIMARY KEY (`nit_fundacion`),
  ADD KEY `fk_fundacion_id_usuario` (`id_usuario`),
  ADD KEY `fk_fundacion_id_perfil` (`id_perfil`);

--
-- Indices de la tabla `t_guardian`
--
ALTER TABLE `t_guardian`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_guardian_id_usuario` (`id_usuario`),
  ADD KEY `fk_guardian_id_registro` (`id_registro`),
  ADD KEY `fk_guardian_id_perfil` (`id_perfil`);

--
-- Indices de la tabla `t_informe`
--
ALTER TABLE `t_informe`
  ADD PRIMARY KEY (`id_informe`),
  ADD KEY `fk_infor_id_mascota` (`id_mascota`),
  ADD KEY `fk_infor_nit_fundacion` (`nit_fundacion`),
  ADD KEY `fk_infor_id_estado_adopcion` (`id_estado_adopcion`);

--
-- Indices de la tabla `t_mascota`
--
ALTER TABLE `t_mascota`
  ADD PRIMARY KEY (`id_mascota`),
  ADD KEY `fk_id_tipo_mascota` (`id_tipo_mascota`),
  ADD KEY `fk_mascota_nit_fundacion` (`nit_fundacion`),
  ADD KEY `fk_id_vacuna` (`num_serie_vacuna`);

--
-- Indices de la tabla `t_perfil`
--
ALTER TABLE `t_perfil`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Indices de la tabla `t_proceso_adopcion`
--
ALTER TABLE `t_proceso_adopcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_usuario` (`id_usuario`),
  ADD KEY `fk_id_mascota` (`id_mascota`),
  ADD KEY `fk_id_fundacion` (`nit_fundacion`),
  ADD KEY `fk_id_estado` (`id_estado`);

--
-- Indices de la tabla `t_producto`
--
ALTER TABLE `t_producto`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `t_publicacion`
--
ALTER TABLE `t_publicacion`
  ADD PRIMARY KEY (`id_publicacion`),
  ADD KEY `fk_publi_nit_fundacion` (`nit_fundacion`);

--
-- Indices de la tabla `t_recuperar_constrasena`
--
ALTER TABLE `t_recuperar_constrasena`
  ADD PRIMARY KEY (`id_recuperacion`),
  ADD KEY `fk_recuperar_usuario` (`id_usuario`);

--
-- Indices de la tabla `t_red_social`
--
ALTER TABLE `t_red_social`
  ADD PRIMARY KEY (`id_red_social`),
  ADD KEY `fk_red_nit_fundacion` (`nit_fundacion`);

--
-- Indices de la tabla `t_registro`
--
ALTER TABLE `t_registro`
  ADD PRIMARY KEY (`id_registro`);

--
-- Indices de la tabla `t_tipo_mascota`
--
ALTER TABLE `t_tipo_mascota`
  ADD PRIMARY KEY (`id_tipo_mascota`);

--
-- Indices de la tabla `t_usuario`
--
ALTER TABLE `t_usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `t_vacuna`
--
ALTER TABLE `t_vacuna`
  ADD PRIMARY KEY (`num_serie_vacuna`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `t_calificacion`
--
ALTER TABLE `t_calificacion`
  MODIFY `id_calificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_donacion`
--
ALTER TABLE `t_donacion`
  MODIFY `id_donacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_estado_adopcion`
--
ALTER TABLE `t_estado_adopcion`
  MODIFY `id_estado_adopcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_informe`
--
ALTER TABLE `t_informe`
  MODIFY `id_informe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_mascota`
--
ALTER TABLE `t_mascota`
  MODIFY `id_mascota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_perfil`
--
ALTER TABLE `t_perfil`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `t_proceso_adopcion`
--
ALTER TABLE `t_proceso_adopcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_producto`
--
ALTER TABLE `t_producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `t_publicacion`
--
ALTER TABLE `t_publicacion`
  MODIFY `id_publicacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_recuperar_constrasena`
--
ALTER TABLE `t_recuperar_constrasena`
  MODIFY `id_recuperacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_red_social`
--
ALTER TABLE `t_red_social`
  MODIFY `id_red_social` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_registro`
--
ALTER TABLE `t_registro`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `t_tipo_mascota`
--
ALTER TABLE `t_tipo_mascota`
  MODIFY `id_tipo_mascota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_usuario`
--
ALTER TABLE `t_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `t_administrador`
--
ALTER TABLE `t_administrador`
  ADD CONSTRAINT `fk_id_registro` FOREIGN KEY (`id_registro`) REFERENCES `t_registro` (`id_registro`),
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `t_usuario` (`id_usuario`);

--
-- Filtros para la tabla `t_calificacion`
--
ALTER TABLE `t_calificacion`
  ADD CONSTRAINT `fk_n_documento` FOREIGN KEY (`n_documento`) REFERENCES `t_guardian` (`id_usuario`),
  ADD CONSTRAINT `fk_nit_fundacion` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`);

--
-- Filtros para la tabla `t_donacion`
--
ALTER TABLE `t_donacion`
  ADD CONSTRAINT `fk_dona_id_producto` FOREIGN KEY (`id_producto`) REFERENCES `t_producto` (`id_producto`),
  ADD CONSTRAINT `fk_dona_n_documento` FOREIGN KEY (`n_documento`) REFERENCES `t_guardian` (`id_usuario`),
  ADD CONSTRAINT `fk_dona_nit_fundacion` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`);

--
-- Filtros para la tabla `t_estado_adopcion`
--
ALTER TABLE `t_estado_adopcion`
  ADD CONSTRAINT `t_estado_adopcion_ibfk_1` FOREIGN KEY (`id_estado_adopcion`) REFERENCES `t_proceso_adopcion` (`id_estado`);

--
-- Filtros para la tabla `t_fundacion`
--
ALTER TABLE `t_fundacion`
  ADD CONSTRAINT `fk_fundacion_id_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `t_perfil` (`id_perfil`),
  ADD CONSTRAINT `fk_fundacion_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `t_usuario` (`id_usuario`),
  ADD CONSTRAINT `t_fundacion_ibfk_1` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_proceso_adopcion` (`nit_fundacion`);

--
-- Filtros para la tabla `t_guardian`
--
ALTER TABLE `t_guardian`
  ADD CONSTRAINT `fk_guardian_id_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `t_perfil` (`id_perfil`),
  ADD CONSTRAINT `fk_guardian_id_registro` FOREIGN KEY (`id_registro`) REFERENCES `t_registro` (`id_registro`),
  ADD CONSTRAINT `fk_guardian_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `t_usuario` (`id_usuario`);

--
-- Filtros para la tabla `t_informe`
--
ALTER TABLE `t_informe`
  ADD CONSTRAINT `fk_infor_id_estado_adopcion` FOREIGN KEY (`id_estado_adopcion`) REFERENCES `t_estado_adopcion` (`id_estado_adopcion`),
  ADD CONSTRAINT `fk_infor_id_mascota` FOREIGN KEY (`id_mascota`) REFERENCES `t_mascota` (`id_mascota`),
  ADD CONSTRAINT `fk_infor_nit_fundacion` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`);

--
-- Filtros para la tabla `t_mascota`
--
ALTER TABLE `t_mascota`
  ADD CONSTRAINT `fk_id_tipo_mascota` FOREIGN KEY (`id_tipo_mascota`) REFERENCES `t_tipo_mascota` (`id_tipo_mascota`),
  ADD CONSTRAINT `fk_id_vacuna` FOREIGN KEY (`num_serie_vacuna`) REFERENCES `t_vacuna` (`num_serie_vacuna`),
  ADD CONSTRAINT `fk_mascota_nit_fundacion` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`),
  ADD CONSTRAINT `t_mascota_ibfk_1` FOREIGN KEY (`id_mascota`) REFERENCES `t_proceso_adopcion` (`id_mascota`);

--
-- Filtros para la tabla `t_publicacion`
--
ALTER TABLE `t_publicacion`
  ADD CONSTRAINT `fk_publi_nit_fundacion` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`);

--
-- Filtros para la tabla `t_recuperar_constrasena`
--
ALTER TABLE `t_recuperar_constrasena`
  ADD CONSTRAINT `fk_recuperar_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `t_usuario` (`id_usuario`);

--
-- Filtros para la tabla `t_red_social`
--
ALTER TABLE `t_red_social`
  ADD CONSTRAINT `fk_red_nit_fundacion` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
