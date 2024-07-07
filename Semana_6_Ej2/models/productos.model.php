<?php
require_once('../config/conexion.php');

class Clase_Productos
{
    public function listarProducto() 
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "SELECT * FROM productos";
        $productos = mysqli_query($con, $cadena);
        $con->close();
        return $productos;
    }

    public function obtenerProducto($ProductoId) 
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "SELECT * FROM productos WHERE id = $ProductoId";
        $producto = mysqli_query($con, $cadena);
        $con->close();
        return $producto;
    }

    public function insertarProducto($nombre, $precio, $stock) 
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "INSERT INTO productos (nombre, precio, stock) VALUES ('$nombre', $precio, $stock)";
        if (mysqli_query($con, $cadena)) {
            $con->close();
            return true;
        } else {
            $con->close();
            return "Error: No se inserto el producto";
        }
    }

    public function actualizarProducto($ProductoId, $nombre, $precio, $stock) 
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "UPDATE productos SET nombre = '$nombre', precio = $precio, stock = $stock WHERE id = $ProductoId";
        if (mysqli_query($con, $cadena)) {
            $con->close();
            return true;
        } else {
            $con->close();
            return "Error: No se actualizo el producto";
        }
    }

    public function eliminarProducto($ProductoId) 
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "DELETE FROM productos WHERE id = $ProductoId";
        $producto = mysqli_query($con, $cadena);
        $con->close();
        return $producto;
    }
}
?>