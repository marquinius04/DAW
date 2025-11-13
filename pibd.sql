-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2025 a las 20:36:23
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
-- Base de datos: `pibd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anuncios`
--

CREATE TABLE `anuncios` (
  `IdAnuncio` int(10) UNSIGNED NOT NULL,
  `TAnuncio` tinyint(3) UNSIGNED NOT NULL,
  `TVivienda` tinyint(3) UNSIGNED NOT NULL,
  `FPrincipal` varchar(255) DEFAULT NULL,
  `Alternativo` varchar(255) DEFAULT NULL,
  `Titulo` varchar(255) DEFAULT NULL,
  `Precio` decimal(10,2) DEFAULT NULL,
  `Texto` text DEFAULT NULL,
  `Ciudad` varchar(255) DEFAULT NULL,
  `Pais` int(10) UNSIGNED NOT NULL,
  `Superficie` decimal(10,2) DEFAULT NULL,
  `NHabitaciones` int(10) UNSIGNED DEFAULT NULL,
  `NBanyos` int(10) UNSIGNED DEFAULT NULL,
  `Planta` int(11) DEFAULT NULL,
  `Anyo` int(10) UNSIGNED DEFAULT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Usuario` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `anuncios`
--

INSERT INTO `anuncios` (`IdAnuncio`, `TAnuncio`, `TVivienda`, `FPrincipal`, `Alternativo`, `Titulo`, `Precio`, `Texto`, `Ciudad`, `Pais`, `Superficie`, `NHabitaciones`, `NBanyos`, `Planta`, `Anyo`, `FRegistro`, `Usuario`) VALUES
(2, 2, 2, 'img/casa2.jpg', 'Foto de Estudio funcional y económico', 'Estudio funcional y económico', 450.00, 'Acogedor estudio cerca del centro. Ideal para estudiantes o solteros. Cocina americana, baño completo y todos los gastos incluidos en el precio.', 'Bilbao', 1, 40.00, 1, 1, NULL, NULL, '2025-11-12 18:05:12', 2),
(3, 1, 3, 'img/casa3.jpg', 'Foto de oficina moderna en distrito financiero', 'Oficina moderna en Madrid', 320000.00, 'Oficina luminosa en edificio exclusivo. Recepción, 3 despachos y sala de reuniones. Baños comunes.', 'Madrid', 1, 120.00, 3, 2, NULL, 2005, '2025-11-12 18:05:12', 3),
(4, 2, 4, 'img/casa4.jpg', 'Foto de local comercial a pie de calle', 'Local comercial en alquiler', 1200.00, 'Local comercial de 80m2 a pie de calle, zona de mucho paso. Antigua tienda de ropa. Salida de humos no disponible.', 'Barcelona', 1, 80.00, 0, 1, 0, NULL, '2025-11-12 18:05:12', 4),
(5, 1, 5, 'img/casa10.jpg', 'Foto de plaza de garaje amplia', 'Plaza de garaje céntrica', 18000.00, 'Plaza de garaje para coche grande. Fácil acceso y maniobra. Puerta automática y vigilancia 24h.', 'Valencia', 1, 15.00, 0, 0, NULL, NULL, '2025-11-12 18:05:12', 5),
(13, 2, 1, 'img/casa5.jpg', 'Foto de Ático luminoso', 'Ático luminoso con terraza', 950.00, 'Precioso ático en el centro de la ciudad. Recién reformado, ideal para parejas. Gran terraza con vistas.', 'Sevilla', 1, 65.00, 2, 1, 5, 2010, '2025-11-13 09:00:00', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estilos`
--

CREATE TABLE `estilos` (
  `IdEstilo` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(255) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `Fichero` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estilos`
--

INSERT INTO `estilos` (`IdEstilo`, `Nombre`, `Descripcion`, `Fichero`) VALUES
(1, 'Estilo Principal', 'Estilo por defecto', 'css/styles.css'),
(2, 'Estilo Noche', 'Modo oscuro', 'css/night.css'),
(3, 'Estilo Contraste', 'Alto contraste', 'css/contrast.css'),
(4, 'Estilo Grande', 'Letra grande', 'css/big.css'),
(5, 'Estilo Contraste Grande', 'Alto contraste y letra grande', 'css/contrast_big.css');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotos`
--

CREATE TABLE `fotos` (
  `IdFoto` int(10) UNSIGNED NOT NULL,
  `Titulo` varchar(255) DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Alternativo` varchar(255) DEFAULT NULL,
  `Anuncio` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fotos`
--

INSERT INTO `fotos` (`IdFoto`, `Titulo`, `Foto`, `Alternativo`, `Anuncio`) VALUES
(6, 'Salón amplio', 'img/casa1_1.jpg', 'Vista del salón principal con grandes ventanales', 13),
(7, 'Cocina moderna', 'img/casa1_2.jpg', 'Cocina equipada con isla central y acabados de lujo', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `IdMensaje` int(10) UNSIGNED NOT NULL,
  `TMensaje` tinyint(3) UNSIGNED NOT NULL,
  `Texto` varchar(4000) DEFAULT NULL,
  `Anuncio` int(10) UNSIGNED NOT NULL,
  `UsuOrigen` int(10) UNSIGNED NOT NULL,
  `UsuDestino` int(10) UNSIGNED NOT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises`
--

CREATE TABLE `paises` (
  `IdPais` int(10) UNSIGNED NOT NULL,
  `NomPais` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paises`
--

INSERT INTO `paises` (`IdPais`, `NomPais`) VALUES
(1, 'España'),
(2, 'Portugal'),
(3, 'Francia'),
(4, 'Alemania'),
(5, 'Italia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `IdSolicitud` int(10) UNSIGNED NOT NULL,
  `Anuncio` int(10) UNSIGNED NOT NULL,
  `Texto` varchar(4000) DEFAULT NULL,
  `Nombre` varchar(200) DEFAULT NULL,
  `Email` varchar(254) DEFAULT NULL,
  `Direccion` text DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Color` varchar(255) DEFAULT NULL,
  `Copias` int(10) UNSIGNED DEFAULT NULL,
  `Resolucion` int(10) UNSIGNED DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `IColor` tinyint(3) UNSIGNED DEFAULT NULL,
  `IPrecio` tinyint(3) UNSIGNED DEFAULT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Coste` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposanuncios`
--

CREATE TABLE `tiposanuncios` (
  `IdTAnuncio` tinyint(3) UNSIGNED NOT NULL,
  `NomTAnuncio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiposanuncios`
--

INSERT INTO `tiposanuncios` (`IdTAnuncio`, `NomTAnuncio`) VALUES
(1, 'Venta'),
(2, 'Alquiler');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposmensajes`
--

CREATE TABLE `tiposmensajes` (
  `IdTMensaje` tinyint(3) UNSIGNED NOT NULL,
  `NomTMensaje` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiposmensajes`
--

INSERT INTO `tiposmensajes` (`IdTMensaje`, `NomTMensaje`) VALUES
(1, 'Más información'),
(2, 'Solicitar una cita'),
(3, 'Comunicar una oferta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposviviendas`
--

CREATE TABLE `tiposviviendas` (
  `IdTVivienda` tinyint(3) UNSIGNED NOT NULL,
  `NomTVivienda` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiposviviendas`
--

INSERT INTO `tiposviviendas` (`IdTVivienda`, `NomTVivienda`) VALUES
(1, 'Obra nueva'),
(2, 'Vivienda'),
(3, 'Oficina'),
(4, 'Local'),
(5, 'Garaje');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IdUsuario` int(10) UNSIGNED NOT NULL,
  `NomUsuario` varchar(15) NOT NULL,
  `Clave` varchar(255) NOT NULL,
  `Email` varchar(254) DEFAULT NULL,
  `Sexo` tinyint(3) UNSIGNED DEFAULT NULL,
  `FNacimiento` date DEFAULT NULL,
  `Ciudad` varchar(255) DEFAULT NULL,
  `Pais` int(10) UNSIGNED NOT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Estilo` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IdUsuario`, `NomUsuario`, `Clave`, `Email`, `Sexo`, `FNacimiento`, `Ciudad`, `Pais`, `Foto`, `FRegistro`, `Estilo`) VALUES
(2, 'user1', 'clave1', 'user1@ejemplo.com', 0, '1995-05-10', 'Madrid', 1, NULL, '2025-11-12 11:18:58', 2),
(3, 'admin', 'secreto', 'admin@ejemplo.com', 1, '1990-03-20', 'Barcelona', 1, NULL, '2025-11-12 11:18:58', 3),
(4, 'marcos', 'a', 'marcos@ejemplo.com', 1, '2002-11-15', 'Valencia', 1, NULL, '2025-11-12 11:18:58', 1),
(5, 'gustavo', 'a', 'gustavo@ejemplo.com', 1, '1988-07-30', 'Sevilla', 1, NULL, '2025-11-12 11:19:00', 5),
(18, 'jose', 'Jose12', 'adwadwadwadw@gmail.com', 1, '2004-12-06', 'Alicante', 1, 'img/default_user.jpg', '2025-11-12 17:43:06', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  ADD PRIMARY KEY (`IdAnuncio`),
  ADD KEY `FK_Anuncios_TiposAnuncios` (`TAnuncio`),
  ADD KEY `FK_Anuncios_TiposViviendas` (`TVivienda`),
  ADD KEY `FK_Anuncios_Paises` (`Pais`),
  ADD KEY `FK_Anuncios_Usuarios` (`Usuario`);

--
-- Indices de la tabla `estilos`
--
ALTER TABLE `estilos`
  ADD PRIMARY KEY (`IdEstilo`);

--
-- Indices de la tabla `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`IdFoto`),
  ADD KEY `FK_Fotos_Anuncios` (`Anuncio`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`IdMensaje`),
  ADD KEY `FK_Mensajes_TiposMensajes` (`TMensaje`),
  ADD KEY `FK_Mensajes_Anuncio` (`Anuncio`),
  ADD KEY `FK_Mensajes_UsuOrigen` (`UsuOrigen`),
  ADD KEY `FK_Mensajes_UsuDestino` (`UsuDestino`);

--
-- Indices de la tabla `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`IdPais`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`IdSolicitud`),
  ADD KEY `FK_Solicitudes_Anuncio` (`Anuncio`);

--
-- Indices de la tabla `tiposanuncios`
--
ALTER TABLE `tiposanuncios`
  ADD PRIMARY KEY (`IdTAnuncio`);

--
-- Indices de la tabla `tiposmensajes`
--
ALTER TABLE `tiposmensajes`
  ADD PRIMARY KEY (`IdTMensaje`);

--
-- Indices de la tabla `tiposviviendas`
--
ALTER TABLE `tiposviviendas`
  ADD PRIMARY KEY (`IdTVivienda`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IdUsuario`),
  ADD UNIQUE KEY `NomUsuario` (`NomUsuario`),
  ADD KEY `FK_Usuarios_Paises` (`Pais`),
  ADD KEY `FK_Usuarios_Estilos` (`Estilo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  MODIFY `IdAnuncio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `estilos`
--
ALTER TABLE `estilos`
  MODIFY `IdEstilo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `fotos`
--
ALTER TABLE `fotos`
  MODIFY `IdFoto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `IdMensaje` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paises`
--
ALTER TABLE `paises`
  MODIFY `IdPais` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `IdSolicitud` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tiposanuncios`
--
ALTER TABLE `tiposanuncios`
  MODIFY `IdTAnuncio` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tiposmensajes`
--
ALTER TABLE `tiposmensajes`
  MODIFY `IdTMensaje` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tiposviviendas`
--
ALTER TABLE `tiposviviendas`
  MODIFY `IdTVivienda` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IdUsuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `anuncios`
--
ALTER TABLE `anuncios`
  ADD CONSTRAINT `FK_Anuncios_Paises` FOREIGN KEY (`Pais`) REFERENCES `paises` (`IdPais`),
  ADD CONSTRAINT `FK_Anuncios_TiposAnuncios` FOREIGN KEY (`TAnuncio`) REFERENCES `tiposanuncios` (`IdTAnuncio`),
  ADD CONSTRAINT `FK_Anuncios_TiposViviendas` FOREIGN KEY (`TVivienda`) REFERENCES `tiposviviendas` (`IdTVivienda`),
  ADD CONSTRAINT `FK_Anuncios_Usuarios` FOREIGN KEY (`Usuario`) REFERENCES `usuarios` (`IdUsuario`);

--
-- Filtros para la tabla `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `FK_Fotos_Anuncios` FOREIGN KEY (`Anuncio`) REFERENCES `anuncios` (`IdAnuncio`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `FK_Mensajes_Anuncio` FOREIGN KEY (`Anuncio`) REFERENCES `anuncios` (`IdAnuncio`),
  ADD CONSTRAINT `FK_Mensajes_TiposMensajes` FOREIGN KEY (`TMensaje`) REFERENCES `tiposmensajes` (`IdTMensaje`),
  ADD CONSTRAINT `FK_Mensajes_UsuDestino` FOREIGN KEY (`UsuDestino`) REFERENCES `usuarios` (`IdUsuario`),
  ADD CONSTRAINT `FK_Mensajes_UsuOrigen` FOREIGN KEY (`UsuOrigen`) REFERENCES `usuarios` (`IdUsuario`);

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `FK_Solicitudes_Anuncio` FOREIGN KEY (`Anuncio`) REFERENCES `anuncios` (`IdAnuncio`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `FK_Usuarios_Estilos` FOREIGN KEY (`Estilo`) REFERENCES `estilos` (`IdEstilo`),
  ADD CONSTRAINT `FK_Usuarios_Paises` FOREIGN KEY (`Pais`) REFERENCES `paises` (`IdPais`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
