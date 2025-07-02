-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-07-2025 a las 00:31:09
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `crear_fundacion` (IN `p_rep_nombre` VARCHAR(100), IN `p_rep_apellido` VARCHAR(100), IN `p_rep_contrasena` VARCHAR(100), IN `p_rep_email` VARCHAR(100), IN `p_rep_direccion` VARCHAR(200), IN `p_rep_telefono` VARCHAR(20), IN `p_fund_nombre` VARCHAR(100), IN `p_fund_nit` VARCHAR(20))   BEGIN
    DECLARE v_id_usuario INT;
    DECLARE v_id_perfil INT;
    DECLARE v_id_registro INT;

    -- 1. Crear usuario representante legal
    INSERT INTO t_usuario (
        nombre, 
        apellido, 
        contrasena, 
        email, 
        direccion, 
        telefono
    ) VALUES (
        p_rep_nombre, 
        p_rep_apellido, 
        p_rep_contrasena, 
        p_rep_email, 
        p_rep_direccion, 
        p_rep_telefono
    );

    SET v_id_usuario = LAST_INSERT_ID();

    -- 2. Crear perfil por defecto para fundación
    INSERT INTO t_perfil (
        nombre, 
        preferencia, 
        descripcion, 
        imagen
    ) VALUES (
        'Perfil Fundación', 
        '', 
        '', 
        'fundacion_default.jpg'
    );

    SET v_id_perfil = LAST_INSERT_ID();

    -- 3. Crear registro de actividad
    INSERT INTO t_registro (
        fecha, 
        tipo_usuario
    ) VALUES (
        CURDATE(), 
        'FUNDACION'
    );

    SET v_id_registro = LAST_INSERT_ID();

    -- 4. Insertar fundación con relaciones
    INSERT INTO t_fundacion (
        nit_fundacion, 
        nombre, 
        id_usuario, 
        id_perfil
        -- NOTA: Se quitó id_registro porque no existe en tu tabla t_fundacion
    ) VALUES (
        p_fund_nit, 
        p_fund_nombre, 
        v_id_usuario, 
        v_id_perfil
    );
END$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_mascota` (IN `p_id_mascota` VARCHAR(30), IN `p_nombre` VARCHAR(50), IN `p_edad_meses` INT, IN `p_sexo` ENUM('macho','hembra'), IN `p_imagen` VARCHAR(255), IN `p_id_tipo_mascota` INT, IN `p_nit_fundacion` VARCHAR(20), IN `p_id_estado_adopcion` INT)   BEGIN
    DECLARE tipo_existe INT DEFAULT 0;
    DECLARE fundacion_existe INT DEFAULT 0;

    -- Validar tipo de mascota
    SELECT COUNT(*) INTO tipo_existe
    FROM t_tipo_mascota
    WHERE id_tipo_mascota = p_id_tipo_mascota;

    -- Validar fundación
    SELECT COUNT(*) INTO fundacion_existe
    FROM t_fundacion
    WHERE nit_fundacion = p_nit_fundacion;

    -- Insertar si todo es válido
    IF tipo_existe > 0 AND fundacion_existe > 0 THEN
        INSERT INTO t_mascota (
            id_mascota,
            nombre,
            edad_meses,
            sexo,
            imagen,
            id_tipo_mascota,
            nit_fundacion,
            id_estado_adopcion
        ) VALUES (
            p_id_mascota,
            p_nombre,
            p_edad_meses,
            p_sexo,
            p_imagen,
            p_id_tipo_mascota,
            p_nit_fundacion,
            p_id_estado_adopcion
        );
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: tipo de mascota o fundación no válida.';
    END IF;
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

--
-- Volcado de datos para la tabla `t_estado_adopcion`
--

INSERT INTO `t_estado_adopcion` (`id_estado_adopcion`, `tipo_estado`) VALUES
(1, 'EN ADOPCION'),
(2, 'EN TRAMITE'),
(3, 'ADOPTADO'),
(4, 'TRANSITO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_fundacion`
--

CREATE TABLE `t_fundacion` (
  `nit_fundacion` bigint(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_fundacion`
--

INSERT INTO `t_fundacion` (`nit_fundacion`, `nombre`, `id_usuario`, `id_perfil`) VALUES
(1234567890, 'TRE', 23, 15),
(8493820102, 'Peluditos', 24, 16);

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
(5, 5, 3),
(6, 6, 4),
(7, 7, 5),
(8, 8, 6);

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
  `id_estado_adopcion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_mascota`
--

INSERT INTO `t_mascota` (`id_mascota`, `nombre`, `edad_meses`, `sexo`, `imagen`, `id_tipo_mascota`, `nit_fundacion`, `id_estado_adopcion`) VALUES
(98765, 'Mota', 19, 'hembra', '', 5, 1234567890, 3),
(123567, 'Mico', 22, 'hembra', '', 5, 1234567890, 2);

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
(3, 'Perfil Guardian', 'Ninguna', 'Auto-generado', 'default.jpg'),
(4, 'Perfil Guardian', 'Ninguna', 'Auto-generado', 'default.jpg'),
(5, 'Perfil Guardian', 'Ninguna', 'Auto-generado', 'default.jpg'),
(6, 'Perfil Guardian', 'Ninguna', 'Auto-generado', 'default.jpg'),
(7, 'Perfil Fundación', '', '', 'fundacion_default.jpg'),
(8, 'Perfil Fundación', '', '', 'fundacion_default.jpg'),
(9, 'Perfil Fundación', '', '', 'fundacion_default.jpg'),
(10, 'Perfil Fundación', '', '', 'fundacion_default.jpg'),
(11, 'Perfil Fundación', '', '', 'fundacion_default.jpg'),
(12, 'Perfil Fundación', '', '', 'fundacion_default.jpg'),
(13, 'Perfil Fundación', '', '', 'fundacion_default.jpg'),
(14, 'Perfil Fundación', '', '', 'fundacion_default.jpg'),
(15, 'Perfil Fundación', '', '', 'fundacion_default.jpg'),
(16, 'Perfil Fundación', '', '', 'fundacion_default.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_proceso_adopcion`
--

CREATE TABLE `t_proceso_adopcion` (
  `id` int(11) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_actualizada` datetime NOT NULL,
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
(58, 'Arena Ara', 'ArenaGato', 'ytvewa', 123456789.00, 234567);

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
  `codigo_recuperacion` varchar(20) NOT NULL,
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
(5, '2025-04-24', 'GUARDIAN'),
(6, '2025-05-15', 'GUARDIAN'),
(7, '2025-06-03', 'GUARDIAN'),
(8, '2025-06-05', 'GUARDIAN'),
(9, '2025-06-24', 'FUNDACION'),
(10, '2025-06-24', 'FUNDACION'),
(11, '2025-06-24', 'FUNDACION'),
(12, '2025-06-24', 'FUNDACION'),
(13, '2025-06-24', 'FUNDACION'),
(14, '2025-06-24', 'FUNDACION'),
(15, '2025-06-24', 'FUNDACION'),
(16, '2025-06-25', 'FUNDACION'),
(17, '2025-06-26', 'FUNDACION'),
(18, '2025-07-02', 'FUNDACION');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_tipo_mascota`
--

CREATE TABLE `t_tipo_mascota` (
  `id_tipo_mascota` int(11) NOT NULL,
  `especie` varchar(100) NOT NULL,
  `raza` varchar(100) DEFAULT NULL,
  `tamaño` varchar(20) NOT NULL,
  `tipo_pelaje` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_tipo_mascota`
--

INSERT INTO `t_tipo_mascota` (`id_tipo_mascota`, `especie`, `raza`, `tamaño`, `tipo_pelaje`) VALUES
(5, 'Perro', 'Labrador Retriever	', 'Grande', 'Corto y denso'),
(6, 'Gato', 'Siamés', 'Mediano', 'Corto'),
(7, 'Perro', 'Chihuahua', 'Pequeño', 'Corto'),
(8, 'Gato', 'Persa', 'Mediano', 'Largo'),
(9, 'Perro', 'Bulldog Francés	', 'Pequeño', 'Corto');

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
(5, 'Andres', 'Miranda', '$2y$10$PFkIhQAEOslDTKUMIaEj3.8YN7jx8kNMMFUQ304cOiSP1s8CHZAXi', 'miranda@gmail.com', '123', '123'),
(6, 'Kathe', 'Rojas', '$2y$10$u7mOBJMay737QKkGi7TmTOJq8nhc4BEh3iOvaNTcGXe9Xazgsyry2', 'katherojas.2805@hotmail.com', 'hgad', '78162344'),
(7, 'Kathe', 'Rojas', '$2y$10$g3XvGGIfvEhw4g3teLGbDegTbAbH7DYXzUnvAIA.XffAY3j40TaDa', 'katherojas.2805@hotmail.com', 'jhaf', '786218731'),
(8, 'Lina ', 'Bohorquez', '$2y$10$lukvLA6yr73Ibdl5wCbWZuKRiqIvfAFA2Zdy4hIFq3aUdETwvi9lS', 'bhrqzln@gmail.com', 'cra1 #43b - 47 sur', '3219192801'),
(9, 'Ana', 'Carreño', '$2y$10$8xSeuZd2Swz7u2p6LUYVMOgZXZQ647Qhjyme1.uEPxCH16YGglHhq', 'a@gmail.com', 'wjnidc9824', '7658439'),
(10, 'Ana', 'Carreño', '$2y$10$mkEh6sLEFjSTFcsQDws0eeS5ZVFZKMPx0GYsBhn./ouLaD2cAyp0u', 'a@gmail.com', 'wjnidc9824', '7658439'),
(11, 'Ana', 'Carreño', '$2y$10$6qGOOu9M7xuQTb6dg4RsWuHt1o.IIzHPKi9cJMKfcebD94D4xHn5C', 'wz@dfh.com', 'wjnidc9824', '981729812'),
(12, 'Ana', 'Carreño', '$2y$10$l39wAkDAis4DVvRv54rfq.OMj9c.JYJGjOyB295ulsi51VLThi.Pm', 'wz@dfh.com', 'wjnidc9824', '981729812'),
(13, 'Ana', 'Carreño', '$2y$10$/PrfJ335DoCtwGh9Lw.IIu4.VUp4.q48ZBPF0VAt3mo16Ys2J8EAy', 'wz@dfh.com', 'wjnidc9824', '981729812'),
(14, 'kt', 'rojas', '$2y$10$qF0zd7rQ4QhJ/iW0bq.SDO11ixjkQzguyws30XqLWMw0945pR8YYm', 'kt@rojas.com', 'injsfs', '876123'),
(15, 'kt', 'rojas', '$2y$10$i/c9K.VYvf9gEtB/wqOEJ.utkrtdaJ.RpRAoTKefMs2oFkx2MLUui', 'kt@rojas.com', 'ftbkmls', '1234567890'),
(16, 'kt', 'rojas', '$2y$10$XrwWSwzPxZVQqOLCaXuwz.W.7HetdDH3yI08uYDFqtO5JvHpHhchC', 'kt@rojas.com', 'injsfs', '876123'),
(17, 'kt', 'rojas', '$2y$10$zOfC5bk8r7I0617lCW6a8OFIrOfNVm9SIX4lpx8ZztThA63eu2xI.', 'kt@rojas.com', 'injsfs', '876123'),
(18, 'kt', 'rojas', '$2y$10$A2GEVLu/uuzam1A02qfhkuMJJJqVtDvfy52r2ccytM.qPeOssVvka', 'kt@rojas.com', 'injsfs', '876123'),
(19, 'kt', 'rojas', '$2y$10$yT6sstoHhEPRuBWB8Zxe2./YFC34i5BMznEy6jxDZEKaK5MRF.2xa', 'kt@rojas.com', 'injsfs', '876123'),
(23, 'kt', 'rojas', '$2y$10$IOjA/Cw5JDr6/zp7A4PMkOqzrRxdTI1M5cyNrPxrzNU5bnmf74Am6', 'kt@rojas.com', 'ftbkmls', '986398132'),
(24, 'Daniel', 'Reyes', '$2y$10$GsH0fEsxh2MRiWwjtHqFzOnCBDN05F9MAZFm5ft5UFz/.TLbkjIF2', 'dr@gmail.com', 'cra4#56-38', '3160538456');

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
  ADD KEY `id_estado_adopcion` (`id_estado_adopcion`);

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
  MODIFY `id_estado_adopcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `t_informe`
--
ALTER TABLE `t_informe`
  MODIFY `id_informe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_perfil`
--
ALTER TABLE `t_perfil`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `t_proceso_adopcion`
--
ALTER TABLE `t_proceso_adopcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_producto`
--
ALTER TABLE `t_producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

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
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `t_tipo_mascota`
--
ALTER TABLE `t_tipo_mascota`
  MODIFY `id_tipo_mascota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `t_usuario`
--
ALTER TABLE `t_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- Filtros para la tabla `t_fundacion`
--
ALTER TABLE `t_fundacion`
  ADD CONSTRAINT `fk_fundacion_id_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `t_perfil` (`id_perfil`),
  ADD CONSTRAINT `fk_fundacion_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `t_usuario` (`id_usuario`);

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
  ADD CONSTRAINT `fk_mascota_nit_fundacion` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`),
  ADD CONSTRAINT `t_mascota_ibfk_2` FOREIGN KEY (`id_estado_adopcion`) REFERENCES `t_estado_adopcion` (`id_estado_adopcion`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_proceso_adopcion`
--
ALTER TABLE `t_proceso_adopcion`
  ADD CONSTRAINT `fk_estado_adopcion` FOREIGN KEY (`id_estado`) REFERENCES `t_estado_adopcion` (`id_estado_adopcion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proceso_fundacion` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proceso_mascota` FOREIGN KEY (`id_mascota`) REFERENCES `t_mascota` (`id_mascota`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_red_nit_fundacion` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
