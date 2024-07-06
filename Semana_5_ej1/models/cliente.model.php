<?php
require_once('../config/conexion.php');
class Clase_Cliente
{
    public function probar()
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        echo $con->error;
    }

    public function todos()
    {
        try {
            $con = new Clase_Conectar();
            $con = $con->Procedimiento_Conectar();
            $cadena = "SELECT * FROM clientes";
            $resultado = mysqli_query($con, $cadena);
            $con->close();
            return $resultado;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function uno($id)
    {
        try {
            $con = new Clase_Conectar();
            $con = $con->Procedimiento_Conectar();
            $cadena = "SELECT * FROM clientes where id=$id";
            $resultado = mysqli_query($con, $cadena);
            return $resultado;
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            $con->close();
        }
    }
    public function insertar($nombre, $apellido, $email, $telefono)
    {
        try {
            echo "1";
            $con = new Clase_Conectar();
            echo "2";
            $con = $con->Procedimiento_Conectar();
            echo "3";
            $cadena = "INSERT INTO `clientes`(`nombre`, `apellido`, `email`, `telefono`) VALUES ('$nombre','$apellido','$email',$telefono')";
            echo $cadena;
            if (mysqli_query($con, $cadena)) {
                return "ok";
                $con->close();
            } else {
                return $con->error;
                $con->close();
            }
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
        }
    }
    public function actualizar($id, $nombre, $apellido, $email, $telefono)
    {
        try {
            $con = new Clase_Conectar();
            $con = $con->Procedimiento_Conectar();
            $cadena = "UPDATE `clientes` SET ,`nombre`='$nombre',`apellido`='$apellido',`email`='$email',`telefono`='$telefono' WHERE `id`=$id";
            if (mysqli_query($con, $cadena)) {
                return "ok";
            } else {
                return $con->error;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            $con->close();
        }
    }
    public function eliminar($id)
    {
        try {
            $con = new Clase_Conectar();
            $con = $con->Procedimiento_Conectar();
            $cadena = "DELETE FROM clientes where id=$id";
            if (mysqli_query($con, $cadena)) {
                return "ok";
            } else {
                return $con->error;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            $con->close();
        }
    }
    public function buscarXNombre($nombre)
    {
        try {
            $con = new Clase_Conectar();
            $con = $con->Procedimiento_Conectar();
            $cadena = "SELECT * FROM clientes where nombre=$nombre";
            $resultado = mysqli_query($con, $cadena);
            return $resultado;
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            $con->close();
        }
    }
}
