-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-05-2025 a las 19:59:46
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
-- Base de datos: `refugio-animales`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `animales`
--

CREATE TABLE `animales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `edad` varchar(255) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `estado` varchar(250) NOT NULL,
  `joined` timestamp NOT NULL DEFAULT current_timestamp(),
  `especie_id` int(11) DEFAULT NULL,
  `raza_id` int(11) DEFAULT NULL,
  `vet_data_id` int(11) NOT NULL,
  `imagen_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `animales`
--

INSERT INTO `animales` (`id`, `nombre`, `edad`, `genero`, `estado`, `joined`, `especie_id`, `raza_id`, `vet_data_id`, `imagen_id`) VALUES
(23, 'Warrior', 'Cachorro (6 meses)', 'Macho', 'Disponible', '2025-04-15 07:18:11', 1, 3, 5, 28),
(24, 'Jinx', 'Joven (2 a&ntilde;os y 1 mes)', 'Hembra', 'Adoptado', '2025-04-15 08:02:39', 2, 2, 6, 29),
(25, 'Apicho', 'Adulto (2 a&ntilde;os)', 'Macho', 'Disponible', '2025-04-16 07:28:54', 6, 4, 7, 37),
(26, 'Marlino', 'Cachorro (5 meses)', 'Macho', 'Disponible', '2025-04-26 08:19:01', 1, 5, 8, 31),
(28, 'Phynx', 'Joven (3 a&ntilde;os)', 'Hembra', 'En proceso', '2025-04-27 10:39:23', 2, 6, 10, 33),
(33, 'Lola', 'Adulto (5 años)', 'Hembra', 'Disponible', '2025-05-02 08:13:44', 1, 7, 15, 42),
(34, 'Gaty', 'Adulto (8 a&ntilde;os)', 'Macho', 'En proceso', '2025-05-14 17:54:22', 2, 8, 16, 43);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especies`
--

CREATE TABLE `especies` (
  `id` int(11) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `descripcion` varchar(254) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especies`
--

INSERT INTO `especies` (`id`, `especie`, `descripcion`) VALUES
(1, 'Perro', 'Los mejores amigos de los hombres, leales y espero que tengas ganas de salir a pasear.'),
(2, 'Gato', 'Animales muy suyos pero con un gran cariño por sus dueños, recomendación de cogerlos con una armadura de malla.'),
(3, 'Ave', 'Pequeños terremotos que inundarán tu casa de canciones todos los días, compra tapones.'),
(4, 'Conejo', 'Pequeña máquina de hacer bolitas marrones y espero que sepas correr porque como salga de su cerca tienes un probema.'),
(5, 'Hurón', 'Los tapones para la nariz son recomendados y mucho tiempo libre para cansar a estos pequeñajos.'),
(6, 'Mapache', 'Mamífero omnívoro nocturno con anillos en la cola, cuidado con tu cartera (o con tu basura).');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes`
--

INSERT INTO `imagenes` (`id`, `imagen`, `alt`) VALUES
(28, 'warrior_67fe083363e5d.jpg', 'Perro: Labrador'),
(29, 'jinx_67fe129f8b274.jpg', 'Gato: Azul Ruso'),
(31, 'marlinito_680c96f57bbf7.jpg', 'Perro: Pug'),
(33, 'phynx_680e095bdf573.jpg', 'Gato: Esfinge'),
(37, 'apicho_681335dcd7503.jpg', 'Apicho'),
(42, 'lola_68147eb8c0274.jpg', 'Perro: Yorkshire'),
(43, 'gaty_6824d8ced0dc1.jpg', 'Gato: Naranja Comun');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `raza`
--

CREATE TABLE `raza` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `especie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `raza`
--

INSERT INTO `raza` (`id`, `nombre`, `especie_id`) VALUES
(1, 'Calico', 2),
(2, 'Azul Ruso', 2),
(3, 'Beagle', 1),
(4, 'Com&uacute;n Silvestre', 6),
(5, 'Pug', 1),
(6, 'Esfinge', 2),
(7, 'Yorkshire', 1),
(8, 'Naranja Comun', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vet_data`
--

CREATE TABLE `vet_data` (
  `id` int(11) NOT NULL,
  `microchip` tinyint(1) DEFAULT NULL,
  `castracion` tinyint(1) DEFAULT NULL,
  `vacunas` varchar(250) DEFAULT NULL,
  `info_adicional` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vet_data`
--

INSERT INTO `vet_data` (`id`, `microchip`, `castracion`, `vacunas`, `info_adicional`) VALUES
(5, 1, 0, 'N/E', 'N/E'),
(6, 1, 0, 'Rabia', 'N/E'),
(7, 1, 1, 'N/E', 'Es un clept&oacute;mano'),
(8, 0, 0, 'N/E', 'N/E'),
(10, 1, 1, 'Trivalente', 'Problemas de piel leves.'),
(15, 1, 0, 'N/E', 'N/E'),
(16, 1, 1, 'Todas', 'Obesidad');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `animales`
--
ALTER TABLE `animales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_animales_especie` (`especie_id`),
  ADD KEY `fk_animales_imagen` (`imagen_id`),
  ADD KEY `fk_animales_raza` (`raza_id`),
  ADD KEY `fk_animales_vet_data` (`vet_data_id`);

--
-- Indices de la tabla `especies`
--
ALTER TABLE `especies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `especie` (`especie`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `raza`
--
ALTER TABLE `raza`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raza_especie_id` (`especie_id`);

--
-- Indices de la tabla `vet_data`
--
ALTER TABLE `vet_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `animales`
--
ALTER TABLE `animales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `especies`
--
ALTER TABLE `especies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `raza`
--
ALTER TABLE `raza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `vet_data`
--
ALTER TABLE `vet_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `animales`
--
ALTER TABLE `animales`
  ADD CONSTRAINT `fk_animales_especie` FOREIGN KEY (`especie_id`) REFERENCES `especies` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_animales_imagen` FOREIGN KEY (`imagen_id`) REFERENCES `imagenes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_animales_raza` FOREIGN KEY (`raza_id`) REFERENCES `raza` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_animales_vet_data` FOREIGN KEY (`vet_data_id`) REFERENCES `vet_data` (`id`);

--
-- Filtros para la tabla `raza`
--
ALTER TABLE `raza`
  ADD CONSTRAINT `raza_especie_id` FOREIGN KEY (`especie_id`) REFERENCES `especies` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
