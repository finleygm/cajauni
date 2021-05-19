# Host: LOCALHOST  (Version 5.7.24)
# Date: 2021-05-19 14:44:16
# Generator: MySQL-Front 6.1  (Build 1.26)


#
# Structure for table "cliente"
#

CREATE TABLE `cliente` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ci` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_expedido` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombres` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `apellidos` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "cliente"
#

INSERT INTO `cliente` VALUES (1,'6304332','SC','FINLEY','GUERRA MENDOZA',NULL,NULL),(2,'9743924','sc','MELISSA','AÑEZ AGUILERA',NULL,NULL),(3,'8135356','sc','Juan','Pachuri Pesoa',NULL,NULL),(4,'8873173','SC','GENRRY','JUSTINIANO YANDURA',NULL,NULL),(5,'7573573','SC','ROY','QUISPE TORREZ',NULL,NULL),(6,'9803178','SC','DIEGO','MOZA VARGAS',NULL,NULL),(7,'9037181','SC','JORGE LUIS','VARGAS MENDEZ',NULL,NULL),(8,'11328676','SC','ERICKA XIMENA','LIMACHI CLAURE',NULL,NULL),(9,'15143488','SC','SORAYA','ORTEGA VIDES',NULL,NULL),(10,'13887154','SC','MARIOLY','PARIAMO VALDEZ',NULL,NULL),(11,'10670314','SC','JULIO ARNALDO','CORTEZ',NULL,NULL),(12,'13168012','Sc','LUIS DAVID','MERCADO GOMEZ',NULL,NULL),(13,'13887169','Sc','ZINARA','CUIQUI MACUAPA',NULL,NULL),(14,'6935823','Sc','ALDAIR','CALLE LIMACO',NULL,NULL),(15,'10670464','Sc','MARIA SOLEDAD','ACOSTA SAAVEDRA',NULL,NULL);

#
# Structure for table "cuenta"
#

CREATE TABLE `cuenta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero_cuenta` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_cuenta` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `precio_unitario` double DEFAULT NULL,
  `cuenta_clasificador_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "cuenta"
#

INSERT INTO `cuenta` VALUES (1,'12341','Pago Historicos','Aranceles',NULL,NULL,15,'4'),(2,'123','Certificado de Notas','Aranceles',NULL,NULL,50,'2'),(4,'4567','Diploma Academico','Aranceles,  Tiene un costo de 200bs',NULL,NULL,200,'4'),(5,'1234','Certificados de Notas  nivel Tecnico Superior','Aranceles',NULL,NULL,30,'4'),(6,'234','pago de certificados de notas','Cuentas',NULL,NULL,30,'2'),(7,'2343','Historicos academicos','Pago para historicos de notas de postgrado',NULL,NULL,15,'2'),(8,'1','DIPLOMA ACADEMICO DE DIPLOMADO',NULL,NULL,NULL,300,'2'),(9,'232','Pago de Alevines',NULL,NULL,NULL,2,'6');

#
# Structure for table "cuenta_clasificador"
#

CREATE TABLE `cuenta_clasificador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_clasificador` int(11) DEFAULT NULL,
  `descripcion` text,
  `unidad_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

#
# Data for table "cuenta_clasificador"
#

INSERT INTO `cuenta_clasificador` VALUES (1,1,'Inscripción/Multa',1),(2,2,'Aranceles de Postgrado',2),(3,3,'Examen de Admision',1),(4,4,'Aranceles Universitarios',3),(5,5,'Aranceles de Pagos Servicios',4),(6,8,'Pago de Pescado',3);

#
# Structure for table "migrations"
#

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "migrations"
#

INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2021_04_19_143346_create_cuenta',1),(4,'2021_04_19_143755_create_pago',1),(5,'2021_04_19_144236_create_cliente',1),(6,'2021_04_19_145757_create_pago_detalle',1);

#
# Structure for table "pago"
#

CREATE TABLE `pago` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `serie` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `total` double(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lugar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cuenta_clasificador_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "pago"
#

INSERT INTO `pago` VALUES (1,1,1,'2021-04-12',250.00,NULL,NULL,'IVO','2',4),(2,2,2,'2021-04-12',295.00,NULL,NULL,'IVO','2',4),(3,3,1,'2021-04-12',230.00,NULL,NULL,'IVO','2',4),(4,4,1,'2021-04-12',230.00,NULL,NULL,'IVO','2',4),(5,5,1,'2021-04-12',200.00,NULL,NULL,'IVO','2',4),(6,6,1,'2021-04-12',200.00,NULL,NULL,'IVO','2',4),(7,7,3,'2021-04-12',45.00,NULL,NULL,'IVO','2',2),(8,8,3,'2021-04-12',45.00,NULL,NULL,'IVO','2',2),(9,9,1,'2021-05-10',230.00,NULL,NULL,'IVO','1',4),(10,10,1,'2021-05-10',230.00,NULL,NULL,'IVO','1',4),(11,11,1,'2021-05-11',300.00,NULL,NULL,'IVO','2',2),(12,12,1,'2021-05-17',200.00,NULL,NULL,'IVO','1',6),(13,13,1,'2021-05-18',4.00,NULL,NULL,'IVO','1',6),(14,14,10,'2021-05-18',17.00,NULL,NULL,'IVO','1',2),(15,15,12,'2021-05-18',200.00,NULL,NULL,'IVO','1',4),(16,16,14,'2021-05-18',30.00,NULL,NULL,'IVO','1',2),(17,17,15,'2021-05-18',2.00,NULL,NULL,'IVO','1',6);

#
# Structure for table "pago_detalle"
#

CREATE TABLE `pago_detalle` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `posicion` int(11) DEFAULT NULL,
  `monto` double(8,2) NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `pago_id` int(11) NOT NULL,
  `precio_unitario` double(8,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cuenta_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "pago_detalle"
#

INSERT INTO `pago_detalle` VALUES (1,NULL,50.00,'Certificado de Notas',1,50.00,1,NULL,NULL,2),(2,NULL,200.00,'Diploma Academico',1,200.00,1,NULL,NULL,4),(3,NULL,15.00,'Pago Historicos',2,15.00,1,NULL,NULL,1),(4,NULL,50.00,'Certificado de Notas',2,50.00,1,NULL,NULL,2),(5,NULL,30.00,'Certificados de Notas  nivel Tecnico Superior',2,30.00,1,NULL,NULL,5),(6,NULL,200.00,'Diploma Academico',2,200.00,1,NULL,NULL,4),(7,NULL,200.00,'Diploma Academico',3,200.00,1,NULL,NULL,4),(8,NULL,30.00,'Certificados de Notas  nivel Tecnico Superior',3,30.00,1,NULL,NULL,5),(9,NULL,200.00,'Diploma Academico',4,200.00,1,NULL,NULL,4),(10,NULL,30.00,'Certificados de Notas  nivel Tecnico Superior',4,30.00,1,NULL,NULL,5),(11,NULL,200.00,'Diploma Academico',5,200.00,1,NULL,NULL,4),(12,NULL,200.00,'Diploma Academico',6,200.00,1,NULL,NULL,4),(13,NULL,30.00,'pago de certificados de notas',7,30.00,1,NULL,NULL,6),(14,NULL,15.00,'Historicos academicos',7,15.00,1,NULL,NULL,7),(15,NULL,15.00,'Historicos academicos',8,15.00,1,NULL,NULL,7),(16,NULL,30.00,'pago de certificados de notas',8,30.00,1,NULL,NULL,6),(17,NULL,200.00,'Diploma Academico',9,200.00,1,NULL,NULL,4),(18,NULL,30.00,'Certificados de Notas  nivel Tecnico Superior',9,30.00,1,NULL,NULL,5),(19,NULL,30.00,'Certificados de Notas  nivel Tecnico Superior',10,30.00,1,NULL,NULL,5),(20,NULL,200.00,'Diploma Academico',10,200.00,1,NULL,NULL,4),(21,NULL,300.00,'DIPLOMA ACADEMICO DE DIPLOMADO',11,300.00,1,NULL,NULL,8),(22,NULL,200.00,'Pago de Alevines',12,2.00,100,NULL,NULL,9),(23,NULL,2.00,'Pago de Alevines',13,2.00,1,NULL,NULL,9),(24,NULL,2.00,'Pago de Alevines',13,2.00,1,NULL,NULL,9),(25,NULL,2.00,'Pago de Alevines',14,2.00,1,NULL,NULL,9),(26,NULL,15.00,'Historicos academicos',14,15.00,1,NULL,NULL,7),(27,NULL,200.00,'Diploma Academico',15,200.00,1,NULL,NULL,4),(28,NULL,30.00,'pago de certificados de notas',16,30.00,1,NULL,NULL,6),(29,NULL,2.00,'Pago de Alevines',17,2.00,1,NULL,NULL,9);

#
# Structure for table "password_resets"
#

CREATE TABLE `password_resets` (
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "password_resets"
#


#
# Structure for table "rubro"
#

CREATE TABLE `rubro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_identificador` int(11) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

#
# Data for table "rubro"
#

INSERT INTO `rubro` VALUES (1,12100,'Venta de Bienes'),(2,12200,'Venta de Servicios'),(3,15200,'Derechos');

#
# Structure for table "unidad"
#

CREATE TABLE `unidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubro_id` int(255) DEFAULT NULL,
  `numero_unidad` int(255) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

#
# Data for table "unidad"
#

INSERT INTO `unidad` VALUES (1,1,1,'Rectorado'),(2,2,2,'Medicina Veterinaria y Zootecnia'),(3,2,3,'Ing. Ecopiscicultura'),(4,1,4,'Vice Rectorado'),(5,2,6,'Contabilidad'),(6,3,7,'Almacenes'),(7,2,8,'Biblioteca'),(8,1,9,'Modulos Ecopiscicultura');

#
# Structure for table "users"
#

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "users"
#

INSERT INTO `users` VALUES (1,'FINLEY GUERRA','finley_1231@hotmail.com',NULL,'$2y$10$M15Z8PeIV.CgwFRWSz3no.Yxc3WdMNt8qUM6Y6gMyoUraOUNCHmfa',NULL,'2021-04-19 17:33:53','2021-04-19 17:33:53'),(2,'Ericka Barrientos','erikabr20@hotmail.com',NULL,'$2y$10$n2nU0blbDCZ943il22Tklude8FoauBWw7S9/J1gWrQ8EwS1H0Oiw.',NULL,'2021-05-05 15:35:34','2021-05-05 15:35:34');
