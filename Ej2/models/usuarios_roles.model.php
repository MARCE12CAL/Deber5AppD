<?php
// php es un lenguaje de programacion interpretado, por lo que no se compila, se ejecuta en el servidor
require_once('../config/conexion.php');
class Clase_Usuarios_Roles
{
    //TODO: procedimiento para obtener todos los usuarios de la base de datos
    public function todos()  ///select * from usuarios;
    {
        //instanciar la clase conectar
        $con = new Clase_Conectar();
        //usar el procedimiento para conectar
        $con = $con->Procedimiento_Conectar();
        //ejecutar la consulta
        $cadena = "SELECT * FROM `usuarios_roles`";
        //guardar la consulta en una variable
        $todos = mysqli_query($con, $cadena);
        //cerrar la conexion
        $con->close();
        //retornar la consulta
        return $todos;
    }
    public function uno($UsuariosRolesId) //select * from usuarios where UsuarioId=$UsuarioId;
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "SELECT * FROM `usuarios_roles` where UsuariosRolesId=$UsuariosRolesId";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;
    }
    public function insertar($RolesId, $UsuarioId)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "INSERT INTO `usuarios_roles`(RolesId, UsuarioId) VALUES ('$RolesId', '$UsuarioId')";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;
    }
    public function actualizar($RolesId, $UsuariosRolesId, $UsuarioId)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "UPDATE `usuarios_roles` SET UsuarioId=$UsuarioId, RolesId=$RolesId WHERE UsuariosRolesId=$UsuariosRolesId";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;
    }
    public function eliminar($UsuariosRolesId)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "DELETE FROM `usuarios_roles` where UsuariosRolesId=$UsuariosRolesId";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;
    }
    public function eliminarxUsuario($UsuarioId)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "DELETE FROM `usuarios_roles` WHERE `UsuarioId`= $UsuarioId";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;
    }
}
