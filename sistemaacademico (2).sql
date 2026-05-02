-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 02-05-2026 a las 03:39:28
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistemaacademico`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_RegistrarUsuario` (IN `p_nombre` VARCHAR(100), IN `p_carnet` VARCHAR(20), IN `p_fecha_nac` VARCHAR(20), IN `p_correo` VARCHAR(100), IN `p_telefono` VARCHAR(15), IN `p_direccion` VARCHAR(200), IN `p_pass` VARCHAR(255), IN `p_rol` VARCHAR(20))   BEGIN
    INSERT INTO usuario (
        nombre_completo, carnet, fecha_nacimiento, 
        correo_electronico, numero_telefono, direccion, 
        contraseña, rol
    ) VALUES (
        p_nombre, p_carnet, p_fecha_nac, 
        p_correo, p_telefono, p_direccion, 
        SHA2(p_pass, 256), p_rol
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ValidarLogin` (IN `p_correo` VARCHAR(100), IN `p_contrasena` VARCHAR(255))   BEGIN
    -- Se asume que la contraseña se guarda como SHA256 en la base de datos
    -- según el ejemplo de 'admin123' que pusiste en el volcado.
    SELECT * FROM usuario 
    WHERE correo_electronico = p_correo 
    AND contraseña = SHA2(p_contrasena, 256);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_administrador` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_administrador`, `id_usuario`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_asistencia` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('Presente','Ausente','Licencia','Retraso') NOT NULL DEFAULT 'Presente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id_asistencia`, `id_materia`, `id_estudiante`, `fecha`, `estado`) VALUES
(1, 8, 5, '2026-05-02', 'Presente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `id_carrera` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('Activa','Inactiva') DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`id_carrera`, `nombre`, `descripcion`, `estado`) VALUES
(1, 'Ingeniería de Sistemas', 'Carrera orientada al desarrollo de software y tecnología', 'Activa'),
(2, 'Administración de Empresas', 'Gestión empresarial y liderazgo organizacional', 'Activa'),
(3, 'Contaduría Pública', 'Ciencias contables y financieras', 'Activa'),
(4, 'Derecho', 'Ciencias jurídicas y legales', 'Activa'),
(5, 'Medicina', 'Ciencias de la salud', 'Activa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `id_docente` int(11) NOT NULL,
  `nivel_educacion` varchar(100) DEFAULT NULL,
  `experiencia_ensenanza` int(11) DEFAULT NULL,
  `horarios_disponibilidad` varchar(200) DEFAULT NULL,
  `correo_institucional` varchar(100) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`id_docente`, `nivel_educacion`, `experiencia_ensenanza`, `horarios_disponibilidad`, `correo_institucional`, `id_usuario`) VALUES
(1, NULL, NULL, NULL, 'juan.perez@escuela.edu', 2),
(3, NULL, NULL, NULL, 'mauricio@edu.bo', 6),
(4, NULL, NULL, NULL, 'docente@universidad.edu', 10),
(5, NULL, NULL, NULL, 'jhonatan@gmail.com', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrega`
--

CREATE TABLE `entrega` (
  `id_entrega` int(11) NOT NULL,
  `id_tarea` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `calificacion` decimal(5,2) DEFAULT NULL,
  `retroalimentacion` text DEFAULT NULL,
  `fecha_calificacion` datetime DEFAULT NULL,
  `estado` enum('Entregado','Calificado') DEFAULT 'Entregado',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrega`
--

INSERT INTO `entrega` (`id_entrega`, `id_tarea`, `id_estudiante`, `archivo`, `fecha_entrega`, `calificacion`, `retroalimentacion`, `fecha_calificacion`, `estado`, `observaciones`) VALUES
(1, 2, 1, 'uploads/tareas/67eda6374597e_2d8ae6f3bed5e571.docx', '2025-04-02 17:03:51', 94.92, 'bien', '2025-04-03 17:36:33', 'Entregado', NULL),
(2, 3, 1, 'uploads/tareas/67eddcb3274fe_d608a4eb8d1772a8.pdf', '2025-04-02 20:56:19', NULL, NULL, NULL, 'Entregado', NULL),
(4, 4, 4, 'uploads/tareas/69ddbd4dd1e94_c92ac4d060d157bb.jpeg', '2026-04-14 00:06:37', NULL, NULL, NULL, 'Entregado', NULL),
(5, 5, 5, 'uploads/tareas/69f54449c674c_667d042d7db29897.pdf', '2026-05-01 20:24:41', 100.00, 'buen trabajo', '2026-05-01 20:32:35', 'Entregado', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id_estudiante` int(11) NOT NULL,
  `correo_institucional` varchar(100) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_carrera` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id_estudiante`, `correo_institucional`, `id_usuario`, `id_carrera`) VALUES
(1, 'ana.garcia@escuela.edu', 3, NULL),
(2, 'pablo@universidad.edu', 5, NULL),
(3, 'estudiante@universidad.edu', 8, NULL),
(4, 'adrian@gmail.com', 12, NULL),
(5, 'adrianxz@gmail.com', 15, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluacion_docente`
--

CREATE TABLE `evaluacion_docente` (
  `id_evaluacion` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_docente` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL CHECK (`puntuacion` >= 1 and `puntuacion` <= 5),
  `comentario` text DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evaluacion_docente`
--

INSERT INTO `evaluacion_docente` (`id_evaluacion`, `id_estudiante`, `id_docente`, `id_materia`, `puntuacion`, `comentario`, `fecha`) VALUES
(1, 5, 5, 8, 5, '¿Cómo califica la metodología del docente? son dinamicas\n¿La comunicación con los estudiantes fue buena? aclara bien \nComentarios adicionales: ', '2026-05-01 20:25:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `id_inscripcion` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `fecha_inscripcion` date DEFAULT curdate(),
  `estado` enum('Activa','Finalizada') DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripcion`
--

INSERT INTO `inscripcion` (`id_inscripcion`, `id_estudiante`, `id_materia`, `fecha_inscripcion`, `estado`) VALUES
(1, 1, 2, '2025-04-02', 'Activa'),
(2, 1, 4, '2025-04-02', 'Activa'),
(3, 1, 5, '2025-04-02', 'Activa'),
(4, 4, 6, '2026-04-14', 'Activa'),
(5, 1, 7, '2026-04-29', 'Activa'),
(6, 5, 8, '2026-05-01', 'Activa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materia`
--

CREATE TABLE `materia` (
  `id_materia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_docente` int(11) NOT NULL,
  `estado` enum('Activa','Inactiva') DEFAULT 'Activa',
  `id_periodo` int(11) DEFAULT NULL,
  `id_carrera` int(11) DEFAULT NULL,
  `grupo` varchar(10) DEFAULT 'A',
  `turno` enum('Mañana','Medio Día','Tarde') DEFAULT 'Mañana',
  `nivel_semestre` enum('1','2','3','4','5','6','7','8','9') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materia`
--

INSERT INTO `materia` (`id_materia`, `nombre`, `descripcion`, `id_docente`, `estado`, `id_periodo`, `id_carrera`, `grupo`, `turno`, `nivel_semestre`) VALUES
(2, 'Tegnicas de investigación', 'Jjajaja', 1, 'Activa', NULL, NULL, 'A', 'Mañana', '1'),
(4, 'Calculo I', 'Calculo I', 1, 'Activa', NULL, NULL, 'A', 'Mañana', '1'),
(5, 'Diseño Web3', 'zccac', 4, 'Activa', NULL, NULL, 'A', 'Mañana', '1'),
(6, 'pruebaa', 'aasad', 5, 'Activa', NULL, NULL, 'A', 'Mañana', '1'),
(7, 'aplicaciones moviles', 'crear tu app', 5, 'Activa', NULL, NULL, 'A', 'Mañana', '1'),
(8, 'base de datos 1 ', 'aprender consulta el sqlserver', 5, 'Activa', 4, 1, 'A', 'Mañana', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material`
--

CREATE TABLE `material` (
  `id_material` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('Documento','Video','Enlace') NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `fecha_publicacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `material`
--

INSERT INTO `material` (`id_material`, `id_materia`, `titulo`, `descripcion`, `tipo`, `ruta`, `fecha_publicacion`) VALUES
(1, 2, 'prueba de ia', 'aprende lo siguiente', 'Enlace', 'https://openai.com/index/chatgpt/', '2025-04-02 17:02:02'),
(2, 2, 'fdffg', 'fdg', 'Documento', '67ee025877663.docx', '2025-04-02 23:36:56'),
(3, 8, 'practico1', NULL, 'Documento', '69f53f1220551.docx', '2026-05-01 20:02:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id_mensaje` int(11) NOT NULL,
  `remitente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `asunto` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0,
  `estado` enum('Activo','Archivado','Eliminado') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo` enum('primary','success','info','warning','danger') DEFAULT 'info',
  `icono` varchar(50) DEFAULT 'fa-info-circle',
  `leida` tinyint(1) DEFAULT 0,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `usuario_id`, `titulo`, `mensaje`, `tipo`, `icono`, `leida`, `fecha`) VALUES
(1, NULL, 'Usuario actualizado', 'Se han actualizado los datos del usuario: Mauricio1', 'info', 'fa-user-edit', 1, '2025-04-02 20:11:03'),
(2, NULL, 'Nuevo usuario registrado', 'Se ha registrado un nuevo usuario: Jose Carpa como Estudiante', 'success', 'fa-user-plus', 1, '2025-04-02 20:11:56'),
(3, NULL, 'Nuevo usuario registrado', 'Se ha registrado un nuevo usuario: Daniel Roca como Docente', 'success', 'fa-user-plus', 1, '2025-04-02 20:26:16'),
(4, NULL, 'Nueva materia creada', 'Se ha creado la materia Diseño Web3 asignada al docente ', 'success', 'fa-book', 1, '2025-04-02 20:28:39'),
(5, NULL, 'Nueva tarea creada', 'El docente  ha creado una nueva tarea en Diseño Web3', 'info', 'fa-tasks', 0, '2025-04-02 20:44:47'),
(6, NULL, 'Usuario eliminado', 'Se ha eliminado el usuario: Mauricio1', 'danger', 'fa-user-times', 0, '2025-04-02 22:10:30'),
(7, NULL, 'Usuario actualizado', 'Se han actualizado los datos del usuario: Estudiante Ana García', 'info', 'fa-user-edit', 0, '2025-04-02 22:10:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo_academico`
--

CREATE TABLE `periodo_academico` (
  `id_periodo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `periodo_academico`
--

INSERT INTO `periodo_academico` (`id_periodo`, `nombre`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(1, '2025-I', '2025-01-15', '2025-06-30', 'Inactivo'),
(2, '2025-II', '2025-07-15', '2025-12-15', 'Activo'),
(3, '2026-I', '2026-01-15', '2026-06-30', 'Activo'),
(4, 'semestre1 ', '2026-05-01', '2026-05-31', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea`
--

CREATE TABLE `tarea` (
  `id_tarea` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_entrega` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarea`
--

INSERT INTO `tarea` (`id_tarea`, `id_materia`, `titulo`, `descripcion`, `fecha_creacion`, `fecha_entrega`) VALUES
(2, 2, 'actividad1', 'sdad', '2025-04-02 17:02:47', '2025-04-03 17:02:00'),
(3, 5, 'Actividad1', 'adsasda', '2025-04-02 20:44:47', '2025-04-11 12:22:00'),
(4, 6, 'pruebadsad', 'asdsad', '2026-04-14 00:05:41', '2026-04-15 00:05:00'),
(5, 8, 'practico1', 'crea una base de datos', '2026-05-01 20:03:40', '2026-05-04 23:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `carnet` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `numero_telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('Estudiante','Docente','Administrador') NOT NULL,
  `foto_perfil` varchar(255) DEFAULT 'assets/img/perfil1.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_completo`, `carnet`, `fecha_nacimiento`, `correo_electronico`, `numero_telefono`, `direccion`, `contraseña`, `rol`, `foto_perfil`) VALUES
(1, 'Admin Principal', 'ADM001', '1980-01-01', 'admin@escuela.edu', '123456789', 'Dirección admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'Administrador', 'assets/img/usuarios/perfil_1_1743646213.jpg'),
(2, 'Profesor Juan Pérez', 'DOC001', '1975-05-15', 'juan.perez@escuela.edu', '87654321', 'Dirección docente', '15e2b0d3c33891ebb0f1ef609ec419420c20e320ce94c65fbc8c3312448eb225', 'Docente', 'assets/img/usuarios/perfil_2_1743623418.png'),
(3, 'Estudiante Ana García', 'EST0012', '2000-08-20', 'ana.garcia@escuela.edu', '11223345', 'Dirección estudiante', '15e2b0d3c33891ebb0f1ef609ec419420c20e320ce94c65fbc8c3312448eb225', 'Estudiante', 'assets/img/usuarios/perfil_3_1743623338.jpg'),
(5, 'Pablo Gutierrez', '12889986', '2025-04-16', 'pablo@escuela.edu', '67701621', 'Mutualista', '15e2b0d3c33891ebb0f1ef609ec419420c20e320ce94c65fbc8c3312448eb225', 'Docente', 'assets/img/perfil1.jpg'),
(8, 'Jose Carpa', '87654321', '2025-04-01', 'estudiante@universidad.edu', '12345678', 'asaaada', '15e2b0d3c33891ebb0f1ef609ec419420c20e320ce94c65fbc8c3312448eb225', 'Estudiante', 'assets/img/perfil1.jpg'),
(10, 'Daniel Roca', '12345678', '2025-04-10', 'docente@universidad.edu', '12345678', '1wsaaac', '15e2b0d3c33891ebb0f1ef609ec419420c20e320ce94c65fbc8c3312448eb225', 'Docente', 'assets/img/perfil1.jpg'),
(11, 'jhonatan', '15956121', '1999-06-28', 'jhonatan@gmail.com', '645121214', 'en su casa', '15e2b0d3c33891ebb0f1ef609ec419420c20e320ce94c65fbc8c3312448eb225', 'Docente', 'assets/img/usuarios/perfil_11_1776136523.png'),
(12, 'adrian', '512145456', '1899-04-14', 'adrian@gmail.com', '68451212', 'en su casa', '15e2b0d3c33891ebb0f1ef609ec419420c20e320ce94c65fbc8c3312448eb225', 'Estudiante', 'assets/img/perfil1.jpg'),
(15, 'adrian', '1145121', '2000-05-01', 'adrianxz@gmail.com', '68152212', 'recreo', '15e2b0d3c33891ebb0f1ef609ec419420c20e320ce94c65fbc8c3312448eb225', 'Estudiante', 'assets/img/perfil1.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_administrador`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `fk_asistencia_materia` (`id_materia`),
  ADD KEY `fk_asistencia_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`id_carrera`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`id_docente`),
  ADD UNIQUE KEY `correo_institucional` (`correo_institucional`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `entrega`
--
ALTER TABLE `entrega`
  ADD PRIMARY KEY (`id_entrega`),
  ADD UNIQUE KEY `id_tarea` (`id_tarea`,`id_estudiante`),
  ADD KEY `id_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `correo_institucional` (`correo_institucional`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_estudiante_carrera` (`id_carrera`);

--
-- Indices de la tabla `evaluacion_docente`
--
ALTER TABLE `evaluacion_docente`
  ADD PRIMARY KEY (`id_evaluacion`),
  ADD KEY `fk_eval_estudiante` (`id_estudiante`),
  ADD KEY `fk_eval_docente` (`id_docente`),
  ADD KEY `fk_eval_materia` (`id_materia`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD UNIQUE KEY `id_estudiante` (`id_estudiante`,`id_materia`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`id_materia`),
  ADD KEY `id_docente` (`id_docente`),
  ADD KEY `fk_materia_periodo` (`id_periodo`),
  ADD KEY `fk_materia_carrera` (`id_carrera`);

--
-- Indices de la tabla `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id_material`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `fk_mensaje_remitente` (`remitente_id`),
  ADD KEY `fk_mensaje_destinatario` (`destinatario_id`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `periodo_academico`
--
ALTER TABLE `periodo_academico`
  ADD PRIMARY KEY (`id_periodo`);

--
-- Indices de la tabla `tarea`
--
ALTER TABLE `tarea`
  ADD PRIMARY KEY (`id_tarea`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD UNIQUE KEY `carnet` (`carnet`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `carrera`
--
ALTER TABLE `carrera`
  MODIFY `id_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `entrega`
--
ALTER TABLE `entrega`
  MODIFY `id_entrega` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `evaluacion_docente`
--
ALTER TABLE `evaluacion_docente`
  MODIFY `id_evaluacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `materia`
--
ALTER TABLE `materia`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `material`
--
ALTER TABLE `material`
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `periodo_academico`
--
ALTER TABLE `periodo_academico`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tarea`
--
ALTER TABLE `tarea`
  MODIFY `id_tarea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `fk_asistencia_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_asistencia_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `fk_estudiante_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`) ON DELETE SET NULL;

--
-- Filtros para la tabla `evaluacion_docente`
--
ALTER TABLE `evaluacion_docente`
  ADD CONSTRAINT `fk_eval_docente` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id_docente`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_eval_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_eval_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE;

--
-- Filtros para la tabla `materia`
--
ALTER TABLE `materia`
  ADD CONSTRAINT `fk_materia_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_materia_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `periodo_academico` (`id_periodo`) ON DELETE SET NULL;

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `fk_mensaje_destinatario` FOREIGN KEY (`destinatario_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_mensaje_remitente` FOREIGN KEY (`remitente_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
