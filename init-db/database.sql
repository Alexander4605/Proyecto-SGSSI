-- Borra las tablas si ya existen, para empezar de cero
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `usuarios`;

--
-- Estructura de tabla para la tabla `usuarios`
-- Cumple con los requisitos del enunciado
--
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `apellidos` text NOT NULL,
  `dni` varchar(10) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL, -- Necesario para el login
  PRIMARY KEY (`id`),
  UNIQUE KEY `dni` (`dni`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `reviews`
-- Estos son tus "elementos", cada uno con 5 campos de datos
--
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo_pelicula` varchar(255) NOT NULL,
  `director` varchar(255) NOT NULL,
  `ano_estreno` int(4) NOT NULL,
  `puntuacion` int(2) NOT NULL,
  `texto_review` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;