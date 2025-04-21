-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-03-2025 a las 16:47:42
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
  `nombre` varchar(100) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `raza` varchar(255) DEFAULT NULL,
  `edad` varchar(255) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `joined` timestamp NOT NULL DEFAULT current_timestamp(),
  `especie_id` int(11) DEFAULT NULL,
  `imagen_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `animales`
--

INSERT INTO `animales` (`id`, `nombre`, `especie`, `raza`, `edad`, `genero`, `joined`, `especie_id`, `imagen_id`) VALUES
(1, 'Algodón', 'Gato', 'Siamés', '1 año', 'Macho', '2025-03-11 08:36:49', 2, 1),
(2, 'Apicho', 'Mapache', 'Común', '2 años', 'Macho', '2025-03-11 08:36:49', 6, 2),
(3, 'Lola', 'Perro', 'Yorkshire Terrier', '4 años', 'Hembra', '2025-03-11 08:36:49', 1, 3),
(5, 'Marlino', 'Perro', 'Pug', '11 meses', 'Macho', '2025-03-11 08:36:49', 1, 5),
(6, 'Luisito', 'Gato', 'Naranja Comun', '3 meses', 'Macho', '2025-03-11 11:59:36', 2, 10),
(10, 'Phynx', 'Gato', 'Esfinge', '1 año', 'Macho', '2025-03-11 13:25:34', 2, 14),
(13, 'Warrior', 'Perro', 'Beagle', '2 años', 'Macho', '2025-03-11 14:25:26', 1, 17);

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
(1, 'algodon.jpg', 'Gato: Siamés.'),
(2, 'apicho.jpg', 'Mapache'),
(3, 'lola.jpg', 'Perro: Yorkshire Terrier'),
(5, 'marlino.jpg', 'Perro: Pug'),
(10, 'luis.jpg', 'Gato: Común Naranja'),
(14, 'phynx.jpg', 'Gato: Esfinge gris'),
(17, 'warrior.jpg', 'Perro: Beagle');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `animales`
--
ALTER TABLE `animales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `especie_id` (`especie_id`),
  ADD KEY `imagen_id` (`imagen_id`);

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `animales`
--
ALTER TABLE `animales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `especies`
--
ALTER TABLE `especies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `animales`
--
ALTER TABLE `animales`
  ADD CONSTRAINT `animales_ibfk_1` FOREIGN KEY (`especie_id`) REFERENCES `especies` (`id`),
  ADD CONSTRAINT `animales_ibfk_2` FOREIGN KEY (`imagen_id`) REFERENCES `imagenes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
