-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-06-2025 a las 04:47:25
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
-- Base de datos: `gretasoft`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AgendarCita` (IN `p_cliente_id` INT, IN `p_trabajador_id` INT, IN `p_servicio_id` INT, IN `p_fecha` DATE, IN `p_hora_inicio` TIME, IN `p_observaciones` TEXT)   BEGIN
    DECLARE nueva_cita_id INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    INSERT INTO citas (cliente_id, trabajador_id, servicio_id, fecha, hora_inicio, observaciones)
    VALUES (p_cliente_id, p_trabajador_id, p_servicio_id, p_fecha, p_hora_inicio, p_observaciones);
    
    SET nueva_cita_id = LAST_INSERT_ID();
    
    -- Crear notificación para el cliente
    INSERT INTO notificaciones (usuario_id, titulo, mensaje, tipo)
    VALUES (p_cliente_id, 'Cita agendada', 
            CONCAT('Su cita ha sido agendada para el ', p_fecha, ' a las ', p_hora_inicio), 
            'confirmacion');
    
    COMMIT;
    
    SELECT 'Cita agendada exitosamente' as mensaje, nueva_cita_id as cita_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerDisponibilidad` (IN `p_trabajador_id` INT, IN `p_fecha` DATE)   BEGIN
    DECLARE dia_trabajo VARCHAR(15);
    
    -- Convertir día a español
    CASE DAYNAME(p_fecha)
        WHEN 'Monday' THEN SET dia_trabajo = 'lunes';
        WHEN 'Tuesday' THEN SET dia_trabajo = 'martes';
        WHEN 'Wednesday' THEN SET dia_trabajo = 'miercoles';
        WHEN 'Thursday' THEN SET dia_trabajo = 'jueves';
        WHEN 'Friday' THEN SET dia_trabajo = 'viernes';
        WHEN 'Saturday' THEN SET dia_trabajo = 'sabado';
        WHEN 'Sunday' THEN SET dia_trabajo = 'domingo';
    END CASE;
    
    -- Obtener horario de trabajo y citas ocupadas
    SELECT 
        ht.hora_inicio as horario_inicio,
        ht.hora_fin as horario_fin,
        COALESCE(GROUP_CONCAT(
            CONCAT(c.hora_inicio, '-', c.hora_fin) 
            ORDER BY c.hora_inicio SEPARATOR ','
        ), '') as citas_ocupadas
    FROM horarios_trabajo ht
    LEFT JOIN citas c ON (
        c.trabajador_id = ht.trabajador_id 
        AND c.fecha = p_fecha 
        AND c.estado IN ('pendiente', 'confirmada', 'en_proceso')
    )
    WHERE ht.trabajador_id = p_trabajador_id
    AND ht.dia_semana = dia_trabajo
    AND ht.activo = 1
    GROUP BY ht.hora_inicio, ht.hora_fin;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `trabajador_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `estado` enum('pendiente','confirmada','en_proceso','completada','cancelada','no_asistio') DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_actualizacion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `cliente_id`, `trabajador_id`, `servicio_id`, `fecha`, `hora_inicio`, `hora_fin`, `estado`, `observaciones`, `fecha_creacion`, `fecha_actualizacion`, `usuario_actualizacion_id`) VALUES
(1, 4, 2, 1, '2025-06-20', '10:00:00', '10:40:00', 'cancelada', NULL, '2025-06-17 01:54:06', '2025-06-18 00:22:29', NULL),
(4, 5, 3, 3, '2025-06-27', '00:58:00', '01:58:00', 'pendiente', NULL, '2025-06-17 12:53:48', '2025-06-17 12:53:48', NULL),
(6, 6, 2, 3, '2025-06-30', '15:45:00', '16:45:00', 'pendiente', NULL, '2025-06-17 14:39:38', '2025-06-17 14:39:38', NULL),
(7, 5, 3, 2, '2025-06-17', '10:46:00', '11:06:00', 'confirmada', NULL, '2025-06-17 15:46:37', '2025-06-18 01:17:06', NULL),
(8, 5, 2, 1, '2025-06-19', '09:36:00', '10:16:00', 'pendiente', NULL, '2025-06-17 20:30:34', '2025-06-17 20:30:34', NULL),
(9, 4, 3, 2, '2025-06-18', '20:00:00', '21:13:00', 'pendiente', NULL, '2025-06-17 20:48:39', '2025-06-17 20:50:56', NULL);

--
-- Disparadores `citas`
--
DELIMITER $$
CREATE TRIGGER `calcular_hora_fin_cita` BEFORE INSERT ON `citas` FOR EACH ROW BEGIN
    DECLARE duracion INT;
    
    SELECT duracion_minutos INTO duracion
    FROM servicios
    WHERE id = NEW.servicio_id;
    
    IF NEW.hora_fin IS NULL OR NEW.hora_fin = '00:00:00' THEN
        SET NEW.hora_fin = ADDTIME(NEW.hora_inicio, SEC_TO_TIME(duracion * 60));
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `registrar_cambio_estado_cita` AFTER UPDATE ON `citas` FOR EACH ROW BEGIN
    IF OLD.estado != NEW.estado THEN
        INSERT INTO historial_citas (cita_id, estado_anterior, estado_nuevo, usuario_id, observaciones)
        VALUES (NEW.id, OLD.estado, NEW.estado, NEW.usuario_actualizacion_id, 
                CONCAT('Cambio de estado: ', OLD.estado, ' -> ', NEW.estado));
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validar_disponibilidad_trabajador` BEFORE INSERT ON `citas` FOR EACH ROW BEGIN
    DECLARE conflicto INT DEFAULT 0;
    DECLARE tiene_servicio INT DEFAULT 0;
    
    -- Verificar que el trabajador puede realizar el servicio
    SELECT COUNT(*) INTO tiene_servicio
    FROM trabajador_servicios ts
    WHERE ts.trabajador_id = NEW.trabajador_id 
    AND ts.servicio_id = NEW.servicio_id 
    AND ts.activo = 1;
    
    IF tiene_servicio = 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'El trabajador no está autorizado para realizar este servicio';
    END IF;
    
    -- Verificar conflictos de horario básicos
    SELECT COUNT(*) INTO conflicto
    FROM citas c
    WHERE c.trabajador_id = NEW.trabajador_id
    AND c.fecha = NEW.fecha
    AND c.estado IN ('pendiente', 'confirmada', 'en_proceso')
    AND c.id != COALESCE(NEW.id, 0)
    AND (
        (NEW.hora_inicio < c.hora_fin AND NEW.hora_fin > c.hora_inicio)
    );
    
    IF conflicto > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'El trabajador ya tiene una cita en ese horario';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_citas`
--

CREATE TABLE `historial_citas` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) NOT NULL,
  `estado_anterior` enum('pendiente','confirmada','en_proceso','completada','cancelada','no_asistio') DEFAULT NULL,
  `estado_nuevo` enum('pendiente','confirmada','en_proceso','completada','cancelada','no_asistio') DEFAULT NULL,
  `fecha_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_citas`
--

INSERT INTO `historial_citas` (`id`, `cita_id`, `estado_anterior`, `estado_nuevo`, `fecha_cambio`, `usuario_id`, `observaciones`) VALUES
(3, 1, 'confirmada', 'cancelada', '2025-06-17 15:35:56', NULL, 'Cambio de estado: confirmada -> cancelada'),
(5, 1, 'cancelada', 'completada', '2025-06-17 23:10:16', NULL, 'Cambio de estado: cancelada -> completada'),
(8, 1, 'completada', 'pendiente', '2025-06-18 00:22:21', NULL, 'Cambio de estado: completada -> pendiente'),
(9, 1, 'pendiente', 'cancelada', '2025-06-18 00:22:29', NULL, 'Cambio de estado: pendiente -> cancelada'),
(10, 7, 'pendiente', 'confirmada', '2025-06-18 01:17:06', NULL, 'Cambio de estado: pendiente -> confirmada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_trabajo`
--

CREATE TABLE `horarios_trabajo` (
  `id` int(11) NOT NULL,
  `trabajador_id` int(11) NOT NULL,
  `dia_semana` enum('lunes','martes','miercoles','jueves','viernes','sabado','domingo') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios_trabajo`
--

INSERT INTO `horarios_trabajo` (`id`, `trabajador_id`, `dia_semana`, `hora_inicio`, `hora_fin`, `activo`) VALUES
(1, 2, 'lunes', '08:00:00', '17:00:00', 1),
(2, 2, 'martes', '08:00:00', '17:00:00', 1),
(3, 2, 'miercoles', '08:00:00', '17:00:00', 1),
(4, 2, 'jueves', '08:00:00', '17:00:00', 1),
(5, 2, 'viernes', '08:00:00', '17:00:00', 1),
(6, 2, 'sabado', '08:00:00', '15:00:00', 1),
(7, 3, 'lunes', '09:00:00', '18:00:00', 1),
(8, 3, 'martes', '09:00:00', '18:00:00', 1),
(9, 3, 'miercoles', '09:00:00', '18:00:00', 1),
(10, 3, 'jueves', '09:00:00', '18:00:00', 1),
(11, 3, 'viernes', '09:00:00', '18:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo` enum('recordatorio','cancelacion','confirmacion','informacion') NOT NULL,
  `leida` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_lectura` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia','otro') DEFAULT 'efectivo',
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp(),
  `penalizacion` decimal(10,2) DEFAULT 0.00,
  `observaciones` text DEFAULT NULL,
  `usuario_registro_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `activo`) VALUES
(1, 'administrador', 'Acceso completo al sistema', 1),
(2, 'trabajador', 'Gestión de citas y servicios asignados', 1),
(3, 'cliente', 'Agendar y gestionar citas propias', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `duracion_minutos` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `duracion_minutos`, `precio`, `activo`, `fecha_creacion`) VALUES
(1, 'Corte de cabello masculino', 'Corte tradicional y moderno para hombres', 40, 18000.00, 1, '2025-06-17 01:54:05'),
(2, 'Arreglo de barba', 'Recorte y perfilado de barba', 20, 10000.00, 1, '2025-06-17 01:54:05'),
(3, 'Corte + Barba', 'Servicio completo de corte y barba', 60, 25000.00, 1, '2025-06-17 01:54:05'),
(4, 'Uñas Acrilicas', 'Montaje de uñas acrilicas ', 50, 35000.00, 1, '2025-06-17 17:54:14'),
(5, 'Uñas tradicional', 'Uñas tradicional', 25, 15000.00, 1, '2025-06-17 17:57:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador_servicios`
--

CREATE TABLE `trabajador_servicios` (
  `trabajador_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabajador_servicios`
--

INSERT INTO `trabajador_servicios` (`trabajador_id`, `servicio_id`, `activo`, `fecha_asignacion`) VALUES
(2, 1, 1, '2025-06-17 01:54:06'),
(2, 3, 1, '2025-06-17 01:54:06'),
(3, 2, 1, '2025-06-17 01:54:06'),
(3, 3, 1, '2025-06-17 01:54:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `especialidad` varchar(100) DEFAULT NULL COMMENT 'Solo para trabajadores',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `telefono`, `password`, `role_id`, `activo`, `especialidad`, `fecha_registro`, `ultimo_acceso`) VALUES
(1, 'Admin', 'Sistema', 'admin@gretasoft.com', '3001234567', '$2y$10$G3XH4WwArxyGYao.6gzl3usmy.1cavwV.9He9rBT2e6QgkPQkhokC', 1, 1, NULL, '2025-06-17 01:54:05', NULL),
(2, 'Juan', 'Puentes', 'juan.puentes@gretasoft.com', '3012345678', '$2y$10$Eqy0NhPO0kl1DI/M9GCdRunKs9xIrblQ.Ac79cX9tkbiimax4CuA6', 2, 1, 'Corte de cabello masculino', '2025-06-17 01:54:05', NULL),
(3, 'Juan', 'Wilches', 'juan.wilches@gretasoft.com', '3023456789', '$2y$10$TNoegy3oirsGV4.2x/T4GuVCMgAmc4wlweYgnMC2NuMrAE/p1lOVW', 2, 1, 'Arreglo de barba', '2025-06-17 01:54:05', NULL),
(4, 'Alejandro', 'Vergara', 'alejandro.vergara@gmail.com', '3213213232', '$2y$10$.P11YF0spTIZ89Eh7KWI9OaUOa.P4mGFO9WpP3CV5l0D.TZ/6Mpma', 3, 1, NULL, '2025-06-17 01:54:05', NULL),
(5, 'Cristian', 'Cruz', 'cristian.cruz@gmail.com', '3145678901', '$2y$10$SAYMUZhswrgSWyKF2IUTdO8/ZjYkrUsDRVZl0bzdKXBdEYrE6VGz2', 3, 1, NULL, '2025-06-17 01:54:05', NULL),
(6, 'Maria', 'Sanchez', 'maria@gretasoft.com', NULL, '$2y$10$j6kcZaFA2c7rAMuzR5MIHONEf9LShpGk0o5YSZgLs7NK2m6to8qGq', 3, 1, NULL, '2025-06-17 11:27:45', NULL),
(7, 'Adriana', 'Paez', 'apaez@gretasoft.com', NULL, '$2y$10$1/wfp2ZbZyV3hB7WcfMuD.FvbtKVLGchNH54me1B/UwabMN462NlC', 2, 1, NULL, '2025-06-18 02:15:29', NULL);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_citas_completa`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_citas_completa` (
`cita_id` int(11)
,`fecha` date
,`hora_inicio` time
,`hora_fin` time
,`estado` enum('pendiente','confirmada','en_proceso','completada','cancelada','no_asistio')
,`cliente_nombre` varchar(201)
,`cliente_email` varchar(100)
,`cliente_telefono` varchar(15)
,`trabajador_nombre` varchar(201)
,`especialidad` varchar(100)
,`servicio_nombre` varchar(100)
,`duracion_minutos` int(11)
,`precio` decimal(10,2)
,`observaciones` text
,`fecha_creacion` timestamp
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_trabajadores_servicios`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_trabajadores_servicios` (
`trabajador_id` int(11)
,`trabajador_nombre` varchar(201)
,`especialidad` varchar(100)
,`trabajador_activo` tinyint(1)
,`servicio_id` int(11)
,`servicio_nombre` varchar(100)
,`duracion_minutos` int(11)
,`precio` decimal(10,2)
,`asignacion_activa` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_citas_completa`
--
DROP TABLE IF EXISTS `vista_citas_completa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_citas_completa`  AS SELECT `c`.`id` AS `cita_id`, `c`.`fecha` AS `fecha`, `c`.`hora_inicio` AS `hora_inicio`, `c`.`hora_fin` AS `hora_fin`, `c`.`estado` AS `estado`, concat(`uc`.`nombre`,' ',`uc`.`apellido`) AS `cliente_nombre`, `uc`.`email` AS `cliente_email`, `uc`.`telefono` AS `cliente_telefono`, concat(`ut`.`nombre`,' ',`ut`.`apellido`) AS `trabajador_nombre`, `ut`.`especialidad` AS `especialidad`, `s`.`nombre` AS `servicio_nombre`, `s`.`duracion_minutos` AS `duracion_minutos`, `s`.`precio` AS `precio`, `c`.`observaciones` AS `observaciones`, `c`.`fecha_creacion` AS `fecha_creacion` FROM (((`citas` `c` join `usuarios` `uc` on(`c`.`cliente_id` = `uc`.`id`)) join `usuarios` `ut` on(`c`.`trabajador_id` = `ut`.`id`)) join `servicios` `s` on(`c`.`servicio_id` = `s`.`id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_trabajadores_servicios`
--
DROP TABLE IF EXISTS `vista_trabajadores_servicios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_trabajadores_servicios`  AS SELECT `u`.`id` AS `trabajador_id`, concat(`u`.`nombre`,' ',`u`.`apellido`) AS `trabajador_nombre`, `u`.`especialidad` AS `especialidad`, `u`.`activo` AS `trabajador_activo`, `s`.`id` AS `servicio_id`, `s`.`nombre` AS `servicio_nombre`, `s`.`duracion_minutos` AS `duracion_minutos`, `s`.`precio` AS `precio`, `ts`.`activo` AS `asignacion_activa` FROM ((`usuarios` `u` join `trabajador_servicios` `ts` on(`u`.`id` = `ts`.`trabajador_id`)) join `servicios` `s` on(`ts`.`servicio_id` = `s`.`id`)) WHERE `u`.`role_id` = 2 ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cita_trabajador_fecha_hora` (`trabajador_id`,`fecha`,`hora_inicio`),
  ADD KEY `idx_citas_cliente` (`cliente_id`),
  ADD KEY `idx_citas_servicio` (`servicio_id`),
  ADD KEY `idx_citas_fecha` (`fecha`),
  ADD KEY `idx_citas_estado` (`estado`),
  ADD KEY `idx_citas_fecha_estado` (`fecha`,`estado`),
  ADD KEY `fk_citas_usuario_act` (`usuario_actualizacion_id`);

--
-- Indices de la tabla `historial_citas`
--
ALTER TABLE `historial_citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_historial_cita` (`cita_id`),
  ADD KEY `idx_historial_fecha` (`fecha_cambio`),
  ADD KEY `fk_historial_usuario` (`usuario_id`);

--
-- Indices de la tabla `horarios_trabajo`
--
ALTER TABLE `horarios_trabajo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_horario_trabajador_dia` (`trabajador_id`,`dia_semana`),
  ADD KEY `idx_horarios_activo` (`activo`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notif_usuario` (`usuario_id`),
  ADD KEY `idx_notif_leida` (`leida`),
  ADD KEY `idx_notif_tipo` (`tipo`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pagos_cita` (`cita_id`),
  ADD KEY `idx_pagos_fecha` (`fecha_pago`),
  ADD KEY `fk_pagos_usuario` (`usuario_registro_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_roles_nombre` (`nombre`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_servicios_activo` (`activo`);

--
-- Indices de la tabla `trabajador_servicios`
--
ALTER TABLE `trabajador_servicios`
  ADD PRIMARY KEY (`trabajador_id`,`servicio_id`),
  ADD KEY `idx_ts_servicio` (`servicio_id`),
  ADD KEY `idx_ts_activo` (`activo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_usuarios_email` (`email`),
  ADD KEY `idx_usuarios_role` (`role_id`),
  ADD KEY `idx_usuarios_activo` (`activo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `historial_citas`
--
ALTER TABLE `historial_citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `horarios_trabajo`
--
ALTER TABLE `horarios_trabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `fk_citas_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_usuario_act` FOREIGN KEY (`usuario_actualizacion_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_citas`
--
ALTER TABLE `historial_citas`
  ADD CONSTRAINT `fk_historial_cita` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `horarios_trabajo`
--
ALTER TABLE `horarios_trabajo`
  ADD CONSTRAINT `fk_horarios_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `fk_notif_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_cita` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pagos_usuario` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `trabajador_servicios`
--
ALTER TABLE `trabajador_servicios`
  ADD CONSTRAINT `fk_ts_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ts_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
