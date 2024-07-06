<?php
//TODO: Requerimientos 
require_once('../config/conexion.php');
require_once('../models/Usuarios_Roles.models.php');

class Usuarios
{
    private $con;

    public function __construct()
    {
        $conectar = new Clase_Conectar();
        $this->con = $conectar->Procedimiento_Conectar();
    }

    /*TODO: Procedimiento para sacar todos los registros*/
    public function todos()
    {
        $cadena = "SELECT Usuarios.idUsuarios, Usuarios.Nombres, Usuarios.Apellidos, Usuarios.Correo, Roles.Rol, Roles.idRoles 
                   FROM Usuarios 
                   INNER JOIN Usuarios_Roles ON Usuarios.idUsuarios = Usuarios_Roles.Usuarios_idUsuarios 
                   INNER JOIN Roles ON Usuarios_Roles.Roles_idRoles = Roles.idRoles";
        $datos = mysqli_query($this->con, $cadena);
        return $datos;
    }

    /*TODO: Procedimiento para sacar un registro*/
    public function uno($idUsuarios)
    {
        $idUsuarios = mysqli_real_escape_string($this->con, $idUsuarios);
        $cadena = "SELECT Usuarios.idUsuarios, Usuarios.Nombres, Usuarios.Apellidos, Usuarios.Correo, Roles.Rol, Roles.idRoles 
                   FROM Usuarios 
                   INNER JOIN Usuarios_Roles ON Usuarios.idUsuarios = Usuarios_Roles.Usuarios_idUsuarios 
                   INNER JOIN Roles ON Usuarios_Roles.Roles_idRoles = Roles.idRoles 
                   WHERE Usuarios.idUsuarios = '$idUsuarios'";
        $datos = mysqli_query($this->con, $cadena);
        return $datos;
    }

    /*TODO: Procedimiento para insertar */
    public function Insertar($Nombres, $Apellidos, $Correo, $Contrasenia, $idRoles)
    {
        $Nombres = mysqli_real_escape_string($this->con, $Nombres);
        $Apellidos = mysqli_real_escape_string($this->con, $Apellidos);
        $Correo = mysqli_real_escape_string($this->con, $Correo);
        $Contrasenia = password_hash($Contrasenia, PASSWORD_DEFAULT);
        
        $cadena = "INSERT INTO Usuarios(Nombres, Apellidos, Correo, Contrasenia) VALUES ('$Nombres', '$Apellidos', '$Correo', '$Contrasenia')";
        if (mysqli_query($this->con, $cadena)) {
            $UsRoles = new Usuarios_Roles();
            return $UsRoles->Insertar(mysqli_insert_id($this->con), $idRoles);
        } else {
            return 'Error al insertar en la base de datos';
        }
    }

    /*TODO: Procedimiento para actualizar */
    public function Actualizar($idUsuarios, $Nombres, $Apellidos, $Correo, $Contrasenia, $idRoles)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "update Usuarios set Nombres='$Nombres',Apellidos='$Apellidos',Correo='$Correo',Contrasenia='$Contrasenia',Roles_idRoles=$idRoles where idUsuarios= $idUsuarios";
        if (mysqli_query($con, $cadena)) {
            return ($idUsuarios);
        } else {
            return 'error al actualizar el registro';
        }
        $con->close();
    }

    /*TODO: Procedimiento para Eliminar */
    public function Eliminar($idUsuarios)
    {
        $idUsuarios = mysqli_real_escape_string($this->con, $idUsuarios);
        $UsRoles = new Usuarios_Roles();
        $UsRoles->Eliminar($idUsuarios);
        
        $cadena = "DELETE FROM Usuarios WHERE idUsuarios = '$idUsuarios'";
        if (mysqli_query($this->con, $cadena)) {
            return true;
        } else {
            return false;
        }
    }

    /*TODO: Procedimiento para login */
    public function login($correo)
    {
        $correo = mysqli_real_escape_string($this->con, $correo);
        $cadena = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $resultado = mysqli_query($this->con, $cadena);
        
        error_log("Consulta SQL: " . $cadena);
        error_log("Número de filas: " . mysqli_num_rows($resultado));
        
        if (!$resultado) {
            error_log("Error en la consulta: " . mysqli_error($this->con));
        }
        
        return $resultado;
    }

    public function __destruct()
    {
        mysqli_close($this->con);
    }
}