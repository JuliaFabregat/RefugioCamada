-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2025 a las 13:52:57
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
(24, 'Jinx', 'Joven (2 a&ntilde;os y 1 mes)', 'Hembra', 'Disponible', '2025-04-15 08:02:39', 2, 2, 6, 29),
(25, 'Apiche', 'Adulto (3 a&ntilde;os)', 'Macho', 'Disponible', '2025-04-16 07:28:54', 6, 4, 7, 37),
(26, 'Marlino', 'Cachorro (5 meses)', 'Macho', 'Disponible', '2025-04-26 08:19:01', 1, 5, 8, 31),
(28, 'Phynx', 'Joven (3 a&ntilde;os)', 'Hembra', 'Adoptado', '2025-04-27 10:39:23', 2, 6, 10, 33),
(33, 'Lola', 'Adulto (5 años)', 'Hembra', 'Adoptado', '2025-05-02 08:13:44', 1, 7, 15, 42),
(40, 'Kenobii', 'Joven (3 a&ntilde;os)', 'Macho', 'Disponible', '2025-05-17 12:14:29', 2, 9, 22, 52),
(41, 'Medivh', 'Joven (4 a&ntilde;os)', 'Macho', 'Disponible', '2025-05-25 09:00:43', 2, 10, 23, 53),
(44, 'Gaty', 'Adulto (8 años)', 'Macho', 'Disponible', '2025-05-30 11:33:17', 2, 8, 26, 56);

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
(52, 'kenoby_68287da5226c8.jpeg', 'Gato: Atigrado'),
(53, 'medivh_6832dc3b96baf.jpeg', 'Gato: Bombay'),
(56, 'gaty_6839977dc75ac.jpg', 'Gato: Naranja Comun');

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
(8, 'Naranja Comun', 2),
(9, 'Atigrado', 2),
(10, 'Bombay', 2),
(11, 'Labrador', 1),
(12, 'Canario', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_adopcion`
--

CREATE TABLE `solicitudes_adopcion` (
  `id` int(11) NOT NULL,
  `id_animal` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `resolucion` enum('En proceso','Aceptada','Denegada') NOT NULL DEFAULT 'En proceso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `solicitudes_adopcion`
--

INSERT INTO `solicitudes_adopcion` (`id`, `id_animal`, `id_usuario`, `fecha`, `resolucion`) VALUES
(2, 26, 2, '2025-05-16 13:48:47', 'Denegada'),
(3, 28, 2, '2025-05-16 14:05:33', 'Aceptada'),
(4, 33, 2, '2025-05-25 11:59:15', 'Aceptada'),
(5, 40, 2, '2025-05-26 19:47:30', 'Denegada'),
(7, 24, 2, '2025-05-28 19:29:55', 'Denegada'),
(8, 40, 4, '2025-05-30 13:26:58', 'En proceso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `password`, `admin`, `created_at`) VALUES
(1, 'Administrador', 'Camada', 'admin@refugiocamada.org', '$2y$10$ekquoB2ZfRjq1PW7LlL6eOA3XIgHjpMtARAVSsdxVqyh3kxvXzk8e', 1, '2025-05-16 12:13:18'),
(2, 'Alfonso', 'Reverte', 'alfonso@gmail.com', '$2y$10$Xz.akyBhgPEWZBjt4QdveeNpdp9CYVLl4l8E7s8o4XPU9GPWf1A56', 0, '2025-05-16 13:31:26'),
(3, 'Julia', 'Perez', 'fabregatjulia99@gmail.com', '$2y$10$rE9r8MLcRbwT6ZkiaZmi2uG2tgZ8LF0kQYz3LHZ9FVP31bfddxNJy', 0, '2025-05-28 18:48:02'),
(4, 'Ana', 'Ecija', 'ana@gmail.com', '$2y$10$Ey.HS5hgRq.9CZqmAGAzjOpztFDb8xqt437Bc9HcXYe5TBsuLiUgK', 0, '2025-05-28 19:10:13');

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
(22, 1, 1, 'Todas', 'La fuerza le acompa&ntilde;a'),
(23, 1, 1, 'Todas', 'Es mago'),
(26, 1, 1, 'Todas', 'Un poco rechonchito <3');

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
-- Indices de la tabla `solicitudes_adopcion`
--
ALTER TABLE `solicitudes_adopcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_animal` (`id_animal`),
  ADD KEY `idx_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `especies`
--
ALTER TABLE `especies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `raza`
--
ALTER TABLE `raza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `solicitudes_adopcion`
--
ALTER TABLE `solicitudes_adopcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `vet_data`
--
ALTER TABLE `vet_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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

--
-- Filtros para la tabla `solicitudes_adopcion`
--
ALTER TABLE `solicitudes_adopcion`
  ADD CONSTRAINT `fk_solicitudes_animal` FOREIGN KEY (`id_animal`) REFERENCES `animales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_solicitudes_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
