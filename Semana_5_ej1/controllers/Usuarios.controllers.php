<?php
//error_reporting(0);
/*TODO: Requerimientos */
require_once('../config/conexion.php');
require_once("../models/Usuarios.models.php");
require_once("../models/Accesos.models.php");

$Usuarios = new Usuarios;
$Accesos = new Accesos;

switch ($_GET["op"]) {
    /*TODO: Procedimiento para listar todos los registros */
    case 'todos':
        $datos = array();
        $datos = $Usuarios->todos();
        while ($row = mysqli_fetch_assoc($datos)) {
            $todos[] = $row;
        }
        echo json_encode($todos);
        break;

    /*TODO: Procedimiento para sacar un registro */
    case 'uno':
        $idUsuarios = $_POST["idUsuarios"];
        $datos = array();
        $datos = $Usuarios->uno($idUsuarios);
        $res = mysqli_fetch_assoc($datos);
        echo json_encode($res);
        break;

    /*TODO: Procedimiento para insertar */
    case 'insertar':
        $Nombres = $_POST["Nombres"];
        $Apellidos = $_POST["Apellidos"];
        $Correo = $_POST["Correo"];
        $Contrasenia = $_POST["Contrasenia"];
        $Roles_idRoles = $_POST["idRoles"];
        $datos = array();
        $datos = $Usuarios->Insertar($Nombres, $Apellidos, $Correo, $Contrasenia, $Roles_idRoles);
        echo json_encode($datos);
        break;

    /*TODO: Procedimiento para actualizar */
    case 'actualizar':
        $idUsuarios = $_POST["idUsuarios"];
        $Nombres = $_POST["Nombres"];
        $Apellidos = $_POST["Apellidos"];
        $Correo = $_POST["Correo"];
        $Contrasenia = $_POST["Contrasenia"];
        $Roles_idRoles = $_POST["Roles_idRoles"];
        $datos = array();
        $datos = $Usuarios->Actualizar($idUsuarios, $Nombres, $Apellidos, $Correo, $Contrasenia, $Roles_idRoles);
        echo json_encode($datos);
        break;

    /*TODO: Procedimiento para eliminar */
    case 'eliminar':
        $idUsuarios = $_POST["idUsuarios"];
        $datos = array();
        $datos = $Usuarios->Eliminar($idUsuarios);
        echo json_encode($datos);
        break;

    /*TODO: Procedimiento para login */
    case 'login':
        if (empty($_POST['correo']) || empty($_POST['password'])) {
            echo json_encode(array("status" => "error", "message" => "Faltan datos de login"));
            exit();
        }
    
        $correo = $_POST['correo'];
        $password = $_POST['password'];
    
        $Usuarios = new Usuarios();
        $datos = $Usuarios->login($correo);
        
        error_log("Correo proporcionado: " . $correo);
        error_log("Número de filas devueltas: " . mysqli_num_rows($datos));
    
        if ($datos && mysqli_num_rows($datos) > 0) {
            $usuario = mysqli_fetch_assoc($datos);
            if ($password === $usuario['password']) {
                // Login exitoso
                session_start();
                $_SESSION['usuario'] = $usuario;
    
                $Accesos = new Accesos();
                $Accesos->registrarAcceso($usuario['UsuarioId']);
    
                echo json_encode(array("status" => "success", "message" => "Login exitoso"));
            } else {
                echo json_encode(array("status" => "error", "message" => "Contraseña incorrecta"));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Usuario no encontrado"));
        }
        break;
    }