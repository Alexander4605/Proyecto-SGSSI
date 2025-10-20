-- Crear la base de datos (ya se crea automáticamente por docker-compose)
USE videoclub_db;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni VARCHAR(10) UNIQUE NOT NULL,
    telefono VARCHAR(9) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de películas (elementos del sistema)
CREATE TABLE IF NOT EXISTS peliculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    director VARCHAR(100) NOT NULL,
    año INT NOT NULL,
    genero VARCHAR(50) NOT NULL,
    duracion INT NOT NULL, -- en minutos
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar algunos datos de ejemplo
INSERT INTO peliculas (titulo, director, año, genero, duracion) VALUES
('El Padrino', 'Francis Ford Coppola', 1972, 'Drama', 175),
('Pulp Fiction', 'Quentin Tarantino', 1994, 'Crimen', 154),
('El Señor de los Anillos: La Comunidad del Anillo', 'Peter Jackson', 2001, 'Fantasía', 178);
