create table
    Productos (
        id int AUTO_INCREMENT primary key,
        nombre varchar(50),
        precio decimal(10, 2),
        stock int
    );

create table
    Clientes (
        id int AUTO_INCREMENT primary key,
        nombre varchar(50),
        apellido varchar(50),
        email varchar(100),
        telefono varchar(20)
    );

create table
    Ventas (
        id int AUTO_INCREMENT primary key,
        fecha datetime,
        cliente_id int,
        total decimal(10, 2),
        foreign key (cliente_id) references Cliente (id)
    );

create table
    VentasDetalle (
        id int AUTO_INCREMENT primary key,
        venta_id int,
        producto_id int,
        cantidad int,
        precio decimal(10, 2),
        foreign key (venta_id) references Venta (id),
        foreign key (producto_id) references Producto (id)
    );

CREATE TABLE
    `usuarios` (
        `UsuarioId` int (11) NOT NULL,
        `Nombre` text NOT NULL,
        `correo` text NOT NULL,
        `password` text NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios` ADD PRIMARY KEY (`UsuarioId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios` MODIFY `UsuarioId` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

COMMIT;