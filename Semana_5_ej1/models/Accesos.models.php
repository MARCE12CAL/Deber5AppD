<?php
//TODO: Requerimientos 
require_once('../config/conexion.php');

class Accesos
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
        $cadena = "SELECT Accesos.*, Usuarios.Nombres, Usuarios.Apellidos 
                   FROM `Accesos` 
                   INNER JOIN Usuarios ON Accesos.Usuarios_idUsuarios = Usuarios.idUsuarios";
        $datos = mysqli_query($this->con, $cadena);
        return $datos;
    }

    /*TODO: Procedimiento para sacar un registro*/
    public function uno($idAccesos)
    {
        $idAccesos = mysqli_real_escape_string($this->con, $idAccesos);
        $cadena = "SELECT * FROM Accesos WHERE idAccesos = '$idAccesos'";
        $datos = mysqli_query($this->con, $cadena);
        return $datos;
    }

    /*TODO: Procedimiento para insertar */
    public function Insertar($Ultimo, $Usuarios_idUsuarios, $tipo)
    {
        $Ultimo = mysqli_real_escape_string($this->con, $Ultimo);
        $Usuarios_idUsuarios = mysqli_real_escape_string($this->con, $Usuarios_idUsuarios);
        $tipo = mysqli_real_escape_string($this->con, $tipo);

        $cadena = "INSERT INTO Accesos(Ultimo, Usuarios_idUsuarios, tipo) VALUES ('$Ultimo', '$Usuarios_idUsuarios', '$tipo')";

        if (mysqli_query($this->con, $cadena)) {
            return "ok";
        } else {
            return mysqli_error($this->con);
        }
    }

    /*TODO: Procedimiento para actualizar */
    public function Actualizar($idAccesos, $Ultimo, $Usuarios_idUsuarios)
    {
        $idAccesos = mysqli_real_escape_string($this->con, $idAccesos);
        $Ultimo = mysqli_real_escape_string($this->con, $Ultimo);
        $Usuarios_idUsuarios = mysqli_real_escape_string($this->con, $Usuarios_idUsuarios);

        $cadena = "UPDATE Accesos SET Ultimo='$Ultimo', Usuarios_idUsuarios='$Usuarios_idUsuarios' WHERE idAccesos='$idAccesos'";
        if (mysqli_query($this->con, $cadena)) {
            return "ok";
        } else {
            return 'Error al actualizar el registro';
        }
    }

    /*TODO: Procedimiento para Eliminar */
    public function Eliminar($idAccesos)
    {
        $idAccesos = mysqli_real_escape_string($this->con, $idAccesos);
        $cadena = "DELETE FROM Accesos WHERE idAccesos = '$idAccesos'";
        if (mysqli_query($this->con, $cadena)) {
            return true;
        } else {
            return false;
        }
    }

    /*TODO: Procedimiento para registrar acceso */
    public function registrarAcceso($idUsuario)
    {
        $idUsuario = mysqli_real_escape_string($this->con, $idUsuario);
        $fecha = date('Y-m-d H:i:s');
        $tipo = 'login';  // Puedes cambiar esto según tus necesidades

        $cadena = "INSERT INTO Accesos(Ultimo, Usuarios_idUsuarios, tipo) VALUES ('$fecha', '$idUsuario', '$tipo')";
        if (mysqli_query($this->con, $cadena)) {
            return true;
        } else {
            return false;
        }
    }

    public function __destruct()
    {
        mysqli_close($this->con);
    }
}