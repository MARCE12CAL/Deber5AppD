select
    pedido.Codigo,
    cliente.Nombre as cliente,
    producto.nombre as producto,
    gama_producto.nombre as categoria
from
    cliente
    inner join pedido on cliente.codigo_cliente = pedido.codigo_cliente
    inner join detalle_pedido on pedido.codigo_pedido = detalle_pedido.codigo_pedido
    inner join producto on detalle_pedido.codigo_producto = producto.codigo_producto
    inner join gama_producto on producto.codigo_gama = gama_producto.codigo_gama
    -- phpMyAdmin SQL Dump
    -- version 5.1.2
    -- https://www.phpmyadmin.net/
    --
    -- Servidor: localhost:3306
    -- Tiempo de generación: 02-07-2024 a las 00:48:33
    -- Versión del servidor: 5.7.24
    -- Versión de PHP: 8.2.14
    -- phpMyAdmin SQL Dump
    -- version 5.1.2
    -- https://www.phpmyadmin.net/
    --
    -- Servidor: localhost:3306
    -- Tiempo de generación: 05-07-2024 a las 02:17:03
    -- Versión del servidor: 5.7.24
    -- Versión de PHP: 8.2.14
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
    time_zone = "+00:00";

--
-- Base de datos: `quinto`
--
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `clientes`
--
CREATE TABLE
    `clientes` (
        `id` int (11) NOT NULL,
        `nombre` varchar(50) DEFAULT NULL,
        `apellido` varchar(50) DEFAULT NULL,
        `email` varchar(100) DEFAULT NULL,
        `telefono` varchar(20) DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `productos`
--
CREATE TABLE
    `productos` (
        `id` int (11) NOT NULL,
        `nombre` varchar(50) DEFAULT NULL,
        `precio` decimal(10, 2) DEFAULT NULL,
        `stock` int (11) DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `roles`
--
CREATE TABLE
    `roles` (
        `RolesId` int (11) NOT NULL,
        `Detalle` text NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

--
-- Volcado de datos para la tabla `roles`
--
INSERT INTO
    `roles` (`RolesId`, `Detalle`)
VALUES
    (1, 'Administrador'),
    (2, 'Secretaria/o'),
    (3, 'Gerente'),
    (4, 'Bodega'),
    (5, 'Nuevo Rol'),
    (6, 'Otro nuevo');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `usuarios`
--
CREATE TABLE
    `usuarios` (
        `UsuarioId` int (11) NOT NULL,
        `Nombre` text NOT NULL,
        `correo` text NOT NULL,
        `password` text NOT NULL,
        `estado` int (1) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

--
-- Volcado de datos para la tabla `usuarios`
--
INSERT INTO
    `usuarios` (
        `UsuarioId`,
        `Nombre`,
        `correo`,
        `password`,
        `estado`
    )
VALUES
    (1, 'lleroc', 'admin', '123', 1),
    (2, 'Alex Bayas', 'alex@gmail.com', '123', 1),
    (3, 'Juan Arreaga', 'juan@gmail.com', '123', 0),
    (
        4,
        'Osvaldo Hurtado',
        'osvaldo@gmail.com',
        '123',
        0
    ),
    (5, 'Edgar Suarez', 'edgar@gmail.com', '123', 1),
    (6, 'FFGHFHJDFG', 'lleroc@gmail.com', '123', 1),
    (7, 'Alex Bayas', 'alex@gmail.com', '123', 0),
    (8, 'Alex bayas', 'corre@correo.com', '123', 0);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `usuarios_roles`
--
CREATE TABLE
    `usuarios_roles` (
        `UsuariosRolesId` int (11) NOT NULL,
        `RolesId` int (11) NOT NULL,
        `UsuarioId` int (11) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

--
-- Volcado de datos para la tabla `usuarios_roles`
--
INSERT INTO
    `usuarios_roles` (`UsuariosRolesId`, `RolesId`, `UsuarioId`)
VALUES
    (1, 1, 1),
    (2, 4, 2),
    (3, 3, 5),
    (4, 2, 4),
    (5, 3, 1),
    (6, 4, 3),
    (7, 2, 1),
    (8, 2, 3),
    (9, 3, 6),
    (10, 4, 7),
    (11, 4, 8);

--
-- Índices para tablas volcadas
--
--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes` ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos` ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles` ADD PRIMARY KEY (`RolesId`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios` ADD PRIMARY KEY (`UsuarioId`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles` ADD PRIMARY KEY (`UsuariosRolesId`),
ADD KEY `usuarios_usauriosroles` (`UsuarioId`),
ADD KEY `roles_usuariosroles` (`RolesId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--
--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles` MODIFY `RolesId` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios` MODIFY `UsuarioId` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 9;

--
-- AUTO_INCREMENT de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles` MODIFY `UsuariosRolesId` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 12;

--
-- Restricciones para tablas volcadas
--
--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles` ADD CONSTRAINT `roles_usuariosroles` FOREIGN KEY (`RolesId`) REFERENCES `roles` (`RolesId`),
ADD CONSTRAINT `usuarios_usauriosroles` FOREIGN KEY (`UsuarioId`) REFERENCES `usuarios` (`UsuarioId`);

COMMIT;