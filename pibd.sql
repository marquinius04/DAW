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
-- Table structure for table `ANUNCIOS`
--

DROP TABLE IF EXISTS `ANUNCIOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ANUNCIOS` (
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
  CONSTRAINT `FK_Anuncios_Paises` FOREIGN KEY (`Pais`) REFERENCES `PAISES` (`IdPais`),
  CONSTRAINT `FK_Anuncios_TiposAnuncios` FOREIGN KEY (`TAnuncio`) REFERENCES `TIPOSANUNCIOS` (`IdTAnuncio`),
  CONSTRAINT `FK_Anuncios_TiposViviendas` FOREIGN KEY (`TVivienda`) REFERENCES `TIPOSVIVIENDAS` (`IdTVivienda`),
  CONSTRAINT `FK_Anuncios_Usuarios` FOREIGN KEY (`Usuario`) REFERENCES `USUARIOS` (`IdUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ANUNCIOS`
--

LOCK TABLES `ANUNCIOS` WRITE;
/*!40000 ALTER TABLE `ANUNCIOS` DISABLE KEYS */;
INSERT INTO `ANUNCIOS` VALUES (1,1,2,'img/casa1.jpg','Foto de Chalet de lujo con piscina','Chalet de lujo con piscina',750000.00,'Espectacular chalet ubicado en zona premium. Cuenta con 4 habitaciones, 3 baños, jardín y piscina privada. Vistas a la montaña y al mar.','Marbella',1,250.00,4,3,NULL,NULL,'2025-11-12 18:05:12',1),(2,2,2,'img/casa2.jpg','Foto de Estudio funcional y económico','Estudio funcional y económico',450.00,'Acogedor estudio cerca del centro. Ideal para estudiantes o solteros. Cocina americana, baño completo y todos los gastos incluidos en el precio.','Bilbao',1,40.00,1,1,NULL,NULL,'2025-11-12 18:05:12',2),(3,1,3,'img/casa3.jpg','Foto de oficina moderna en distrito financiero','Oficina moderna en Madrid',320000.00,'Oficina luminosa en edificio exclusivo. Recepción, 3 despachos y sala de reuniones. Baños comunes.','Madrid',1,120.00,3,2,NULL,2005,'2025-11-12 18:05:12',3),(4,2,4,'img/casa4.jpg','Foto de local comercial a pie de calle','Local comercial en alquiler',1200.00,'Local comercial de 80m2 a pie de calle, zona de mucho paso. Antigua tienda de ropa. Salida de humos no disponible.','Barcelona',1,80.00,0,1,0,NULL,'2025-11-12 18:05:12',4),(5,1,5,'img/casa5.jpg','Foto de plaza de garaje amplia','Plaza de garaje céntrica',18000.00,'Plaza de garaje para coche grande. Fácil acceso y maniobra. Puerta automática y vigilancia 24h.','Valencia',1,15.00,0,0,NULL,NULL,'2025-11-12 18:05:12',5),(6,1,1,'img/casa10.jpg','Foto de promoción de obra nueva','Piso de Obra Nueva en Alicante',210000.00,'Promoción de obra nueva en Playa de San Juan. 2 habitaciones, 2 baños. Urbanización con piscina, pádel y zonas verdes.','Alicante',1,90.00,2,2,NULL,2024,'2025-11-12 18:05:14',1);
/*!40000 ALTER TABLE `ANUNCIOS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ESTILOS`
--

DROP TABLE IF EXISTS `ESTILOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ESTILOS` (
  `IdEstilo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `Fichero` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdEstilo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ESTILOS`
--

LOCK TABLES `ESTILOS` WRITE;
/*!40000 ALTER TABLE `ESTILOS` DISABLE KEYS */;
INSERT INTO `ESTILOS` VALUES (1,'Estilo Principal','Estilo por defecto','css/styles.css'),(2,'Estilo Noche','Modo oscuro','css/night.css'),(3,'Estilo Contraste','Alto contraste','css/contrast.css'),(4,'Estilo Grande','Letra grande','css/big.css'),(5,'Estilo Contraste Grande','Alto contraste y letra grande','css/contrast_big.css');
/*!40000 ALTER TABLE `ESTILOS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FOTOS`
--

DROP TABLE IF EXISTS `FOTOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FOTOS` (
  `IdFoto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(255) DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Alternativo` varchar(255) DEFAULT NULL,
  `Anuncio` int(10) unsigned NOT NULL,
  PRIMARY KEY (`IdFoto`),
  KEY `FK_Fotos_Anuncios` (`Anuncio`),
  CONSTRAINT `FK_Fotos_Anuncios` FOREIGN KEY (`Anuncio`) REFERENCES `ANUNCIOS` (`IdAnuncio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FOTOS`
--

LOCK TABLES `FOTOS` WRITE;
/*!40000 ALTER TABLE `FOTOS` DISABLE KEYS */;
/*!40000 ALTER TABLE `FOTOS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MENSAJES`
--

DROP TABLE IF EXISTS `MENSAJES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MENSAJES` (
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
  CONSTRAINT `FK_Mensajes_Anuncio` FOREIGN KEY (`Anuncio`) REFERENCES `ANUNCIOS` (`IdAnuncio`),
  CONSTRAINT `FK_Mensajes_TiposMensajes` FOREIGN KEY (`TMensaje`) REFERENCES `TIPOSMENSAJES` (`IdTMensaje`),
  CONSTRAINT `FK_Mensajes_UsuDestino` FOREIGN KEY (`UsuDestino`) REFERENCES `USUARIOS` (`IdUsuario`),
  CONSTRAINT `FK_Mensajes_UsuOrigen` FOREIGN KEY (`UsuOrigen`) REFERENCES `USUARIOS` (`IdUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MENSAJES`
--

LOCK TABLES `MENSAJES` WRITE;
/*!40000 ALTER TABLE `MENSAJES` DISABLE KEYS */;
/*!40000 ALTER TABLE `MENSAJES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PAISES`
--

DROP TABLE IF EXISTS `PAISES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PAISES` (
  `IdPais` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NomPais` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdPais`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PAISES`
--

LOCK TABLES `PAISES` WRITE;
/*!40000 ALTER TABLE `PAISES` DISABLE KEYS */;
INSERT INTO `PAISES` VALUES (1,'España'),(2,'Portugal'),(3,'Francia'),(4,'Alemania'),(5,'Italia');
/*!40000 ALTER TABLE `PAISES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SOLICITUDES`
--

DROP TABLE IF EXISTS `SOLICITUDES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SOLICITUDES` (
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
  CONSTRAINT `FK_Solicitudes_Anuncio` FOREIGN KEY (`Anuncio`) REFERENCES `ANUNCIOS` (`IdAnuncio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SOLICITUDES`
--

LOCK TABLES `SOLICITUDES` WRITE;
/*!40000 ALTER TABLE `SOLICITUDES` DISABLE KEYS */;
/*!40000 ALTER TABLE `SOLICITUDES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TIPOSANUNCIOS`
--

DROP TABLE IF EXISTS `TIPOSANUNCIOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TIPOSANUNCIOS` (
  `IdTAnuncio` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `NomTAnuncio` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdTAnuncio`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TIPOSANUNCIOS`
--

LOCK TABLES `TIPOSANUNCIOS` WRITE;
/*!40000 ALTER TABLE `TIPOSANUNCIOS` DISABLE KEYS */;
INSERT INTO `TIPOSANUNCIOS` VALUES (1,'Venta'),(2,'Alquiler');
/*!40000 ALTER TABLE `TIPOSANUNCIOS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TIPOSMENSAJES`
--

DROP TABLE IF EXISTS `TIPOSMENSAJES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TIPOSMENSAJES` (
  `IdTMensaje` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `NomTMensaje` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdTMensaje`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TIPOSMENSAJES`
--

LOCK TABLES `TIPOSMENSAJES` WRITE;
/*!40000 ALTER TABLE `TIPOSMENSAJES` DISABLE KEYS */;
INSERT INTO `TIPOSMENSAJES` VALUES (1,'Más información'),(2,'Solicitar una cita'),(3,'Comunicar una oferta');
/*!40000 ALTER TABLE `TIPOSMENSAJES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TIPOSVIVIENDAS`
--

DROP TABLE IF EXISTS `TIPOSVIVIENDAS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TIPOSVIVIENDAS` (
  `IdTVivienda` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `NomTVivienda` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdTVivienda`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TIPOSVIVIENDAS`
--

LOCK TABLES `TIPOSVIVIENDAS` WRITE;
/*!40000 ALTER TABLE `TIPOSVIVIENDAS` DISABLE KEYS */;
INSERT INTO `TIPOSVIVIENDAS` VALUES (1,'Obra nueva'),(2,'Vivienda'),(3,'Oficina'),(4,'Local'),(5,'Garaje');
/*!40000 ALTER TABLE `TIPOSVIVIENDAS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USUARIOS`
--

DROP TABLE IF EXISTS `USUARIOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USUARIOS` (
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
  CONSTRAINT `FK_Usuarios_Estilos` FOREIGN KEY (`Estilo`) REFERENCES `ESTILOS` (`IdEstilo`),
  CONSTRAINT `FK_Usuarios_Paises` FOREIGN KEY (`Pais`) REFERENCES `PAISES` (`IdPais`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USUARIOS`
--

LOCK TABLES `USUARIOS` WRITE;
/*!40000 ALTER TABLE `USUARIOS` DISABLE KEYS */;
INSERT INTO `USUARIOS` VALUES (1,'a','a','a@ejemplo.com',1,'2000-01-01','Alicante',1,NULL,'2025-11-12 11:18:58',1),(2,'user1','clave1','user1@ejemplo.com',0,'1995-05-10','Madrid',1,NULL,'2025-11-12 11:18:58',2),(3,'admin','secreto','admin@ejemplo.com',1,'1990-03-20','Barcelona',1,NULL,'2025-11-12 11:18:58',3),(4,'marcos','a','marcos@ejemplo.com',1,'2002-11-15','Valencia',1,NULL,'2025-11-12 11:18:58',4),(5,'gustavo','a','gustavo@ejemplo.com',1,'1988-07-30','Sevilla',1,NULL,'2025-11-12 11:19:00',5),(18,'jose','Jose12','adwadwadwadw@gmail.com',1,'2004-12-06','Alicante',1,'img/default_user.jpg','2025-11-12 17:43:06',1);
/*!40000 ALTER TABLE `USUARIOS` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-12 20:05:59
