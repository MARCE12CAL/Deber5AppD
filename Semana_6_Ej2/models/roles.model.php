<?php
// php es un lenguaje de programacion interpretado, por lo que no se compila, se ejecuta en el servidor
require_once('../config/conexion.php');
class Clase_Roles
{
    //TODO: procedimiento para obtener todos los usuarios de la base de datos
    public function todos()  ///select * from usuarios;
    {
        //instanciar la clase conectar
        $con = new Clase_Conectar();
        //usar el procedimiento para conectar
        $con = $con->Procedimiento_Conectar();
        //ejecutar la consulta
        $cadena = "SELECT * FROM `roles`";
        //guardar la consulta en una variable
        $todos = mysqli_query($con, $cadena);
        //cerrar la conexion
        $con->close();
        //retornar la consulta
        return $todos;
    }
    public function uno($RolesId) //select * from usuarios where UsuarioId=$UsuarioId;
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "SELECT * FROM `roles` where RolesId=$RolesId";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;
    }
    public function insertar($Detalle)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "INSERT INTO `roles`(`Detalle`) VALUES ('$Detalle ')";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;
    }
    public function actualizar($RolesId, $Detalle)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "UPDATE `roles` SET `Detalle`='$Detalle' WHERE RolesId=$RolesId";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;
    }
    public function actualizar_Estado($RolesId, $estado)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "UPDATE `roles` SET `estado`=$estado WHERE RolesId=$RolesId";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;
    }
    public function eliminar($RolesId)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "DELETE FROM `roles` where RolesId=$RolesId";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;
    }
}
