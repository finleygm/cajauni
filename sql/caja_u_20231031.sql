-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 31-10-2023 a las 23:01:31
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero_cuenta` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_cuenta` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `fecha_pago` date DEFAULT NULL,
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
  `justificacion` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubro`
--

CREATE TABLE `rubro` (
  `id` int(11) NOT NULL,
  `numero_identificador` int(11) DEFAULT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad`
--

CREATE TABLE `unidad` (
  `id` int(11) NOT NULL,
  `numero_unidad` int(255) DEFAULT NULL,
  `descripcion` text,
  `tipo_unidad` varchar(50) NOT NULL COMMENT '1.- Administracion\r\n2.- Modulo\r\n3.- Carreraa\r\n4.- Sub Modulo\r\n\r\n',
  `unidad_id` int(11) NOT NULL COMMENT 'unidad padre \r\n'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(2, 'Ericka Barrientos', 'erikabr20@hotmail.com', NULL, '$2y$10$n2nU0blbDCZ943il22Tklude8FoauBWw7S9/J1gWrQ8EwS1H0Oiw.', NULL, '2021-05-05 19:35:34', '2023-10-09 19:53:07', 'Femenino', 'Usuario', 1),
(3, 'ROY DANY', 'roydanyurzagaste@gmail.com', NULL, '$2y$10$NVILQK6xhfY71dbJsGmT1eBVesLwwb0IllIL0JhqrYAQPoR4xBUT2', NULL, '2023-08-18 19:25:45', '2023-08-18 19:25:45', 'Masculino', 'Administrador', 2),
(4, 'Roberto Carlos Mattu Guzman', 'robertomatu@gmail.com', NULL, '$2y$10$dzv2Y8398ivjZKyhluhhW.baMONkr.PAJ09gSJxTiVLguO9m8X43u', NULL, '2023-11-01 01:24:06', '2023-11-01 01:24:06', 'Masculino', 'Usuario', 2),
(5, 'Carla Villegas Juchani', 'carlavillegas123@gmail.com', NULL, '$2y$10$jWTAi3DPDLJlviNXNmV06uLymT0WgAXso9stHYXRRvZpXGdYl4/i.', NULL, '2023-11-01 01:36:01', '2023-11-01 01:36:01', 'Femenino', 'Usuario', 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago_detalle`
--
ALTER TABLE `pago_detalle`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productousuario`
--
ALTER TABLE `productousuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rubro`
--
ALTER TABLE `rubro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `unidad`
--
ALTER TABLE `unidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
