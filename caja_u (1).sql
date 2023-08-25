-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 25-08-2023 a las 23:43:40
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
(1, '6304332', 'SC', 'FINLEY', 'GUERRA MENDOZA', NULL, NULL),
(2, '9743924', 'sc', 'MELISSA', 'AÑEZ AGUILERA', NULL, NULL),
(3, '8135356', 'sc', 'Juan', 'Pachuri Pesoa', NULL, NULL),
(4, '8873173', 'SC', 'GENRRY', 'JUSTINIANO YANDURA', NULL, NULL),
(5, '7573573', 'SC', 'ROY', 'QUISPE TORREZ', NULL, NULL),
(6, '9803178', 'SC', 'DIEGO', 'MOZA VARGAS', NULL, NULL),
(7, '9037181', 'SC', 'JORGE LUIS', 'VARGAS MENDEZ', NULL, NULL),
(8, '11328676', 'SC', 'ERICKA XIMENA', 'LIMACHI CLAURE', NULL, NULL),
(9, '15143488', 'SC', 'SORAYA', 'ORTEGA VIDES', NULL, NULL),
(10, '13887154', 'SC', 'MARIOLY', 'PARIAMO VALDEZ', NULL, NULL),
(11, '10670314', 'SC', 'JULIO ARNALDO', 'CORTEZ', NULL, NULL),
(12, '13168012', 'Sc', 'LUIS DAVID', 'MERCADO GOMEZ', NULL, NULL),
(13, '13887169', 'Sc', 'ZINARA', 'CUIQUI MACUAPA', NULL, NULL),
(14, '6935823', 'Sc', 'ALDAIR', 'CALLE LIMACO', NULL, NULL),
(15, '10670464', 'Sc', 'MARIA SOLEDAD', 'ACOSTA SAAVEDRA', NULL, NULL),
(16, '13670965', 'Sc', 'CARLOS LEONEL', 'VIDAL LOBO', NULL, NULL),
(17, '12787076', 'Tj', 'ROY DANY', 'URZAGASTE HERRERA', NULL, NULL),
(18, '12345678', 'Sc', 'CARLOS', 'PALENQUE', NULL, NULL);

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
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuenta`
--

INSERT INTO `cuenta` (`id`, `numero_cuenta`, `nombre_cuenta`, `descripcion`, `created_at`, `updated_at`, `precio_unitario`, `rubro_id`, `unidad_id`, `tipo_cuenta`, `stock`) VALUES
(1, '12341', 'Pago Historicos', 'Aranceles', NULL, NULL, 15, 0, 0, '', 0),
(2, '123', 'Certificado de Notas', 'Aranceles', NULL, NULL, 50, 0, 0, '', 0),
(4, '4567', 'Diploma Academico', 'Aranceles,  Tiene un costo de 200bs', NULL, NULL, 200, 0, 0, '', 0),
(5, '1234', 'Certificados de Notas  nivel Tecnico Superior', 'Aranceles', NULL, NULL, 30, 0, 0, '', 0),
(6, '234', 'pago de certificados de notas', 'Cuentas', NULL, NULL, 30, 0, 0, '', 0),
(7, '2343', 'Historicos academicos', 'Pago para historicos de notas de postgrado', NULL, NULL, 15, 0, 0, '', 0),
(8, '1', 'DIPLOMA ACADEMICO DE DIPLOMADO', NULL, NULL, NULL, 300, 0, 0, '', 0),
(9, '232', 'Pago de Alevines', NULL, NULL, NULL, 2, 0, 0, '', 0),
(10, '12', 'pollos', 'venta de pollos', NULL, NULL, 20, 3, 6, '1', 100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_clasificador`
--

CREATE TABLE `cuenta_clasificador` (
  `id` int(11) NOT NULL,
  `numero_clasificador` int(11) DEFAULT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cuenta_clasificador`
--

INSERT INTO `cuenta_clasificador` (`id`, `numero_clasificador`, `descripcion`) VALUES
(1, 1, 'Inscripción/Multa'),
(2, 2, 'Aranceles de Postgrado'),
(3, 3, 'Examen de Admision'),
(4, 4, 'Aranceles Universitarios'),
(5, 5, 'Aranceles de Pagos Servicios'),
(6, 8, 'Pago de Pescado'),
(7, NULL, NULL),
(8, NULL, NULL),
(9, NULL, NULL),
(10, NULL, NULL),
(11, NULL, 'a2'),
(12, 204, 'Notas'),
(13, 2041, 'abcde2'),
(15, 1234, 'Producto Avicola2'),
(16, 123456, 'Producto Avicola'),
(17, 12345, 'prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_producto_clasificador`
--

CREATE TABLE `cuenta_producto_clasificador` (
  `id` int(11) NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  `cuenta_clasificador_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cuenta_producto_clasificador`
--

INSERT INTO `cuenta_producto_clasificador` (`id`, `cuenta_id`, `cuenta_clasificador_id`) VALUES
(1, 6, -1),
(2, 2, -1),
(3, 4, -1),
(6, 9, 15),
(7, 6, 16),
(8, 2, 16),
(11, 6, 15),
(14, 2, 12);

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
  `cuenta_clasificador_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id`, `serie`, `cliente_id`, `fecha_pago`, `total`, `created_at`, `updated_at`, `lugar`, `user_id`, `cuenta_clasificador_id`) VALUES
(1, 1, 1, '2021-04-12', 250.00, NULL, NULL, 'IVO', '2', 4),
(2, 2, 2, '2021-04-12', 295.00, NULL, NULL, 'IVO', '2', 4),
(3, 3, 1, '2021-04-12', 230.00, NULL, NULL, 'IVO', '2', 4),
(4, 4, 1, '2021-04-12', 230.00, NULL, NULL, 'IVO', '2', 4),
(5, 5, 1, '2021-04-12', 200.00, NULL, NULL, 'IVO', '2', 4),
(6, 6, 1, '2021-04-12', 200.00, NULL, NULL, 'IVO', '2', 4),
(7, 7, 3, '2021-04-12', 45.00, NULL, NULL, 'IVO', '2', 2),
(8, 8, 3, '2021-04-12', 45.00, NULL, NULL, 'IVO', '2', 2),
(9, 9, 1, '2021-05-10', 230.00, NULL, NULL, 'IVO', '1', 4),
(10, 10, 1, '2021-05-10', 230.00, NULL, NULL, 'IVO', '1', 4),
(11, 11, 1, '2021-05-11', 300.00, NULL, NULL, 'IVO', '2', 2),
(12, 12, 1, '2021-05-17', 200.00, NULL, NULL, 'IVO', '1', 6),
(13, 13, 1, '2021-05-18', 4.00, NULL, NULL, 'IVO', '1', 6),
(14, 14, 10, '2021-05-18', 17.00, NULL, NULL, 'IVO', '1', 2),
(15, 15, 12, '2021-05-18', 200.00, NULL, NULL, 'IVO', '1', 4),
(16, 16, 14, '2021-05-18', 30.00, NULL, NULL, 'IVO', '1', 2),
(17, 17, 15, '2021-05-18', 2.00, NULL, NULL, 'IVO', '1', 6),
(18, 18, 1, '2023-08-11', 50.00, NULL, NULL, 'IVO', '1', 2),
(19, 19, 16, '2023-08-11', 365.00, NULL, NULL, 'IVO', '1', 2),
(20, 20, 1, '2023-08-14', 30.00, NULL, NULL, 'IVO', '1', 2);

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
(1, NULL, 50.00, 'Certificado de Notas', 1, 50.00, 1, NULL, NULL, 2),
(2, NULL, 200.00, 'Diploma Academico', 1, 200.00, 1, NULL, NULL, 4),
(3, NULL, 15.00, 'Pago Historicos', 2, 15.00, 1, NULL, NULL, 1),
(4, NULL, 50.00, 'Certificado de Notas', 2, 50.00, 1, NULL, NULL, 2),
(5, NULL, 30.00, 'Certificados de Notas  nivel Tecnico Superior', 2, 30.00, 1, NULL, NULL, 5),
(6, NULL, 200.00, 'Diploma Academico', 2, 200.00, 1, NULL, NULL, 4),
(7, NULL, 200.00, 'Diploma Academico', 3, 200.00, 1, NULL, NULL, 4),
(8, NULL, 30.00, 'Certificados de Notas  nivel Tecnico Superior', 3, 30.00, 1, NULL, NULL, 5),
(9, NULL, 200.00, 'Diploma Academico', 4, 200.00, 1, NULL, NULL, 4),
(10, NULL, 30.00, 'Certificados de Notas  nivel Tecnico Superior', 4, 30.00, 1, NULL, NULL, 5),
(11, NULL, 200.00, 'Diploma Academico', 5, 200.00, 1, NULL, NULL, 4),
(12, NULL, 200.00, 'Diploma Academico', 6, 200.00, 1, NULL, NULL, 4),
(13, NULL, 30.00, 'pago de certificados de notas', 7, 30.00, 1, NULL, NULL, 6),
(14, NULL, 15.00, 'Historicos academicos', 7, 15.00, 1, NULL, NULL, 7),
(15, NULL, 15.00, 'Historicos academicos', 8, 15.00, 1, NULL, NULL, 7),
(16, NULL, 30.00, 'pago de certificados de notas', 8, 30.00, 1, NULL, NULL, 6),
(17, NULL, 200.00, 'Diploma Academico', 9, 200.00, 1, NULL, NULL, 4),
(18, NULL, 30.00, 'Certificados de Notas  nivel Tecnico Superior', 9, 30.00, 1, NULL, NULL, 5),
(19, NULL, 30.00, 'Certificados de Notas  nivel Tecnico Superior', 10, 30.00, 1, NULL, NULL, 5),
(20, NULL, 200.00, 'Diploma Academico', 10, 200.00, 1, NULL, NULL, 4),
(21, NULL, 300.00, 'DIPLOMA ACADEMICO DE DIPLOMADO', 11, 300.00, 1, NULL, NULL, 8),
(22, NULL, 200.00, 'Pago de Alevines', 12, 2.00, 100, NULL, NULL, 9),
(23, NULL, 2.00, 'Pago de Alevines', 13, 2.00, 1, NULL, NULL, 9),
(24, NULL, 2.00, 'Pago de Alevines', 13, 2.00, 1, NULL, NULL, 9),
(25, NULL, 2.00, 'Pago de Alevines', 14, 2.00, 1, NULL, NULL, 9),
(26, NULL, 15.00, 'Historicos academicos', 14, 15.00, 1, NULL, NULL, 7),
(27, NULL, 200.00, 'Diploma Academico', 15, 200.00, 1, NULL, NULL, 4),
(28, NULL, 30.00, 'pago de certificados de notas', 16, 30.00, 1, NULL, NULL, 6),
(29, NULL, 2.00, 'Pago de Alevines', 17, 2.00, 1, NULL, NULL, 9),
(30, NULL, 50.00, 'Certificado de Notas', 18, 50.00, 1, NULL, NULL, 2),
(31, NULL, 300.00, 'DIPLOMA ACADEMICO DE DIPLOMADO', 19, 300.00, 1, NULL, NULL, 8),
(32, NULL, 50.00, 'Certificado de Notas', 19, 50.00, 1, NULL, NULL, 2),
(33, NULL, 15.00, 'Historicos academicos', 19, 15.00, 1, NULL, NULL, 7),
(34, NULL, 30.00, 'pago de certificados de notas', 20, 30.00, 1, NULL, NULL, 6);

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
(1, 12100, 'Venta de Bienes'),
(2, 12200, 'Venta de Servicios'),
(3, 15200, 'Derechos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad`
--

CREATE TABLE `unidad` (
  `id` int(11) NOT NULL,
  `numero_unidad` int(255) DEFAULT NULL,
  `descripcion` text,
  `tipo_unidad` varchar(50) NOT NULL,
  `unidad_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `unidad`
--

INSERT INTO `unidad` (`id`, `numero_unidad`, `descripcion`, `tipo_unidad`, `unidad_id`) VALUES
(1, 1, 'UNIBOL', 'Administracion', 1),
(2, 2, 'Medicina Veterinaria y Zootecnia', '', 0),
(3, 3, 'Ing. Ecopiscicultura', '', 0),
(4, 4, 'Vice Rectorado', '', 0),
(5, 6, 'Contabilidad', '', 0),
(6, 7, 'Almacenes', '', 0),
(7, 8, 'Biblioteca', '', 0),
(8, 9, 'Modulos Ecopiscicultura', '', 0);

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
(1, 'FINLEY GUERRA', 'finley_1231@hotmail.com', NULL, '$2y$10$M15Z8PeIV.CgwFRWSz3no.Yxc3WdMNt8qUM6Y6gMyoUraOUNCHmfa', NULL, '2021-04-19 21:33:53', '2021-04-19 21:33:53', 'Masculino', 'Administrador', 1),
(2, 'Ericka Barrientos', 'erikabr20@hotmail.com', NULL, '$2y$10$n2nU0blbDCZ943il22Tklude8FoauBWw7S9/J1gWrQ8EwS1H0Oiw.', NULL, '2021-05-05 19:35:34', '2021-05-05 19:35:34', 'FEMENINO', 'administrador', 1),
(3, 'Carla Villegas', 'carlavillegas123@gmail.com', NULL, '$2y$10$QZoIme1z0sCwsUyd.ePmuOZDE3P3vX7tx95.SGGD4ImkAQDQpL/Eq', NULL, '2023-08-16 01:32:20', '2023-08-16 01:32:20', 'Femenino', 'usuario', 0),
(5, 'ROY DANY', 'roydanyurzagaste@gmail.com', NULL, '$2y$10$NVILQK6xhfY71dbJsGmT1eBVesLwwb0IllIL0JhqrYAQPoR4xBUT2', NULL, '2023-08-18 19:25:45', '2023-08-18 19:25:45', 'Masculino', 'Administrador', 2),
(6, 'Yovana Nina Perez', 'yovananina@gmail.com', NULL, '$2y$10$UaZ5hr3fxbwYBPXL8tZ2EepNghiyAMvC7Fl/iaFeTKXYf/r2.dOlG', NULL, '2023-08-18 19:34:41', '2023-08-18 19:34:41', 'Femenino', 'Usuario', 0),
(7, 'Roberto Mattu', 'robertomatu@gmail.com', NULL, '$2y$10$VVkqyW4TZKmAcl/80ejjp.PZ7ndPOxD8fTsG/hvePx2yf4C.ryAQ6', NULL, '2023-08-18 19:38:15', '2023-08-18 19:38:15', 'Masculino', 'Administrador', 0),
(8, 'Marcos Alexander Orosco', 'marcos123@gmail.com', NULL, '$2y$10$TuebzO6iSUdtTYQy3O8NqunB6wPUZ2.K3c.xZNSfctHTWvnY8785K', NULL, '2023-08-18 19:43:46', '2023-08-18 19:43:46', 'Masculino', 'Usuario', 0),
(9, 'Milton Chacay', 'miltoncachay@gmail.com', NULL, '$2y$10$82rrr0bJbyvB0swIPEiURemkrDJOSK5Dk.iWctQ/Hko1BWS71tE4G', NULL, '2023-08-18 19:46:03', '2023-08-18 19:46:03', 'Masculino', 'Usuario', 0),
(10, 'Carlos Bustamante', 'carlosbustamante@gmail.com', NULL, '$2y$10$0X5w7BLMm00nZKE2c6ZMeugcBOn9vI4p/EFSGMWPFhGGaRK1f51Sq', NULL, '2023-08-18 20:05:10', '2023-08-18 20:05:10', 'Femenino', 'Usuario', 0),
(11, 'Roberto Fernandez', 'Robertofernandez@gmail.com', NULL, '$2y$10$VWPsD963EKDtuLPM0pp.1uaz7c8AHoBTM/DK0UDSUeNATbZzOLHXS', NULL, '2023-08-18 22:13:34', '2023-08-18 22:13:34', 'Masculino', 'Administrador', 0),
(12, 'Viviana Alapez', 'vivianaalapez@gmail.com', NULL, '$2y$10$geIRUsBv6/KBtQB8mQKs1.L8dlalzRVweatiZhhoZJ/EVN1B57iGC', NULL, '2023-08-18 22:26:58', '2023-08-18 22:26:58', 'Femenino', 'Usuario', 0),
(17, 'Gonzalo Maratua', 'gonzalo@gmail.com', NULL, '$2y$10$0qUPLNlXDcPm7km6kEKQrOyjRn79nImg101XOFa63A1Y.KN3s6xfO', NULL, '2023-08-21 18:49:15', '2023-08-21 18:49:15', 'Masculino', 'Administrador', 0),
(20, 'Jose Luis Alcadia', 'josel@gmail.com', NULL, '$2y$10$Wx99zJeYDfRxRc0tCznFXe5CsXyLsq4SABCeQcR3b9y9t60zkWuhm', NULL, '2023-08-21 18:55:00', '2023-08-21 18:55:00', 'Masculino', 'Usuario', 0),
(21, 'Camen Vallejos', 'carmenv@gmail.com', NULL, '$2y$10$HqfUDehlKsyNWZUK7I9msemAnqsBXcjd5XSDyImwphaDPEqGuXQJS', NULL, '2023-08-21 18:57:01', '2023-08-21 18:57:01', 'Femenino', 'Usuario', 0),
(22, 'Milton  Flores', 'milton@gmail.com', NULL, '$2y$10$Qt74jfreq4YoNmCjLrykmeY9SQaSBBOU8rHdBxm3FOOitwUIqLJIS', NULL, '2023-08-21 18:58:15', '2023-08-21 18:58:15', 'Masculino', 'Usuario', 0),
(23, 'Alvaro Torrez', 'alvaro@gmail.com', NULL, '$2y$10$MQK4sDmQYBgji2KT76DfdeTXTKGFabZ9b16xkGp0eGavBOmws.1vq', NULL, '2023-08-21 22:50:29', '2023-08-21 22:50:29', 'Masculino', 'Usuario', 0),
(24, 'Oscar Cadena', 'oscar@gmail.com', NULL, '$2y$10$AFK1Oa1224MJC3jkYZhRTujjKIVqJWY8gITydl1vOLmSAchqp1xCC', NULL, '2023-08-21 22:53:38', '2023-08-21 22:53:38', 'Masculino', 'Usuario', 0),
(25, 'Ariel Vacies', 'ariel@gmai.com', NULL, '$2y$10$x8ZlfZ/thg6AmoYYG5LqkeTZktxbItbj7DFsl5SDFlSZveaoVnrmy', NULL, '2023-08-21 22:55:33', '2023-08-21 22:55:33', 'Masculino', 'Usuario', 0),
(26, 'Ana', 'anita@gmail.com', NULL, '$2y$10$mch9NDmLO4dDopYwmObJV.uglf4p7weifkrAQHyqfDG4IjZwoQywe', NULL, '2023-08-21 23:01:48', '2023-08-21 23:01:48', 'Femenino', 'Usuario', 0),
(27, 'Marta Alejandra', 'martia@gmail.com', NULL, '$2y$10$/Y7Qq0q7.KyTYcfDTwa9vO8NWw35htnfg1W4N2lWQl0J.7NYFV2.a', NULL, '2023-08-21 23:06:34', '2023-08-21 23:06:34', 'Femenino', 'Usuario', 0),
(28, 'Alcira', 'alci@gmail.com', NULL, '$2y$10$.ZPIgR/ScJBn8WzZd3X7SeBl99CkY3Q/Jyjv.S5mg3JDw49AzwQZS', NULL, '2023-08-21 23:07:29', '2023-08-21 23:07:29', 'Femenino', 'Usuario', 0),
(29, 'Carlos ariel', 'carlos@gmail.com', NULL, '$2y$10$QicRt57sOb8GPDvoRgdkN.jPk0WJz1mfp/cwoaMhfglamMWRvPvhG', NULL, '2023-08-22 00:57:04', '2023-08-22 00:57:04', 'Masculino', 'Usuario', 0),
(30, 'Roberto matias', 'robert@gmail.com', NULL, '$2y$10$VXW5dLKevU9HiFCYB66GCeVWtA4TpR6chP4gA5bqiNaiRF5ah8yqy', NULL, '2023-08-22 01:43:58', '2023-08-22 01:43:58', 'Masculino', 'Usuario', 1);

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `cuenta_clasificador`
--
ALTER TABLE `cuenta_clasificador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `cuenta_producto_clasificador`
--
ALTER TABLE `cuenta_producto_clasificador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `pago_detalle`
--
ALTER TABLE `pago_detalle`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `rubro`
--
ALTER TABLE `rubro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `unidad`
--
ALTER TABLE `unidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
