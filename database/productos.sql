-- Crear base de datos
CREATE DATABASE IF NOT EXISTS clothing_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE clothing_store;

-- Crear tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    categoria VARCHAR(100),
    corte VARCHAR(50),
    color VARCHAR(50),
    tallas JSON,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio, imagen, categoria, corte, color, tallas) VALUES
('Camisa Negra Manga Larga Regular Fit Easy Iron', 
 'Camisa de tela con tratamiento de planchado fácil. Modelo con cuello inglés, tapeta clásica y canesú en la espalda. Mangas largas con perilla y botón, y cierre ajustable de botón en los puños. Bajo ligeramente redondeado. Corte regular que ofrece comodidad sin perder estilo.', 
 29.99, 
 'img/camisa-negra-regular-easy.webp', 
 'Camisas', 
 'Regular Fit',
 'Negro',
 '["XS", "S", "M", "L", "XL"]'),

('Camisa Negra Manga Larga Slim Fit Easy Iron', 
 'Camisa de tela con tratamiento de planchado fácil. Modelo con cuello inglés, tapeta clásica y canesú en la espalda. Mangas largas con perilla y botón, y cierre ajustable de botón en los puños. Bajo ligeramente redondeado. Corte ajustado que realza el contorno del cuerpo creando una silueta entallada.', 
 19.99, 
 'img/camisa-negra-slim-easy.webp', 
 'Camisas', 
 'Slim Fit',
 'Negro',
 '["XS", "S", "M", "L", "XL"]'),

('Camisa Celeste Manga Larga Slim Fit Easy Iron', 
 'Camisa de tela con tratamiento de planchado fácil. Modelo con cuello inglés, tapeta clásica y canesú en la espalda. Mangas largas con perilla y botón, y cierre ajustable de botón en los puños. Bajo ligeramente redondeado. Corte ajustado que realza el contorno del cuerpo creando una silueta entallada.', 
 19.99, 
 'img/camisa-celeste-slim-easy.webp', 
 'Camisas', 
 'Slim Fit',
 'Celeste',
 '["XS", "S", "M", "L", "XL"]'),

('Camisa Algodón Manga Larga Regular Fit', 
 'Camisa de algodón 100% transpirable y cómoda. Modelo con cuello inglés, tapeta clásica y canesú en la espalda. Mangas largas con perilla y botón, y cierre ajustable de botón en los puños. Bajo ligeramente redondeado. Corte regular que ofrece comodidad durante todo el día.', 
 34.99, 
 'img/camisa-slim-algodon.webp', 
 'Camisas', 
 'Regular Fit',
 'Blanco',
 '["XS", "S", "M", "L", "XL"]');
