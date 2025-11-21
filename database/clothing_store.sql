-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-11-2025 a las 12:42:57
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `clothing_store`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `corte` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `tallas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tallas`)),
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `categoria`, `corte`, `color`, `tallas`, `fecha_creacion`) VALUES
(1, 'Camisa Negra Manga Larga Regular Fit Easy Iron', 'Esta es nuestra camisa en negro con corte regular fit. Elaborada en Ecuador con tejido de algodón que combina comodidad y practicidad. El acabado easy care minimiza el mantenimiento. Presenta una silueta relajada perfecta para el día a día. Construcción clásica con costuras reforzadas. Este producto es apto para lavadora.', 79.00, 'img/camisa-negra-regular-easy.webp', 'Camisas', 'Regular Fit', 'Negro', '[\"XS\", \"S\", \"M\", \"L\", \"XL\"]', '2025-11-18 17:16:44'),
(2, 'Camisa Negra Manga Larga Slim Fit Easy Iron', 'Esta es nuestra camisa en negro con acabado easy care. Confeccionada en Ecuador utilizando algodón premium con tratamiento anti-arrugas. Diseñada con corte slim fit que define una línea limpia y moderna. Presenta detalles minimalistas y construcción duradera. Ideal para ocasiones formales o casuales. Este producto es apto para lavadora.', 85.00, 'img/camisa-negra-slim-easy.webp', 'Camisas', 'Slim Fit', 'Negro', '[\"XS\", \"S\", \"M\", \"L\", \"XL\"]', '2025-11-18 17:16:44'),
(3, 'Camisa Celeste Manga Larga Slim Fit Easy Iron', 'Esta es nuestra camisa en tonalidad celeste con acabado easy care. Elaborada en Ecuador con algodón de alta calidad que requiere mínimo planchado. El corte slim fit ofrece una silueta moderna y favorecedora. Presenta cuello clásico y cierre frontal con botones. Material resistente a las arrugas para uso diario. Este producto es apto para lavadora.', 85.00, 'img/camisa-celeste-slim-easy.webp', 'Camisas', 'Slim Fit', 'Celeste', '[\"XS\", \"S\", \"M\", \"L\", \"XL\"]', '2025-11-18 17:16:44'),
(4, 'Camisa Algodón Manga Larga Regular Fit', 'Esta es nuestra camisa clásica en algodón 100%. Confeccionada en Ecuador con acabados cuidadosamente elaborados. Diseñada con corte regular fit para una silueta contemporánea. Presenta costuras reforzadas y botones de resina natural. El tejido es suave al tacto y transpirable. Este producto es apto para lavadora.', 79.00, 'img/camisa-slim-algodon.webp', 'Camisas', 'Regular Fit', 'Blanco', '[\"XS\", \"S\", \"M\", \"L\", \"XL\"]', '2025-11-18 17:16:44'),
(5, 'Pantalón Denim Crudo Loose Fit', 'Este es nuestro pantalón de denim en tono crudo. Confeccionado en Ecuador con mezclilla 100% algodón que mejora con el uso. El corte loose fit ofrece comodidad y estilo contemporáneo. Presenta bolsillos delanteros curvados para un acabado más suave, con bolsillo de parche en la parte posterior. Este producto es apto para lavadora.', 95.00, 'img/pantalon-denim-crudo-loose-fit.webp', 'Pantalones', 'Loose Fit', 'Crudo', '[\"28\", \"30\", \"32\", \"34\", \"36\", \"38\"]', '2025-11-21 02:47:18'),
(6, 'Pantalón Denim Gris Loose Fit', 'Este es nuestro pantalón de denim en gris con corte loose fit. Confeccionado en Ecuador utilizando mezclilla premium con lavado artesanal. La silueta relajada refleja el diseño contemporáneo. Presenta construcción robusta con costuras de doble pespunte. Teñido en prenda para un acabado único. Este producto es apto para lavadora.', 95.00, 'img/pantalon-denim-gris-loose-fit.webp', 'Pantalones', 'Loose Fit', 'Gris', '[\"28\", \"30\", \"32\", \"34\", \"36\", \"38\"]', '2025-11-21 02:47:18'),
(7, 'Pantalón Mezcla Lino Relaxed Fit', 'Este es nuestro pantalón en negro fabricado con mezcla de 80% algodón y 20% lino. Elaborado en Ecuador con un tejido compacto excelente para todo el año. El corte relaxed fit proporciona libertad de movimiento. Presenta bolsillos laterales inclinados y cintura ajustable. Material transpirable ideal para climas cálidos. Este producto es apto para lavadora.', 99.00, 'img/pantalon-negro-mezcla-lino-relaxed-fit.webp', 'Pantalones', 'Relaxed Fit', 'Negro', '[\"28\", \"30\", \"32\", \"34\", \"36\", \"38\"]', '2025-11-21 02:47:18'),
(8, 'Pantalón de Traje Regular Fit', 'Este es nuestro pantalón de vestir con corte regular fit. Elaborado en Ecuador con tejido de lana premium que ofrece caída impecable. Presenta pliegues frontales y líneas limpias para un aspecto refinado. Diseño atemporal con acabados cuidadosos. Construcción que combina tradición artesanal con estética moderna. Este producto requiere limpieza en seco.', 125.00, 'img/pantalon-traje-regular-fit.webp', 'Pantalones', 'Regular Fit', 'Negro', '[\"28\", \"30\", \"32\", \"34\", \"36\", \"38\"]', '2025-11-21 02:47:18'),
(9, 'Loafers Negro Clásico', 'Estos son nuestros loafers en negro con diseño minimalista. Confeccionados en Ecuador con cuero suave y suela flexible. Presentan construcción sin cordones para facilitar el uso. Plantilla ergonómica que brinda comodidad durante todo el día. Acabado pulido con detalles discretos. Perfectos para un estilo smart casual contemporáneo.', 139.00, 'img/loafers-negro.webp', 'Calzado', 'Clásico', 'Negro', '[\"39\", \"40\", \"41\", \"42\", \"43\", \"44\"]', '2025-11-21 02:47:18'),
(10, 'Zapatos de Vestir', 'Estos son nuestros zapatos de vestir elaborados artesanalmente en Ecuador. Fabricados en cuero genuino con suela de cuero duradero. Presentan construcción tradicional con costuras visibles. Diseño minimalista que se adapta a ocasiones formales. Plantilla acolchada para mayor comodidad. Requieren cuidado regular con productos específicos para cuero.', 159.00, 'img/zapatos-vestir.webp', 'Calzado', 'Formal', 'Negro', '[\"39\", \"40\", \"41\", \"42\", \"43\", \"44\"]', '2025-11-21 02:47:18'),
(11, 'Bolso Cruzado Denim', 'Este es nuestro bolso cruzado en denim gris. Elaborado en Ecuador con mezclilla resistente y herrajes metálicos de acabado plateado. Presenta compartimento principal con cierre y bolsillos interiores organizadores. Correa ajustable para llevar cruzado. Diseño funcional que combina practicidad con estética urbana. El material es duradero y envejece naturalmente.', 69.00, 'img/bolso-cruzado-denim-gris.webp', 'Accesorios', 'Único', 'Gris', '[\"Única\"]', '2025-11-21 02:47:18'),
(12, 'Cinturón de Ante', 'Este es nuestro cinturón de ante con hebilla minimalista. Elaborado en Ecuador con cuero suave de textura afelpada. Presenta hebilla en acabado mate y construcción de una sola pieza. Ancho clásico de 3.5 cm apto para uso diario. El material desarrolla una pátina natural con el tiempo. Disponible en talla única ajustable.', 49.00, 'img/cinturon-ante.webp', 'Accesorios', 'Único', 'Marrón', '[\"S\", \"M\", \"L\", \"XL\"]', '2025-11-21 02:47:18'),
(13, 'Gorra Blanca Sarga', 'Esta es nuestra gorra en sarga de algodón color blanco. Confeccionada en Ecuador con tejido resistente de alta densidad. Presenta panel frontal estructurado y visera curva. Cierre ajustable en la parte posterior para adaptarse a diferentes tallas. Diseño limpio y versátil. Este producto es apto para lavadora.', 35.00, 'img/gorra-blanca-sarga.webp', 'Accesorios', 'Único', 'Blanco', '[\"Única\"]', '2025-11-21 02:47:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `telefono`, `direccion`, `fecha_registro`, `ultimo_acceso`) VALUES
(1, 'Usuario Demo', 'demo@clothingstore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, '2025-11-21 10:43:19', NULL),
(2, 'Maholi Fernandez', 'demo@admin.com', '$2y$10$kOV6NR.vYCcA1SrUbvh5Begq..tOgbMZbacYbCAInsdgYnOcjkCbe', NULL, NULL, '2025-11-21 10:50:53', '2025-11-21 10:51:07');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
