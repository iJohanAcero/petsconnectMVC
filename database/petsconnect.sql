-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-09-2025 a las 07:11:13
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_agregar_red_social` (IN `p_id_perfil` INT, IN `p_tipo_red` VARCHAR(50), IN `p_url_red` VARCHAR(255))   BEGIN
    -- Validar que el perfil exista
    DECLARE v_perfil_existe INT;
    SELECT COUNT(*) INTO v_perfil_existe FROM t_perfil WHERE id_perfil = p_id_perfil;
    
    IF v_perfil_existe = 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'El perfil especificado no existe';
    ELSE
        -- Insertar red social
        INSERT INTO t_perfil_redes (
            id_perfil, 
            tipo_red, 
            url_red
        ) VALUES (
            p_id_perfil, 
            p_tipo_red, 
            p_url_red
        );
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_crear_solicitud_adopcion` (IN `p_id_usuario` INT, IN `p_id_mascota` INT, IN `p_nit_fundacion` BIGINT, IN `p_estado_civil` VARCHAR(50), IN `p_tipo_documento` VARCHAR(2), IN `p_numero_documento` VARCHAR(30), IN `p_ocupacion` VARCHAR(100), IN `p_tipo_vivienda` VARCHAR(50), IN `p_tiene_patio` BOOLEAN, IN `p_seguridad_ventanas` BOOLEAN, IN `p_personas_hogar` INT, IN `p_ninos_adultos` VARCHAR(100), IN `p_horas_fuera_casa` VARCHAR(100), IN `p_viajes_frecuentes` TEXT, IN `p_experiencia_previas` TEXT, IN `p_otras_mascotas` TEXT, IN `p_mascotas_vacunadas` BOOLEAN, IN `p_compromiso_gastos` BOOLEAN, IN `p_situacion_economica` TEXT, IN `p_motivacion` TEXT, IN `p_expectativas` TEXT)   BEGIN
    DECLARE v_id_formulario INT;
    DECLARE v_id_proceso INT;
    DECLARE v_id_estado INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    -- 1) Insertar formulario
    INSERT INTO t_formulario_adopcion (
        id_usuario, id_mascota, nit_fundacion, estado_civil, tipo_documento, numero_documento,
        ocupacion, tipo_vivienda, tiene_patio, seguridad_ventanas, personas_hogar, ninos_adultos,
        horas_fuera_casa, viajes_frecuentes, experiencia_previas, otras_mascotas,
        mascotas_vacunadas, compromiso_gastos, situacion_economica, motivacion, expectativas
    ) VALUES (
        p_id_usuario, p_id_mascota, p_nit_fundacion, p_estado_civil, p_tipo_documento, p_numero_documento,
        p_ocupacion, p_tipo_vivienda, p_tiene_patio, p_seguridad_ventanas, p_personas_hogar, p_ninos_adultos,
        p_horas_fuera_casa, p_viajes_frecuentes, p_experiencia_previas, p_otras_mascotas,
        p_mascotas_vacunadas, p_compromiso_gastos, p_situacion_economica, p_motivacion, p_expectativas
    );

    SET v_id_formulario = LAST_INSERT_ID();

    -- 2) Buscar el estado "EN TRAMITE" (corregido el campo)
    SELECT id_estado_adopcion
      INTO v_id_estado
      FROM t_estado_adopcion
     WHERE tipo_estado = 'EN TRAMITE'  -- ✅ Ahora usa el campo correcto
     LIMIT 1;

    -- Si no encuentra el estado, usar un fallback
    IF v_id_estado IS NULL THEN
        SET v_id_estado = 2; -- ID que corresponde a 'EN TRAMITE' según tu BD
    END IF;

    -- 3) Crear proceso de adopción
    INSERT INTO t_proceso_adopcion (id_formulario, id_estado)
    VALUES (v_id_formulario, v_id_estado);

    SET v_id_proceso = LAST_INSERT_ID();
    
    COMMIT;

    -- 4) Devolver IDs
    SELECT v_id_formulario AS formulario_id, v_id_proceso AS proceso_id, 'success' AS status;
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
(1001, 23, 31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_causa`
--

CREATE TABLE `t_causa` (
  `id_causa` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `meta` decimal(12,2) DEFAULT NULL,
  `estado_causa` varchar(50) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `nit_fundacion` varchar(20) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `tipo_causa` varchar(50) DEFAULT NULL,
  `public_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_causa`
--

INSERT INTO `t_causa` (`id_causa`, `nombre`, `descripcion`, `meta`, `estado_causa`, `fecha_creacion`, `nit_fundacion`, `imagen_url`, `tipo_causa`, `public_id`) VALUES
(18, 'Jornada de medicamentos', 'medicamentos para mascortas', 1000.00, 'activa', '2025-08-24 07:41:01', '11111', 'https://res.cloudinary.com/dhyowmhw6/image/upload/v1756014063/causas/php755D.jpg', 'medicamentos', 'causas/php755D');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_donacion`
--

CREATE TABLE `t_donacion` (
  `id_donacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_causa` int(11) NOT NULL,
  `nit_fundacion` bigint(20) NOT NULL,
  `stripe_payment_id` varchar(100) DEFAULT NULL,
  `monto` decimal(15,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT 'stripe',
  `estado` enum('pendiente','pagado','fallido','reembolsado') DEFAULT 'pendiente',
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_donacion`
--

INSERT INTO `t_donacion` (`id_donacion`, `id_usuario`, `id_causa`, `nit_fundacion`, `stripe_payment_id`, `monto`, `metodo_pago`, `estado`, `fecha`) VALUES
(1, 32, 18, 11111, 'pi_3S2llQRok1mN1oR60krVGWhe', 50000.00, 'stripe', 'pagado', '2025-09-01 23:24:12'),
(2, 32, 18, 11111, 'pi_3S2lxpRok1mN1oR6120igdM9', 25000.00, 'stripe', 'pagado', '2025-09-01 23:37:01');

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
(3, 'ADOPTADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_formulario_adopcion`
--

CREATE TABLE `t_formulario_adopcion` (
  `id_formulario` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `nit_fundacion` bigint(20) NOT NULL,
  `estado_civil` varchar(50) DEFAULT NULL,
  `tipo_documento` enum('CC','CE') NOT NULL,
  `numero_documento` varchar(30) NOT NULL,
  `ocupacion` varchar(100) DEFAULT NULL,
  `tipo_vivienda` varchar(50) DEFAULT NULL,
  `tiene_patio` tinyint(1) DEFAULT NULL,
  `seguridad_ventanas` tinyint(1) DEFAULT NULL,
  `personas_hogar` int(11) DEFAULT NULL,
  `ninos_adultos` text DEFAULT NULL,
  `horas_fuera_casa` varchar(100) DEFAULT NULL,
  `viajes_frecuentes` text DEFAULT NULL,
  `experiencia_previas` text DEFAULT NULL,
  `otras_mascotas` text DEFAULT NULL,
  `mascotas_vacunadas` tinyint(1) DEFAULT NULL,
  `compromiso_gastos` tinyint(1) DEFAULT NULL,
  `situacion_economica` text DEFAULT NULL,
  `motivacion` text DEFAULT NULL,
  `expectativas` text DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_formulario_adopcion`
--

INSERT INTO `t_formulario_adopcion` (`id_formulario`, `id_usuario`, `id_mascota`, `nit_fundacion`, `estado_civil`, `tipo_documento`, `numero_documento`, `ocupacion`, `tipo_vivienda`, `tiene_patio`, `seguridad_ventanas`, `personas_hogar`, `ninos_adultos`, `horas_fuera_casa`, `viajes_frecuentes`, `experiencia_previas`, `otras_mascotas`, `mascotas_vacunadas`, `compromiso_gastos`, `situacion_economica`, `motivacion`, `expectativas`, `fecha_respuesta`) VALUES
(20, 32, 76767, 11111, 'Casado/a', 'CC', '12313123', 'dasdasd', 'Casa propia', 1, 1, 1231, '2', '2', 'dadas', 'dasd', '2', 1, 1, 'dasds', 'dasdsa', 'dasda', '2025-08-30 00:21:33');

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
(11111, 'Fundacion1', 33, 21);

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
(24, 32, 20);

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
  `id_estado_adopcion` int(11) NOT NULL,
  `public_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_mascota`
--

INSERT INTO `t_mascota` (`id_mascota`, `nombre`, `edad_meses`, `sexo`, `imagen`, `id_tipo_mascota`, `nit_fundacion`, `id_estado_adopcion`, `public_id`) VALUES
(76767, 'Mateo', 21, 'macho', 'https://res.cloudinary.com/dhyowmhw6/image/upload/v1756181258/mascotas/phpA3FD.jpg', 13, 11111, 1, 'mascotas/phpA3FD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_perfil`
--

CREATE TABLE `t_perfil` (
  `id_perfil` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `preferencia` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `public_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_perfil`
--

INSERT INTO `t_perfil` (`id_perfil`, `nombre`, `preferencia`, `descripcion`, `imagen`, `public_id`) VALUES
(20, 'Johan Acero', 'Todos los animales', 'me gustaria adoptar gatos en bogotaa', 'https://res.cloudinary.com/dhyowmhw6/image/upload/v1756445067/perfiles/php1AF1.jpg', 'perfiles/php1AF1'),
(21, 'Fundacion Valentina', 'Gatos', 'fundacion de gatos ', 'https://res.cloudinary.com/dhyowmhw6/image/upload/v1756267115/perfiles/phpBF73.jpg', 'perfiles/phpBF73'),
(23, 'Perfil Fundación', '', '', 'fundacion_default.jpg', NULL),
(24, 'Perfil Fundación', '', '', 'fundacion_default.jpg', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_perfil_redes`
--

CREATE TABLE `t_perfil_redes` (
  `id_red` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `tipo_red` varchar(50) NOT NULL,
  `url_red` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_perfil_redes`
--

INSERT INTO `t_perfil_redes` (`id_red`, `id_perfil`, `tipo_red`, `url_red`) VALUES
(50, 21, 'facebook', 'https://www.youtube.com/');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_proceso_adopcion`
--

CREATE TABLE `t_proceso_adopcion` (
  `id_proceso` int(11) NOT NULL,
  `id_formulario` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `fecha_inicio` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizada` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_proceso_adopcion`
--

INSERT INTO `t_proceso_adopcion` (`id_proceso`, `id_formulario`, `id_estado`, `fecha_inicio`, `fecha_actualizada`) VALUES
(6, 20, 2, '2025-08-30 00:21:33', '2025-08-30 00:21:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_publicacion`
--

CREATE TABLE `t_publicacion` (
  `id_publicacion` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `contenido` varchar(100) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `nit_fundacion` bigint(20) NOT NULL,
  `public_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_publicacion`
--

INSERT INTO `t_publicacion` (`id_publicacion`, `titulo`, `contenido`, `imagen`, `fecha`, `nit_fundacion`, `public_id`) VALUES
(27, 'Jornada de adopcion', 'perros en adopcion', 'https://res.cloudinary.com/dhyowmhw6/image/upload/v1755927648/publicaciones/phpE8D6.jpg', '2025-08-23 07:40:49', 11111, 'publicaciones/phpE8D6');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_recuperar_constrasena`
--

CREATE TABLE `t_recuperar_constrasena` (
  `id_recuperacion` int(11) NOT NULL,
  `codigo_recuperacion` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_expiracion` date NOT NULL,
  `id_usuario` int(11) NOT NULL
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
(23, '2025-07-04', 'ADMIN'),
(24, '2025-07-04', 'GUARDIAN'),
(25, '2025-07-04', 'FUNDACION'),
(27, '2025-07-09', 'FUNDACION'),
(28, '2025-08-02', 'FUNDACION');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_tipo_mascota`
--

CREATE TABLE `t_tipo_mascota` (
  `id_tipo_mascota` int(11) NOT NULL,
  `especie` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_tipo_mascota`
--

INSERT INTO `t_tipo_mascota` (`id_tipo_mascota`, `especie`) VALUES
(12, 'Canino'),
(13, 'Felino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_usuario`
--

CREATE TABLE `t_usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `contrasena` varchar(200) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `google_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_usuario`
--

INSERT INTO `t_usuario` (`id_usuario`, `nombre`, `apellido`, `contrasena`, `email`, `direccion`, `telefono`, `google_id`) VALUES
(31, 'Admin', 'pets', '$2y$10$kpFsZAIko71tAkZIlPRhvegAe./rAO1/8TPpK0cGZWngfvkwh8Ls.', 'admin@gmail.com', 'Bogotá', '123456', NULL),
(32, 'Johan David', 'Acero Pirajan', NULL, 'johanacero8@gmail.com', '', '123123123', '110443786294827582324'),
(33, 'Jhon', 'Doe', '$2y$10$hd0SPDHn1f5Ob312a.Es..tylEKiK55r22FWaHNae57s4TYGomfe6', 'fundacion@gmail.com', 'Bogotá, calle12', '111111', NULL);

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
-- Indices de la tabla `t_causa`
--
ALTER TABLE `t_causa`
  ADD PRIMARY KEY (`id_causa`);

--
-- Indices de la tabla `t_donacion`
--
ALTER TABLE `t_donacion`
  ADD PRIMARY KEY (`id_donacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_causa` (`id_causa`),
  ADD KEY `nit_fundacion` (`nit_fundacion`);

--
-- Indices de la tabla `t_estado_adopcion`
--
ALTER TABLE `t_estado_adopcion`
  ADD PRIMARY KEY (`id_estado_adopcion`);

--
-- Indices de la tabla `t_formulario_adopcion`
--
ALTER TABLE `t_formulario_adopcion`
  ADD PRIMARY KEY (`id_formulario`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_mascota` (`id_mascota`),
  ADD KEY `nit_fundacion` (`nit_fundacion`);

--
-- Indices de la tabla `t_fundacion`
--
ALTER TABLE `t_fundacion`
  ADD PRIMARY KEY (`nit_fundacion`),
  ADD UNIQUE KEY `nombre` (`nombre`),
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
-- Indices de la tabla `t_perfil_redes`
--
ALTER TABLE `t_perfil_redes`
  ADD PRIMARY KEY (`id_red`),
  ADD KEY `id_perfil` (`id_perfil`);

--
-- Indices de la tabla `t_proceso_adopcion`
--
ALTER TABLE `t_proceso_adopcion`
  ADD PRIMARY KEY (`id_proceso`),
  ADD KEY `id_formulario` (`id_formulario`),
  ADD KEY `id_estado` (`id_estado`);

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
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `t_causa`
--
ALTER TABLE `t_causa`
  MODIFY `id_causa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `t_donacion`
--
ALTER TABLE `t_donacion`
  MODIFY `id_donacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `t_estado_adopcion`
--
ALTER TABLE `t_estado_adopcion`
  MODIFY `id_estado_adopcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `t_formulario_adopcion`
--
ALTER TABLE `t_formulario_adopcion`
  MODIFY `id_formulario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `t_informe`
--
ALTER TABLE `t_informe`
  MODIFY `id_informe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_perfil`
--
ALTER TABLE `t_perfil`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `t_perfil_redes`
--
ALTER TABLE `t_perfil_redes`
  MODIFY `id_red` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `t_proceso_adopcion`
--
ALTER TABLE `t_proceso_adopcion`
  MODIFY `id_proceso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `t_publicacion`
--
ALTER TABLE `t_publicacion`
  MODIFY `id_publicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `t_recuperar_constrasena`
--
ALTER TABLE `t_recuperar_constrasena`
  MODIFY `id_recuperacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `t_registro`
--
ALTER TABLE `t_registro`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `t_tipo_mascota`
--
ALTER TABLE `t_tipo_mascota`
  MODIFY `id_tipo_mascota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `t_usuario`
--
ALTER TABLE `t_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
-- Filtros para la tabla `t_donacion`
--
ALTER TABLE `t_donacion`
  ADD CONSTRAINT `t_donacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `t_usuario` (`id_usuario`),
  ADD CONSTRAINT `t_donacion_ibfk_2` FOREIGN KEY (`id_causa`) REFERENCES `t_causa` (`id_causa`),
  ADD CONSTRAINT `t_donacion_ibfk_3` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`);

--
-- Filtros para la tabla `t_formulario_adopcion`
--
ALTER TABLE `t_formulario_adopcion`
  ADD CONSTRAINT `t_formulario_adopcion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `t_usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_formulario_adopcion_ibfk_2` FOREIGN KEY (`id_mascota`) REFERENCES `t_mascota` (`id_mascota`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_formulario_adopcion_ibfk_3` FOREIGN KEY (`nit_fundacion`) REFERENCES `t_fundacion` (`nit_fundacion`) ON DELETE CASCADE;

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
-- Filtros para la tabla `t_perfil_redes`
--
ALTER TABLE `t_perfil_redes`
  ADD CONSTRAINT `t_perfil_redes_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `t_perfil` (`id_perfil`) ON DELETE CASCADE;

--
-- Filtros para la tabla `t_proceso_adopcion`
--
ALTER TABLE `t_proceso_adopcion`
  ADD CONSTRAINT `t_proceso_adopcion_ibfk_1` FOREIGN KEY (`id_formulario`) REFERENCES `t_formulario_adopcion` (`id_formulario`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_proceso_adopcion_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `t_estado_adopcion` (`id_estado_adopcion`);

--
-- Filtros para la tabla `t_recuperar_constrasena`
--
ALTER TABLE `t_recuperar_constrasena`
  ADD CONSTRAINT `fk_recuperar_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `t_usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
