-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 26-06-2024 a las 22:35:26
-- Versión del servidor: 5.7.24-log
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `caja_u`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificador_pago`
--

CREATE TABLE `clasificador_pago` (
  `id` int(11) NOT NULL,
  `concepto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `clasificador_pago`
--

INSERT INTO `clasificador_pago` (`id`, `concepto`) VALUES
(1, 'historicos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ci` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_expedido` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombres` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `apellidos` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `ci`, `ci_expedido`, `nombres`, `apellidos`, `created_at`, `updated_at`) VALUES
(1, '11370434', '', 'JOSE VICENTE', 'FRIAS CESPEDES', NULL, NULL),
(2, '13636924', '', 'JUAN GABRIEL', 'YURE RIVERO', NULL, NULL),
(3, '8412755', '', 'RAQUIELA', 'MAYTO BAYA', NULL, NULL),
(4, '9606382', '', 'FABIO', 'AMARAL MONTENEGRO', NULL, NULL),
(5, '10812001', '', 'BERTHA VIVIANA', 'ALIPAZ LURICI', NULL, NULL),
(6, '7779062', '', 'ALDO ANTONIO', 'FERNANDEZ POICHE', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero_cuenta` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_cuenta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `precio_unitario` double DEFAULT NULL,
  `rubro_id` int(11) NOT NULL,
  `unidad_id` int(11) NOT NULL,
  `tipo_cuenta` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1-. Es producto\r\n2-. No es producto',
  `stock` int(11) NOT NULL,
  `unidad` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'descripcion de producto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuenta`
--

INSERT INTO `cuenta` (`id`, `numero_cuenta`, `nombre_cuenta`, `descripcion`, `created_at`, `updated_at`, `precio_unitario`, `rubro_id`, `unidad_id`, `tipo_cuenta`, `stock`, `unidad`) VALUES
(1, '1', 'Diploma Academico', NULL, NULL, NULL, 200, 1, 2, '0', 2, 'Unidad'),
(2, '2', 'Legalizacion de Diploma Académico', NULL, NULL, NULL, 80, 1, 2, '0', 1, 'Unidad'),
(3, '3', 'Certificado de Notas (por semestre)', NULL, NULL, NULL, 5, 1, 2, '2', 6, 'Unidad'),
(4, '4', 'Certificado Global de Notas (Historial Academico)', '', NULL, NULL, 15, 1, 2, '2', 1, 'Unidad'),
(5, '5', 'Carga horaria universitaria (por gestión)', '', NULL, NULL, 20, 1, 2, '2', 0, 'Unidad'),
(6, '6', 'Certificado de equivalencia de notas', '', NULL, NULL, 20, 1, 2, '2', 0, 'Unidad'),
(7, '7', 'Legalizacion Programa Analítico', '', NULL, NULL, 20, 1, 2, '2', 0, 'Unidad'),
(8, '8', 'Certificado de Vencimiento Plan de Estudios', '', NULL, NULL, 20, 1, 2, '2', 0, 'Unidad'),
(9, '9', 'Legalización Plan de Estudios', '', NULL, NULL, 10, 1, 2, '2', 0, 'Unidad'),
(10, '10', 'Legalizacion Certificado Acta de Defensa de Grado', NULL, NULL, NULL, 10, 1, 2, '2', 0, 'Unidad'),
(11, '11', 'Otros documentos universitarios', NULL, NULL, NULL, 20, 1, 2, '2', 0, 'Unidad'),
(12, '12', 'Diploma Academico de Diplomado', NULL, NULL, NULL, 300, 1, 3, '2', 0, 'Unidad'),
(13, '13', 'Diploma Académico de Especialidad', NULL, NULL, NULL, 350, 1, 3, '2', 0, 'Unidad'),
(14, '14', 'Diploma Academico de Maestria', NULL, NULL, NULL, 450, 1, 3, '2', 0, 'Unidad'),
(15, '15', 'Diploma Academico de Doctorado', '', NULL, NULL, 500, 1, 3, '2', 0, 'Unidad'),
(16, '16', 'Diploma Académico de Post Doctorado', '', NULL, NULL, 550, 1, 3, '2', 0, 'Unidad'),
(17, '17', 'Copia legalizada de Diploma Academico de Diplomado', NULL, NULL, NULL, 100, 1, 3, '2', 0, 'Unidad'),
(18, '18', 'Copia legalizada de Diploma Academico de Especialidad', '', NULL, NULL, 100, 1, 3, '2', 0, 'Unidad'),
(19, '19', 'Copia legalizada de Diploma Academico de Maestria', '', NULL, NULL, 150, 1, 3, '2', 0, 'Unidad'),
(20, '20', 'Copia legalizada de Diploma Academico de Doctorado', NULL, NULL, NULL, 200, 1, 3, '2', 0, 'Unidad'),
(21, '21', 'Copia legalizada de Diploma Academico de Post Doctorado', NULL, NULL, NULL, 250, 1, 3, '2', 0, 'Unidad'),
(22, '22', 'Certificado de Calificaciones por Módulo (Diplomado, Especialidad, Maestria)', NULL, NULL, NULL, 20, 1, 3, '2', 0, 'Unidad'),
(23, '23', 'Certificado de Calificaciones por Módulo Doctorado', NULL, NULL, NULL, 30, 1, 3, '2', 0, 'Unidad'),
(24, '24', 'Certificado de Calificaciones por Módulo Post Doctorado', NULL, NULL, NULL, 50, 1, 3, '2', 0, 'Unidad'),
(25, '25', 'Historial Academico Post Gradual', NULL, NULL, NULL, 50, 1, 3, '2', 0, 'Unidad'),
(26, '26', 'Certificado de Acta de Defensa de Post Grado', NULL, NULL, NULL, 50, 1, 3, '2', 0, 'Unidad'),
(27, '27', 'Copia legalizada del programa analítico, Acta de defensa u otro documento de Post Grado', NULL, NULL, NULL, 20, 1, 3, '2', 0, 'Unidad'),
(28, '28', 'Curso Preparatorio (presencial)', '', NULL, NULL, 150, 1, 2, '2', 0, 'Unidad'),
(29, '29', 'Curso Preparatorio (Examen Directo)', '', NULL, NULL, 150, 1, 2, '2', 0, 'Unidad'),
(30, '30', 'Matricula de Inscripción', '', NULL, NULL, 50, 1, 2, '2', 0, 'Unidad'),
(31, '31', 'Multa por inscripción', '', NULL, NULL, 50, 1, 2, '2', 0, 'Unidad'),
(32, '32', 'Curso Intensivo', '', NULL, NULL, 50, 1, 2, '2', 0, 'Unidad'),
(33, '33', 'Servicio de Cisterna', '', NULL, NULL, 0, 2, 2, '2', 0, 'Unidad'),
(34, '34', 'Certificado No Deudor', '', NULL, NULL, 50, 2, 2, '2', 0, 'Unidad'),
(35, '35', 'Certificado de No procesos Judiciales', '', NULL, NULL, 50, 2, 2, '2', 0, 'Unidad'),
(36, '36', 'MULTA POR RETRASO EN LA DEFENSA DE TRABAJO DE GRADO', '', NULL, NULL, 200, 1, 2, '2', 0, 'Unidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_clasificador`
--

CREATE TABLE `cuenta_clasificador` (
  `id` int(11) NOT NULL,
  `numero_clasificador` int(11) DEFAULT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_producto_clasificador`
--

CREATE TABLE `cuenta_producto_clasificador` (
  `id` int(11) NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  `cuenta_clasificador_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2021_04_19_143346_create_cuenta', 1),
(4, '2021_04_19_143755_create_pago', 1),
(5, '2021_04_19_144236_create_cliente', 1),
(6, '2021_04_19_145757_create_pago_detalle', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serie` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `total` double(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lugar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sector` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` int(11) NOT NULL COMMENT '1.- Tesoreria\r\n2.- Comercializacion\r\n',
  `nro_recibo` int(11) NOT NULL,
  `clasificador_pago_id` int(11) NOT NULL,
  `estado_pago` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Estado de boleta\r\n1.- activo\r\n2.- anulado\r\n',
  `justificacion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_anulacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id`, `serie`, `cliente_id`, `fecha_pago`, `total`, `created_at`, `updated_at`, `lugar`, `user_id`, `sector`, `categoria`, `nro_recibo`, `clasificador_pago_id`, `estado_pago`, `justificacion`, `fecha_anulacion`) VALUES
(1, 1, 1, '2024-04-03 00:00:00', 240.00, NULL, NULL, 'IVO', '2', 'Interna', 1, 0, 0, 'Activo', '', NULL),
(2, 2, 2, '2024-04-03 00:00:00', 125.00, NULL, NULL, 'IVO', '2', 'Interna', 1, 0, 0, 'Activo', '', NULL),
(3, 3, 3, '2024-04-03 00:00:00', 200.00, NULL, NULL, 'IVO', '2', 'Interna', 1, 0, 0, 'Activo', '', NULL),
(4, 4, 1, '2024-04-03 00:00:00', 50.00, NULL, NULL, 'IVO', '2', 'Interna', 1, 0, 0, 'Activo', '', NULL),
(5, 5, 4, '2024-04-03 00:00:00', 230.00, NULL, NULL, 'IVO', '2', 'Interna', 1, 0, 0, 'Activo', '', NULL),
(6, 6, 5, '2024-04-03 00:00:00', 265.00, NULL, NULL, 'IVO', '2', 'Interna', 1, 0, 0, 'Activo', '', NULL),
(7, 7, 2, '2024-04-03 00:00:00', 125.00, NULL, NULL, 'IVO', '2', 'Interna', 1, 0, 0, 'Anulado', 'xxxx', '2024-04-24 11:45:58'),
(8, 8, 6, '2024-04-03 00:00:00', 230.00, NULL, NULL, 'IVO', '2', 'Interna', 1, 0, 0, 'Anulado', 'por error', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_detalle`
--

CREATE TABLE `pago_detalle` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `posicion` int(11) DEFAULT NULL,
  `monto` double(8,2) NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `pago_id` int(11) NOT NULL,
  `precio_unitario` double(8,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cuenta_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pago_detalle`
--

INSERT INTO `pago_detalle` (`id`, `posicion`, `monto`, `descripcion`, `pago_id`, `precio_unitario`, `cantidad`, `created_at`, `updated_at`, `cuenta_id`) VALUES
(1, NULL, 200.00, 'Diploma Academico', 1, 200.00, 1, NULL, NULL, 1),
(2, NULL, 15.00, 'Certificado Global de Notas (Historial Academico)', 1, 15.00, 1, NULL, NULL, 4),
(3, NULL, 25.00, 'Certificado de Notas (por semestre)', 1, 5.00, 5, NULL, NULL, 3),
(4, NULL, 80.00, 'Legalizacion de Diploma Académico', 2, 80.00, 1, NULL, NULL, 2),
(5, NULL, 15.00, 'Certificado Global de Notas (Historial Academico)', 2, 15.00, 1, NULL, NULL, 4),
(6, NULL, 30.00, 'Certificado de Notas (por semestre)', 2, 5.00, 6, NULL, NULL, 3),
(7, NULL, 200.00, 'MULTA POR RETRASO EN LA DEFENSA DE TRABAJO DE GRADO', 3, 200.00, 1, NULL, NULL, 36),
(8, NULL, 50.00, 'Certificado No Deudor', 4, 50.00, 1, NULL, NULL, 34),
(9, NULL, 200.00, 'Diploma Academico', 5, 200.00, 1, NULL, NULL, 1),
(10, NULL, 15.00, 'Certificado Global de Notas (Historial Academico)', 5, 15.00, 1, NULL, NULL, 4),
(11, NULL, 15.00, 'Certificado de Notas (por semestre)', 5, 5.00, 3, NULL, NULL, 3),
(12, NULL, 200.00, 'Diploma Academico', 6, 200.00, 1, NULL, NULL, 1),
(13, NULL, 15.00, 'Certificado Global de Notas (Historial Academico)', 6, 15.00, 1, NULL, NULL, 4),
(14, NULL, 50.00, 'Certificado de Notas (por semestre)', 6, 5.00, 10, NULL, NULL, 3),
(15, NULL, 80.00, 'Legalizacion de Diploma Académico', 7, 80.00, 1, NULL, NULL, 2),
(16, NULL, 15.00, 'Certificado Global de Notas (Historial Academico)', 7, 15.00, 1, NULL, NULL, 4),
(17, NULL, 30.00, 'Certificado de Notas (por semestre)', 7, 5.00, 6, NULL, NULL, 3),
(18, NULL, 200.00, 'Diploma Academico', 8, 200.00, 1, NULL, NULL, 1),
(19, NULL, 15.00, 'Certificado de Notas (por semestre)', 8, 5.00, 3, NULL, NULL, 3),
(20, NULL, 15.00, 'Certificado Global de Notas (Historial Academico)', 8, 15.00, 1, NULL, NULL, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productousuario`
--

CREATE TABLE `productousuario` (
  `id` int(11) NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productousuario`
--

INSERT INTO `productousuario` (`id`, `cuenta_id`, `user_id`) VALUES
(1, 1, 2),
(2, 2, 2),
(3, 3, 2),
(4, 4, 2),
(5, 5, 2),
(9, 12, 3),
(10, 14, 3),
(13, 36, 2),
(14, 34, 2),
(15, 33, 2),
(16, 32, 2),
(17, 31, 2),
(18, 30, 2),
(19, 29, 2),
(20, 28, 2),
(21, 26, 2),
(22, 25, 2),
(23, 24, 2),
(24, 22, 2),
(25, 21, 2),
(26, 20, 2),
(27, 18, 2),
(28, 17, 2),
(29, 16, 2),
(30, 15, 2),
(31, 14, 2),
(32, 13, 2),
(33, 12, 2),
(34, 11, 2),
(35, 10, 2),
(36, 9, 2),
(37, 8, 2),
(38, 6, 2),
(39, 5, 2),
(45, 3, 3),
(46, 5, 3),
(59, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubro`
--

CREATE TABLE `rubro` (
  `id` int(11) NOT NULL,
  `numero_identificador` int(11) DEFAULT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rubro`
--

INSERT INTO `rubro` (`id`, `numero_identificador`, `descripcion`) VALUES
(1, 15200, 'Derechos'),
(2, 12200, 'Venta de servicios de la Administración Pública'),
(3, 15990, 'Otros Ingresos No Específicos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad`
--

CREATE TABLE `unidad` (
  `id` int(11) NOT NULL,
  `numero_unidad` int(255) DEFAULT NULL,
  `descripcion` text,
  `tipo_unidad` varchar(50) NOT NULL COMMENT '1.- Administracion\r\n2.- Modulo\r\n3.- Carreraa\r\n4.- Sub Modulo\r\n\r\n',
  `unidad_id` int(11) DEFAULT NULL COMMENT 'unidad padre \r\n'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `unidad`
--

INSERT INTO `unidad` (`id`, `numero_unidad`, `descripcion`, `tipo_unidad`, `unidad_id`) VALUES
(1, 1, 'Unibol', 'Administracion', NULL),
(2, 2, 'Administracion Central', 'Administracion', 1),
(3, 3, 'Postgrado', 'Administracion', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sexo` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cargo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` int(11) NOT NULL COMMENT '1.- Tesoreria\r\n2.- Comercializacion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `sexo`, `cargo`, `categoria`) VALUES
(1, 'FINLEY GUERRA MENDOZA', 'finley_1231@hotmail.com', NULL, '$2y$10$M15Z8PeIV.CgwFRWSz3no.Yxc3WdMNt8qUM6Y6gMyoUraOUNCHmfa', NULL, '2021-04-19 21:33:53', '2023-10-09 19:54:49', 'Masculino', 'Usuario', 1),
(2, 'Erika Barrientos', 'erikabr20@hotmail.com', NULL, '$2y$10$n2nU0blbDCZ943il22Tklude8FoauBWw7S9/J1gWrQ8EwS1H0Oiw.', NULL, '2021-05-05 19:35:34', '2024-04-24 19:47:15', 'Femenino', 'Administrador', 1),
(3, 'ROY DANY', 'roydanyurzagaste@gmail.com', NULL, '$2y$10$9mEeyBpgVvoW7Q.uu6g4h.3ed/aTblSwK28.XN71vhGUZJq0LYrlC', NULL, '2023-08-18 19:25:45', '2024-04-19 19:53:31', 'Masculino', 'Administrador', 2),
(4, 'Roberto Carlos Mattu Guzman', 'robertomatu@gmail.com', NULL, '$2y$10$dzv2Y8398ivjZKyhluhhW.baMONkr.PAJ09gSJxTiVLguO9m8X43u', NULL, '2023-11-01 01:24:06', '2023-11-01 01:24:06', 'Masculino', 'Usuario', 2),
(5, 'Carla Villegas Juchani', 'carlavillegas123@gmail.com', NULL, '$2y$10$jWTAi3DPDLJlviNXNmV06uLymT0WgAXso9stHYXRRvZpXGdYl4/i.', NULL, '2023-11-01 01:36:01', '2023-11-01 01:36:01', 'Femenino', 'Usuario', 1),
(6, 'Yudit Carmelo Manuel', 'yuditusielacarmelo@gmail.com', NULL, '$2y$10$rQskmGS6I2oV7qNUOMSFsO0qH2t9ACZEgMlNwEL6vW42/MXr5fITO', NULL, '2024-05-06 21:51:39', '2024-05-06 21:51:39', 'Femenino', 'Usuario', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clasificador_pago`
--
ALTER TABLE `clasificador_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubro_id` (`rubro_id`),
  ADD KEY `id` (`unidad_id`);

--
-- Indices de la tabla `cuenta_clasificador`
--
ALTER TABLE `cuenta_clasificador`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuenta_producto_clasificador`
--
ALTER TABLE `cuenta_producto_clasificador`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pago_detalle`
--
ALTER TABLE `pago_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `productousuario`
--
ALTER TABLE `productousuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rubro`
--
ALTER TABLE `rubro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `unidad`
--
ALTER TABLE `unidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clasificador_pago`
--
ALTER TABLE `clasificador_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `cuenta_clasificador`
--
ALTER TABLE `cuenta_clasificador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuenta_producto_clasificador`
--
ALTER TABLE `cuenta_producto_clasificador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pago_detalle`
--
ALTER TABLE `pago_detalle`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `productousuario`
--
ALTER TABLE `productousuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `rubro`
--
ALTER TABLE `rubro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `unidad`
--
ALTER TABLE `unidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
