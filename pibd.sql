-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: pibd
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `anuncios`
--

DROP TABLE IF EXISTS `anuncios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anuncios` (
  `IdAnuncio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TAnuncio` tinyint(3) unsigned NOT NULL,
  `TVivienda` tinyint(3) unsigned NOT NULL,
  `FPrincipal` varchar(255) DEFAULT NULL,
  `Alternativo` varchar(255) DEFAULT NULL,
  `Titulo` varchar(255) DEFAULT NULL,
  `Precio` decimal(10,2) DEFAULT NULL,
  `Texto` text DEFAULT NULL,
  `Ciudad` varchar(255) DEFAULT NULL,
  `Pais` int(10) unsigned NOT NULL,
  `Superficie` decimal(10,2) DEFAULT NULL,
  `NHabitaciones` int(10) unsigned DEFAULT NULL,
  `NBanyos` int(10) unsigned DEFAULT NULL,
  `Planta` int(11) DEFAULT NULL,
  `Anyo` int(10) unsigned DEFAULT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`IdAnuncio`),
  KEY `FK_Anuncios_TiposAnuncios` (`TAnuncio`),
  KEY `FK_Anuncios_TiposViviendas` (`TVivienda`),
  KEY `FK_Anuncios_Paises` (`Pais`),
  KEY `FK_Anuncios_Usuarios` (`Usuario`),
  CONSTRAINT `FK_Anuncios_Paises` FOREIGN KEY (`Pais`) REFERENCES `paises` (`IdPais`),
  CONSTRAINT `FK_Anuncios_TiposAnuncios` FOREIGN KEY (`TAnuncio`) REFERENCES `tiposanuncios` (`IdTAnuncio`),
  CONSTRAINT `FK_Anuncios_TiposViviendas` FOREIGN KEY (`TVivienda`) REFERENCES `tiposviviendas` (`IdTVivienda`),
  CONSTRAINT `FK_Anuncios_Usuarios` FOREIGN KEY (`Usuario`) REFERENCES `usuarios` (`IdUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anuncios`
--

LOCK TABLES `anuncios` WRITE;
/*!40000 ALTER TABLE `anuncios` DISABLE KEYS */;
INSERT INTO `anuncios` VALUES (2,2,2,'img/casa2.jpg','Foto de Estudio funcional y económico','Estudio funcional y económico',450.00,'Acogedor estudio cerca del centro. Ideal para estudiantes o solteros. Cocina americana, baño completo y todos los gastos incluidos en el precio.','Bilbao',1,40.00,1,1,NULL,NULL,'2025-11-12 18:05:12',2),(3,1,3,'img/casa3.jpg','Foto de oficina moderna en distrito financiero','Oficina moderna en Madrid',320000.00,'Oficina luminosa en edificio exclusivo. Recepción, 3 despachos y sala de reuniones. Baños comunes.','Madrid',1,120.00,3,2,NULL,2005,'2025-11-12 18:05:12',3),(4,2,4,'img/casa4.jpg','Foto de local comercial a pie de calle','Local comercial en alquiler',1200.00,'Local comercial de 80m2 a pie de calle, zona de mucho paso. Antigua tienda de ropa. Salida de humos no disponible.','Barcelona',1,80.00,0,1,0,NULL,'2025-11-12 18:05:12',4),(5,1,5,'img/casa10.jpg','Foto de plaza de garaje amplia','Plaza de garaje céntrica',18000.00,'Plaza de garaje para coche grande. Fácil acceso y maniobra. Puerta automática y vigilancia 24h.','Valencia',1,15.00,0,0,NULL,NULL,'2025-11-12 18:05:12',5),(13,2,1,'img/casa5.jpg','Foto de Ático luminoso','Ático luminoso con terraza',950.00,'Precioso ático en el centro de la ciudad. Recién reformado, ideal para parejas. Gran terraza con vistas.','Sevilla',1,65.00,2,1,5,2010,'2025-11-13 09:00:00',5),(16,1,2,'img/1764271132_anuncio1.jpg',NULL,'Chalet grande',2000000.00,'Muy chula y barata','Alicante',1,NULL,NULL,NULL,NULL,NULL,'2025-11-27 18:54:55',23);
/*!40000 ALTER TABLE `anuncios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estilos`
--

DROP TABLE IF EXISTS `estilos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estilos` (
  `IdEstilo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `Fichero` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdEstilo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estilos`
--

LOCK TABLES `estilos` WRITE;
/*!40000 ALTER TABLE `estilos` DISABLE KEYS */;
INSERT INTO `estilos` VALUES (1,'Estilo Principal','Estilo por defecto','css/styles.css'),(2,'Estilo Noche','Modo oscuro','css/night.css'),(3,'Estilo Contraste','Alto contraste','css/contrast.css'),(4,'Estilo Grande','Letra grande','css/big.css'),(5,'Estilo Contraste Grande','Alto contraste y letra grande','css/contrast_big.css');
/*!40000 ALTER TABLE `estilos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fotos`
--

DROP TABLE IF EXISTS `fotos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fotos` (
  `IdFoto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(255) DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Alternativo` varchar(255) DEFAULT NULL,
  `Anuncio` int(10) unsigned NOT NULL,
  PRIMARY KEY (`IdFoto`),
  KEY `FK_Fotos_Anuncios` (`Anuncio`),
  CONSTRAINT `FK_Fotos_Anuncios` FOREIGN KEY (`Anuncio`) REFERENCES `anuncios` (`IdAnuncio`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fotos`
--

LOCK TABLES `fotos` WRITE;
/*!40000 ALTER TABLE `fotos` DISABLE KEYS */;
INSERT INTO `fotos` VALUES (6,'Salón amplio','img/casa1_1.jpg','Vista del salón principal con grandes ventanales',13),(7,'Cocina moderna','img/casa1_2.jpg','Cocina equipada con isla central y acabados de lujo',13);
/*!40000 ALTER TABLE `fotos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mensajes` (
  `IdMensaje` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TMensaje` tinyint(3) unsigned NOT NULL,
  `Texto` varchar(4000) DEFAULT NULL,
  `Anuncio` int(10) unsigned NOT NULL,
  `UsuOrigen` int(10) unsigned NOT NULL,
  `UsuDestino` int(10) unsigned NOT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`IdMensaje`),
  KEY `FK_Mensajes_TiposMensajes` (`TMensaje`),
  KEY `FK_Mensajes_Anuncio` (`Anuncio`),
  KEY `FK_Mensajes_UsuOrigen` (`UsuOrigen`),
  KEY `FK_Mensajes_UsuDestino` (`UsuDestino`),
  CONSTRAINT `FK_Mensajes_Anuncio` FOREIGN KEY (`Anuncio`) REFERENCES `anuncios` (`IdAnuncio`),
  CONSTRAINT `FK_Mensajes_TiposMensajes` FOREIGN KEY (`TMensaje`) REFERENCES `tiposmensajes` (`IdTMensaje`),
  CONSTRAINT `FK_Mensajes_UsuDestino` FOREIGN KEY (`UsuDestino`) REFERENCES `usuarios` (`IdUsuario`),
  CONSTRAINT `FK_Mensajes_UsuOrigen` FOREIGN KEY (`UsuOrigen`) REFERENCES `usuarios` (`IdUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes`
--

LOCK TABLES `mensajes` WRITE;
/*!40000 ALTER TABLE `mensajes` DISABLE KEYS */;
INSERT INTO `mensajes` VALUES (1,3,'ey ey ey',13,23,5,'2025-11-27 20:06:54');
/*!40000 ALTER TABLE `mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paises`
--

DROP TABLE IF EXISTS `paises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paises` (
  `IdPais` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NomPais` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdPais`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paises`
--

LOCK TABLES `paises` WRITE;
/*!40000 ALTER TABLE `paises` DISABLE KEYS */;
INSERT INTO `paises` VALUES (1,'España'),(2,'Portugal'),(3,'Francia'),(4,'Alemania'),(5,'Italia');
/*!40000 ALTER TABLE `paises` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes`
--

DROP TABLE IF EXISTS `solicitudes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitudes` (
  `IdSolicitud` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Anuncio` int(10) unsigned NOT NULL,
  `Texto` varchar(4000) DEFAULT NULL,
  `Nombre` varchar(200) DEFAULT NULL,
  `Email` varchar(254) DEFAULT NULL,
  `Direccion` text DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Color` varchar(255) DEFAULT NULL,
  `Copias` int(10) unsigned DEFAULT NULL,
  `Resolucion` int(10) unsigned DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `IColor` tinyint(3) unsigned DEFAULT NULL,
  `IPrecio` tinyint(3) unsigned DEFAULT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Coste` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`IdSolicitud`),
  KEY `FK_Solicitudes_Anuncio` (`Anuncio`),
  CONSTRAINT `FK_Solicitudes_Anuncio` FOREIGN KEY (`Anuncio`) REFERENCES `anuncios` (`IdAnuncio`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes`
--

LOCK TABLES `solicitudes` WRITE;
/*!40000 ALTER TABLE `solicitudes` DISABLE KEYS */;
INSERT INTO `solicitudes` VALUES (1,16,'pruebapruebaprueba','Marcos Dáiz Moleón','marcos@gmail.com','1, 11, 03690, Alciatne, Alicante','684138645','#004aad',1,150,'2025-11-27',0,1,'2025-11-27 19:37:13',7.00);
/*!40000 ALTER TABLE `solicitudes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiposanuncios`
--

DROP TABLE IF EXISTS `tiposanuncios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tiposanuncios` (
  `IdTAnuncio` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `NomTAnuncio` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdTAnuncio`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tiposanuncios`
--

LOCK TABLES `tiposanuncios` WRITE;
/*!40000 ALTER TABLE `tiposanuncios` DISABLE KEYS */;
INSERT INTO `tiposanuncios` VALUES (1,'Venta'),(2,'Alquiler');
/*!40000 ALTER TABLE `tiposanuncios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiposmensajes`
--

DROP TABLE IF EXISTS `tiposmensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tiposmensajes` (
  `IdTMensaje` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `NomTMensaje` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdTMensaje`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tiposmensajes`
--

LOCK TABLES `tiposmensajes` WRITE;
/*!40000 ALTER TABLE `tiposmensajes` DISABLE KEYS */;
INSERT INTO `tiposmensajes` VALUES (1,'Más información'),(2,'Solicitar una cita'),(3,'Comunicar una oferta');
/*!40000 ALTER TABLE `tiposmensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiposviviendas`
--

DROP TABLE IF EXISTS `tiposviviendas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tiposviviendas` (
  `IdTVivienda` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `NomTVivienda` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdTVivienda`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tiposviviendas`
--

LOCK TABLES `tiposviviendas` WRITE;
/*!40000 ALTER TABLE `tiposviviendas` DISABLE KEYS */;
INSERT INTO `tiposviviendas` VALUES (1,'Obra nueva'),(2,'Vivienda'),(3,'Oficina'),(4,'Local'),(5,'Garaje');
/*!40000 ALTER TABLE `tiposviviendas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `IdUsuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NomUsuario` varchar(15) NOT NULL,
  `Clave` varchar(255) NOT NULL,
  `Email` varchar(254) DEFAULT NULL,
  `Sexo` tinyint(3) unsigned DEFAULT NULL,
  `FNacimiento` date DEFAULT NULL,
  `Ciudad` varchar(255) DEFAULT NULL,
  `Pais` int(10) unsigned NOT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Estilo` int(10) unsigned NOT NULL,
  PRIMARY KEY (`IdUsuario`),
  UNIQUE KEY `NomUsuario` (`NomUsuario`),
  KEY `FK_Usuarios_Paises` (`Pais`),
  KEY `FK_Usuarios_Estilos` (`Estilo`),
  CONSTRAINT `FK_Usuarios_Estilos` FOREIGN KEY (`Estilo`) REFERENCES `estilos` (`IdEstilo`),
  CONSTRAINT `FK_Usuarios_Paises` FOREIGN KEY (`Pais`) REFERENCES `paises` (`IdPais`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (2,'user1','clave1','user1@ejemplo.com',0,'1995-05-10','Madrid',1,NULL,'2025-11-12 11:18:58',2),(3,'admin','secreto','admin@ejemplo.com',1,'1990-03-20','Barcelona',1,NULL,'2025-11-12 11:18:58',3),(4,'marcos','a','marcos@ejemplo.com',1,'2002-11-15','Valencia',1,NULL,'2025-11-12 11:18:58',1),(5,'gustavo','a','gustavo@ejemplo.com',1,'1988-07-30','Sevilla',1,NULL,'2025-11-12 11:19:00',5),(18,'jose','Jose12','adwadwadwadw@gmail.com',1,'2004-12-06','Alicante',1,'img/default_user.jpg','2025-11-12 17:43:06',1),(23,'joselete','$2y$10$6nhVVtQdSh1ZX1wW4p0NJeLVC11at3iktkjpPpeDX0hKnn91sSnBC','dawdaw@gmail.com',0,'2004-12-12','Alicante',1,'img/default_user.jpg','2025-11-25 12:04:51',1),(24,'Gustavoymarcos','$2y$10$ZJ.qonFxKLRSGSPVzeVg.eB1JKBauYZzZhSrZxFjbFajEawSe2VcK','gustavoymarcos@alu.ua.es',1,'2004-12-04','Toulousse',3,'img/default_user.jpg','2025-11-26 12:08:22',2);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-27 21:16:52
