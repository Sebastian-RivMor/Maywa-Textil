-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-10-2025 a las 04:06:39
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
-- Base de datos: `bd_maywa1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otp_resets`
--

CREATE TABLE `otp_resets` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `used_at` datetime DEFAULT NULL,
  `invalidated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id`, `id_usuario`, `token`, `expires_at`, `created_at`, `used_at`, `invalidated_at`) VALUES
(1, 2, '5039fe4fd893e0cdd7d4d15046a87ece29f426ca3b76664ce79953e600d66f0d', '2025-10-06 08:40:39', '2025-10-06 01:30:39', '2025-10-06 01:32:15', NULL),
(2, 2, '63b8b6a4bfeafa1a9e6118ff46736ff67c72286d864aedbfaa00febe13026bbd', '2025-10-06 08:42:48', '2025-10-06 01:32:48', '2025-10-06 01:33:24', NULL),
(3, 2, 'a7cc9083baf243ec6010e932bf4ebb3593837656c6fce02fa43a6c8dbbe267fe', '2025-10-06 08:46:14', '2025-10-06 01:36:14', '2025-10-06 01:37:04', NULL),
(4, 2, 'd2bc96a439ba386da1e3628d7d07aadb40bd297a2a01549a7a6b2676cbc8f569', '2025-10-06 08:58:20', '2025-10-06 01:48:20', '2025-10-06 01:48:47', NULL),
(5, 2, 'c6ba6b9c65eb2f69f064abb950f1354481d449da8719ceca49277f4e7d799bb4', '2025-10-06 09:04:08', '2025-10-06 01:54:08', NULL, '2025-10-06 01:54:50'),
(6, 2, '8de7487119f7f8ab917e5fff4c78abcd4bf8113a7ed1b417c0941e3d1b6e0950', '2025-10-06 09:04:50', '2025-10-06 01:54:50', NULL, '2025-10-06 01:55:49'),
(7, 2, 'bc56f6bd1dfb1014b406c6739058359b04e9bbf93eb59f3869c4108fb4597a97', '2025-10-06 09:05:49', '2025-10-06 01:55:49', '2025-10-06 01:56:18', NULL),
(8, 10, '5ec03a2dccfc17880b700ce8747362a2e030d718a9c4f7ad16194a9a804764a7', '2025-10-06 18:36:08', '2025-10-06 11:26:08', '2025-10-06 11:27:36', NULL),
(9, 10, '0c734ddb3aa2bb935ea9e02fadf6dadc30a602dd6d23fddf3db2af55e09dbe94', '2025-10-06 18:41:39', '2025-10-06 11:31:39', NULL, '2025-10-22 15:44:42'),
(10, 10, '0a35114dacdb7cd79abdec2553ccd38c8514279b303b3f27b485d336302ff7e5', '2025-10-22 22:54:42', '2025-10-22 15:44:42', '2025-10-22 15:45:30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_categoria`
--

CREATE TABLE `tb_categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_categoria`
--

INSERT INTO `tb_categoria` (`id_categoria`, `nombre_categoria`) VALUES
(1, 'Ponchos'),
(2, 'Chompas y Suéteres'),
(3, 'Chalecos'),
(4, 'Faldas'),
(5, 'Bufandas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_comunidad`
--

CREATE TABLE `tb_comunidad` (
  `id_comunidad` int(11) NOT NULL,
  `nombre_comunidad` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_comunidad`
--

INSERT INTO `tb_comunidad` (`id_comunidad`, `nombre_comunidad`, `descripcion`, `fecha_creacion`) VALUES
(1, 'Asociación de Mujeres Artesanas Makyss', 'Colectivo de mujeres artesanas dedicadas al tejido tradicional.', '2025-10-06 04:20:56'),
(2, 'Comunidad Campesina de Pilpichaca', 'Productores textiles con fibras naturales de altura.', '2025-10-06 04:20:56'),
(3, 'Nación Chopcca', 'Comunidad reconocida por sus técnicas textiles ancestrales.', '2025-10-06 04:20:56'),
(4, 'Comunidad de Ambato (talleres locales)', 'Red de talleres locales con producción artesanal.', '2025-10-06 04:20:56'),
(5, 'Asociación de Mujeres Artesanas San Martín de Tantaccato (Qori Qaytu)', 'Asociación especializada en tejido fino y tintes naturales.', '2025-10-06 04:20:56'),
(6, 'Asociación Centro de Artesanía Pacha', 'Centro de artesanía con enfoque en comercio justo.', '2025-10-06 04:20:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_departamento`
--

CREATE TABLE `tb_departamento` (
  `id_departamento` int(11) NOT NULL,
  `nombre_departamento` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_departamento`
--

INSERT INTO `tb_departamento` (`id_departamento`, `nombre_departamento`) VALUES
(1, 'Amazonas'),
(2, 'Áncash'),
(3, 'Apurímac'),
(4, 'Arequipa'),
(5, 'Ayacucho'),
(6, 'Cajamarca'),
(7, 'Callao'),
(8, 'Cusco'),
(9, 'Huancavelica'),
(10, 'Huánuco'),
(11, 'Ica'),
(12, 'Junín'),
(13, 'La Libertad'),
(14, 'Lambayeque'),
(15, 'Lima'),
(16, 'Loreto'),
(17, 'Madre de Dios'),
(18, 'Moquegua'),
(19, 'Pasco'),
(20, 'Piura'),
(21, 'Puno'),
(22, 'San Martín'),
(23, 'Tacna'),
(24, 'Tumbes'),
(25, 'Ucayali');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_detalle_pedido`
--

CREATE TABLE `tb_detalle_pedido` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_detalle_pedido`
--

INSERT INTO `tb_detalle_pedido` (`id_detalle`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(3, 5, 13, 5, 780.00),
(4, 6, 13, 1, 780.00),
(5, 6, 11, 1, 450.00),
(6, 7, 13, 4, 780.00),
(7, 8, 13, 1, 780.00),
(8, 8, 11, 1, 450.00),
(9, 8, 6, 1, 200.00),
(10, 9, 10, 1, 275.00),
(11, 10, 12, 2, 350.00),
(12, 11, 13, 1, 780.00),
(13, 11, 11, 1, 450.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_envio`
--

CREATE TABLE `tb_envio` (
  `id_envio` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `direccion_envio` varchar(255) NOT NULL,
  `estado_envio` enum('pendiente','enviado','entregado','fallido') DEFAULT 'pendiente',
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_envio`
--

INSERT INTO `tb_envio` (`id_envio`, `id_pedido`, `direccion_envio`, `estado_envio`, `fecha_envio`) VALUES
(2, 5, 'CALLE 22 N 197 INDEPENDENCIA', 'pendiente', '2025-10-22 19:31:34'),
(3, 6, 'Los livos', 'pendiente', '2025-10-22 19:33:58'),
(4, 7, 'CALLE 22 N 15454567 INDEPENDENCIA', 'entregado', '2025-10-22 19:55:41'),
(5, 8, 'Lima 234', 'entregado', '2025-10-22 20:47:51'),
(6, 9, 'Machhu picchu', 'pendiente', '2025-10-22 20:59:29'),
(7, 10, 'Av Javier Prado 345. Este', 'pendiente', '2025-10-22 23:50:55'),
(8, 11, 'Av.Los olivos', 'entregado', '2025-10-23 01:02:25');

--
-- Disparadores `tb_envio`
--
DELIMITER $$
CREATE TRIGGER `trg_auto_direccion_envio` BEFORE INSERT ON `tb_envio` FOR EACH ROW BEGIN
  DECLARE v_direccion VARCHAR(255);
  
  -- Obtener la dirección del pedido asociado
  SELECT direccion_entrega INTO v_direccion
  FROM tb_pedido
  WHERE id_pedido = NEW.id_pedido;
  
  -- Copiar la dirección al nuevo envío
  SET NEW.direccion_envio = v_direccion;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_material`
--

CREATE TABLE `tb_material` (
  `id_material` int(11) NOT NULL,
  `nombre_material` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_material`
--

INSERT INTO `tb_material` (`id_material`, `nombre_material`) VALUES
(1, 'Alpaca'),
(4, 'Alpaca-Oveja'),
(2, 'Baby Alpaca'),
(3, 'Lana de oveja');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_pago`
--

CREATE TABLE `tb_pago` (
  `id_pago` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `metodo_pago` enum('tarjeta de credito','tarjeta de debito') NOT NULL,
  `estado_pago` enum('pendiente','completado','fallido') DEFAULT 'pendiente',
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_pago`
--

INSERT INTO `tb_pago` (`id_pago`, `id_pedido`, `monto_pagado`, `metodo_pago`, `estado_pago`, `fecha_pago`) VALUES
(2, 5, 780.00, '', 'completado', '2025-10-22 19:31:34'),
(3, 6, 1230.00, '', 'completado', '2025-10-22 19:33:58'),
(4, 7, 3120.00, '', 'completado', '2025-10-22 19:55:41'),
(5, 8, 1430.00, '', 'completado', '2025-10-22 20:47:51'),
(6, 9, 275.00, '', 'completado', '2025-10-22 20:59:29'),
(7, 10, 700.00, '', 'completado', '2025-10-22 23:50:55'),
(8, 11, 1230.00, '', 'completado', '2025-10-23 01:02:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_pedido`
--

CREATE TABLE `tb_pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado_pedido` enum('pendiente','procesando','enviado','entregado','cancelado') DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL,
  `direccion_entrega` varchar(255) NOT NULL,
  `id_departamento_envio` int(11) NOT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_pedido`
--

INSERT INTO `tb_pedido` (`id_pedido`, `id_usuario`, `estado_pedido`, `total`, `direccion_entrega`, `id_departamento_envio`, `fecha_pedido`) VALUES
(5, 10, 'pendiente', 780.00, 'CALLE 22 N 197 INDEPENDENCIA', 8, '2025-10-22 19:31:34'),
(6, 10, 'pendiente', 1230.00, 'Los livos', 13, '2025-10-22 19:33:58'),
(7, 10, 'enviado', 3120.00, 'CALLE 22 N 15454567 INDEPENDENCIA', 22, '2025-10-22 19:55:41'),
(8, 10, 'enviado', 1430.00, 'Lima 234', 13, '2025-10-22 20:47:51'),
(9, 10, 'cancelado', 275.00, 'Machhu picchu', 8, '2025-10-22 20:59:29'),
(10, 10, 'pendiente', 700.00, 'Av Javier Prado 345. Este', 15, '2025-10-22 23:50:55'),
(11, 10, 'enviado', 1230.00, 'Av.Los olivos', 7, '2025-10-23 01:02:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_persona`
--

CREATE TABLE `tb_persona` (
  `id_persona` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dni` char(8) NOT NULL,
  `telefono` char(9) DEFAULT NULL,
  `sexo` enum('Masculino','Femenino','Prefiero no decirlo') DEFAULT 'Prefiero no decirlo',
  `id_departamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_persona`
--

INSERT INTO `tb_persona` (`id_persona`, `nombre`, `apellido`, `dni`, `telefono`, `sexo`, `id_departamento`) VALUES
(1, 'MERLY', 'ANGULO', '76369555', '939285569', 'Femenino', 15),
(2, 'Merly Andrea', 'Angulo', '76369561', '948426325', 'Femenino', 15),
(3, 'ELI', 'ANGULO', '87234567', '961742780', 'Femenino', 15),
(4, 'asdjnfds', 'sdfgvbfds', '45677899', '455435467', 'Masculino', 18),
(5, 'jassdi', 'vasques', '34567543', '234565432', 'Masculino', 12),
(6, 'sgjnhofpldsókgjng', 'efdgbgnbgfbdesdbg', '34567865', '099495435', 'Femenino', 17),
(7, 'fgf', 'ANDY', '98888998', '989898989', 'Femenino', 19),
(8, 'sdfghfdfgffgrtet4rt', 'ythtyyhggtght', '39999999', '384394853', 'Femenino', 17),
(9, 'sdvfgbthytytty', 'fgbggb', '57887788', '354356435', 'Masculino', 14),
(10, 'ANGEL', 'MORENO', '72426043', '972614314', 'Masculino', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_producto`
--

CREATE TABLE `tb_producto` (
  `id_producto` int(11) NOT NULL,
  `id_comunidad` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `id_material` int(11) DEFAULT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `descripcion_producto` text NOT NULL,
  `descripcion_corta` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `foto_url` text NOT NULL,
  `estado_producto` enum('Disponible','Agotado') DEFAULT 'Disponible',
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_producto`
--

INSERT INTO `tb_producto` (`id_producto`, `id_comunidad`, `id_categoria`, `id_material`, `nombre_producto`, `descripcion_producto`, `descripcion_corta`, `precio`, `stock`, `foto_url`, `estado_producto`, `fecha_subida`) VALUES
(2, 1, 1, 1, 'Poncho Alpaca', '¡Descubre la esencia de los Andes con nuestros exquisitos ponchos!\r\nCada uno de nuestros ponchos es una obra maestra, tejida con amor y dedicación por talentosas tejedoras de Comunidades Altoandinas de Cusco, Perú.\r\n\r\nNuestros ponchos son más que prendas de vestir; son expresiones de arte y de identidad. Cada color vibrante que adorna estas piezas es obtenido con tintes naturales, que respeta tanto la artesanía ancestral como el medio ambiente.Al elegir uno de nuestros ponchos, estás optando por la autenticidad y la sostenibilidad.\r\n\r\nCada pieza es única en diseño y detalle, reflejando la individualidad y la creatividad de las manos que las crearon. Cada poncho cuenta una historia de tradición y artesanía que perdura en el tiempo.\r\nSumérgete en la riqueza cultural de los Andes con nuestros ponchos de alpaca, y lleva contigo una parte del encanto y la magia de esta región montañosa.\r\n\r\nViste con orgullo una prenda que te conecta con una historia milenaria.*Las medidas pueden variar +/- 2 cm*', '100% Hecho a mano - 100% oveja y 100% de alpaca', 152.00, 10, '/MAYWATEXTIL/public/assets/uploads/productos/1759770480_poncho.png', 'Disponible', '2025-10-06 17:08:00'),
(3, 6, 3, 1, 'Chaleco-Alpaca', 'Chaleco tejido artesanalmente con fibra de alpaca de alta calidad', 'Chaleco cálido de alpaca pura', 520.00, 15, '/MAYWATEXTIL/public/assets/uploads/productos/1759779228_chaleco_alpaca_1.png', 'Disponible', '2025-10-06 19:33:48'),
(4, 6, 2, 3, 'Chaleco-Oveja', 'Chaleco confeccionado con lana de oveja, ideal para climas fríos', 'Chaleco de lana de oveja natural', 315.00, 20, '/MAYWATEXTIL/public/assets/uploads/productos/1759779411_chaleco_oveja_1.png', 'Disponible', '2025-10-06 19:36:51'),
(5, 2, 5, 1, 'chalina-adoquines', 'Chalina con diseño de adoquines en fibra de alpaca', 'Chalina con patrón adoquín en alpaca', 200.00, 25, '/MAYWATEXTIL/public/assets/uploads/productos/1759779552_chalina_alpaca_1.png', 'Disponible', '2025-10-06 19:39:12'),
(6, 2, 5, 1, 'chalina-andina', 'Chalina con motivos andinos tradicionales en alpaca', 'Chalina andina en alpaca pura', 200.00, 18, '/MAYWATEXTIL/public/assets/uploads/productos/1759779657_chalina_alpaca_2.png', 'Disponible', '2025-10-06 19:40:57'),
(7, 3, 2, 4, 'Chompa-50%-alpaca-50%_oveja-Negra', 'Chompa negra mezcla 50% alpaca y 50% lana de oveja', 'Chompa negra alpaca-oveja', 420.00, 12, '/MAYWATEXTIL/public/assets/uploads/productos/1759779756_chompa_alpaca_oveja_2.png', 'Disponible', '2025-10-06 19:42:36'),
(8, 3, 2, 4, 'Chompa-50%-alpaca50%_ovejaBlancoPerla', 'Chompa color blanco perla mezcla alpaca y oveja', 'Chompa blanco perla alpaca-oveja', 420.00, 10, '/MAYWATEXTIL/public/assets/uploads/productos/1759779830_chompa_alpaca_oveja_1.png', 'Disponible', '2025-10-06 19:43:50'),
(9, 4, 4, 1, 'Falda-Alpaca', 'Falda elegante tejida en fibra de alpaca con diseños tradicionales', 'Falda larga de alpaca', 395.00, 8, '/MAYWATEXTIL/public/assets/uploads/productos/1759779984_falda_alpaca_1.png', 'Disponible', '2025-10-06 19:46:24'),
(10, 4, 4, 3, 'Falda-lana-oveja', 'Falda cálida confeccionada con lana de oveja natural', 'Falda de lana de oveja', 275.00, 15, '/MAYWATEXTIL/public/assets/uploads/productos/1759780101_falda_oveja_1.png', 'Disponible', '2025-10-06 19:48:21'),
(11, 5, 1, 1, 'Poncho-Alpaca', 'Poncho tradicional peruano en fibra de alpaca de primera calidad', 'Poncho tradicional de alpaca', 450.00, 12, '/MAYWATEXTIL/public/assets/uploads/productos/1759780176_poncho_alpaca_1.png', 'Disponible', '2025-10-06 19:49:36'),
(12, 5, 1, 3, 'Poncho-oveja', 'Poncho abrigador hecho con lana de oveja, perfecto para el frío', 'Poncho de lana de oveja', 350.00, 10, '/MAYWATEXTIL/public/assets/uploads/productos/1759780276_poncho_oveja_1.png', 'Disponible', '2025-10-06 19:51:16'),
(13, 6, 2, 2, 'Sueter baby alpaca', 'Suéter suave y ligero confeccionado con baby alpaca premium', 'Suéter de baby alpaca extra suave', 780.00, 15, '/MAYWATEXTIL/public/assets/uploads/productos/1759780361_sueter_baby_alpaca_1.png', 'Disponible', '2025-10-06 19:52:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_rol`
--

CREATE TABLE `tb_rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_rol`
--

INSERT INTO `tb_rol` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador'),
(2, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `estado_usuario` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_usuario`
--

INSERT INTO `tb_usuario` (`id_usuario`, `id_persona`, `id_rol`, `correo`, `contrasena`, `estado_usuario`, `fecha_registro`) VALUES
(1, 1, 1, 'admin_mer@maywa.com', '$2y$10$GB9XktXKiFpHIHTlzic5Gu2uQ4fJb0oMI6jHFMax5GfcJEtDluXV.', 'Activo', '2025-10-06 00:15:00'),
(2, 2, 2, 'andrea.angulo82004@gmail.com', '$2y$10$9gxCW4iX0IBB91jBVrahxOyd1yKKj8.q2c6T6QwXLSLPzVntluizW', 'Activo', '2025-10-06 02:38:37'),
(3, 3, 2, 'eli.angulo72004@gmail.com', '$2y$10$QLydlXms4dYRpSp64pPoZOJfcAKweIPoWtzDMStcVGNFucDGTfjn6', 'Activo', '2025-10-06 02:50:14'),
(4, 4, 2, 'hola@gmail.com', '$2y$10$9Dlz.uVqGCoTppkHwZ0FEe5ZPQ7DFV1Eizk4Ra43gMwmWD7CSBKpm', 'Activo', '2025-10-06 03:00:58'),
(5, 5, 2, 'jassid@gmail.com', '$2y$10$.4FXgWKg7utxJfFyzQ2R7uwFXQm/tF/hpw2k4J97q0wZnGPBJRhy2', 'Activo', '2025-10-06 03:03:10'),
(6, 6, 2, 'que@gmail.com', '$2y$10$cPhkqMB6SyDfV.Hr4xgy3uV.Qs65FGKCaK6Ur8wKx542OIsNNXGXK', 'Activo', '2025-10-06 03:07:17'),
(7, 7, 2, 'eyeyeyey@gmail.com', '$2y$10$g7cRe5pA5wmgKiTBwstEZeCQTsxO5dpaBluovl3EPTZom9RTja6vi', 'Activo', '2025-10-06 03:10:00'),
(8, 8, 2, 'ayyyyyyyyyyy@gmail.com', '$2y$10$KlLFUU5QoOW8HwetA3kdx.O3oXP.JsOUFOVCHF32qhasw1NoWR7ru', 'Activo', '2025-10-06 03:14:08'),
(9, 9, 2, 'svsvs@gmail.com', '$2y$10$P6VU8vm/nibunVIA5/VO2OnmRlh1wzU3Agpr2XXrNoZduFL7YUtRa', 'Activo', '2025-10-06 03:25:26'),
(10, 10, 2, 'sebas.rivmor@hotmail.com', '$2y$10$bjXoOrsiH9RC9QlKym2xduINFQlwhkOKPJfqq8MxUSSj6ZdK1LzWu', 'Activo', '2025-10-06 16:25:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `otp_resets`
--
ALTER TABLE `otp_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_token` (`token`),
  ADD KEY `idx_id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tb_categoria`
--
ALTER TABLE `tb_categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `tb_comunidad`
--
ALTER TABLE `tb_comunidad`
  ADD PRIMARY KEY (`id_comunidad`);

--
-- Indices de la tabla `tb_departamento`
--
ALTER TABLE `tb_departamento`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `tb_detalle_pedido`
--
ALTER TABLE `tb_detalle_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `tb_envio`
--
ALTER TABLE `tb_envio`
  ADD PRIMARY KEY (`id_envio`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `fk_envio_direccion` (`direccion_envio`);

--
-- Indices de la tabla `tb_material`
--
ALTER TABLE `tb_material`
  ADD PRIMARY KEY (`id_material`),
  ADD UNIQUE KEY `nombre_material` (`nombre_material`);

--
-- Indices de la tabla `tb_pago`
--
ALTER TABLE `tb_pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `tb_pedido`
--
ALTER TABLE `tb_pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD UNIQUE KEY `uq_direccion_entrega` (`direccion_entrega`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_departamento_envio` (`id_departamento_envio`);

--
-- Indices de la tabla `tb_persona`
--
ALTER TABLE `tb_persona`
  ADD PRIMARY KEY (`id_persona`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `telefono` (`telefono`),
  ADD KEY `id_departamento` (`id_departamento`);

--
-- Indices de la tabla `tb_producto`
--
ALTER TABLE `tb_producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `tb_producto_ibfk_1` (`id_comunidad`),
  ADD KEY `tb_producto_ibfk_2` (`id_categoria`),
  ADD KEY `tb_producto_ibfk_3` (`id_material`);

--
-- Indices de la tabla `tb_rol`
--
ALTER TABLE `tb_rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_persona` (`id_persona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `otp_resets`
--
ALTER TABLE `otp_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tb_categoria`
--
ALTER TABLE `tb_categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tb_comunidad`
--
ALTER TABLE `tb_comunidad`
  MODIFY `id_comunidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tb_departamento`
--
ALTER TABLE `tb_departamento`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `tb_detalle_pedido`
--
ALTER TABLE `tb_detalle_pedido`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `tb_envio`
--
ALTER TABLE `tb_envio`
  MODIFY `id_envio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tb_material`
--
ALTER TABLE `tb_material`
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tb_pago`
--
ALTER TABLE `tb_pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tb_pedido`
--
ALTER TABLE `tb_pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tb_persona`
--
ALTER TABLE `tb_persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tb_producto`
--
ALTER TABLE `tb_producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `tb_rol`
--
ALTER TABLE `tb_rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `otp_resets`
--
ALTER TABLE `otp_resets`
  ADD CONSTRAINT `otp_resets_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk_password_resets_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tb_detalle_pedido`
--
ALTER TABLE `tb_detalle_pedido`
  ADD CONSTRAINT `tb_detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `tb_pedido` (`id_pedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `tb_producto` (`id_producto`);

--
-- Filtros para la tabla `tb_envio`
--
ALTER TABLE `tb_envio`
  ADD CONSTRAINT `fk_envio_direccion` FOREIGN KEY (`direccion_envio`) REFERENCES `tb_pedido` (`direccion_entrega`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_envio_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `tb_pedido` (`id_pedido`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tb_pago`
--
ALTER TABLE `tb_pago`
  ADD CONSTRAINT `tb_pago_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `tb_pedido` (`id_pedido`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tb_pedido`
--
ALTER TABLE `tb_pedido`
  ADD CONSTRAINT `tb_pedido_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_pedido_ibfk_2` FOREIGN KEY (`id_departamento_envio`) REFERENCES `tb_departamento` (`id_departamento`);

--
-- Filtros para la tabla `tb_persona`
--
ALTER TABLE `tb_persona`
  ADD CONSTRAINT `tb_persona_ibfk_1` FOREIGN KEY (`id_departamento`) REFERENCES `tb_departamento` (`id_departamento`);

--
-- Filtros para la tabla `tb_producto`
--
ALTER TABLE `tb_producto`
  ADD CONSTRAINT `tb_producto_ibfk_1` FOREIGN KEY (`id_comunidad`) REFERENCES `tb_comunidad` (`id_comunidad`) ON DELETE SET NULL,
  ADD CONSTRAINT `tb_producto_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `tb_categoria` (`id_categoria`) ON DELETE SET NULL,
  ADD CONSTRAINT `tb_producto_ibfk_3` FOREIGN KEY (`id_material`) REFERENCES `tb_material` (`id_material`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD CONSTRAINT `tb_usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `tb_rol` (`id_rol`),
  ADD CONSTRAINT `tb_usuario_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `tb_persona` (`id_persona`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
