<?php
// Muestra todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluye archivos necesarios
require_once('../config/cors.php');
require_once('../models/usuarios.model.php');

// Crea una instancia de la clase de usuarios
$usuario = new Clase_Usuarios();

// Obtiene el método HTTP utilizado
$metodo = $_SERVER['REQUEST_METHOD'];

// Manejo de diferentes tipos de solicitudes HTTP
switch ($metodo) {
    case "GET":
        if (isset($_GET["UsuarioId"])) {
            $uno = $usuario->uno($_GET["UsuarioId"]);
            echo json_encode(mysqli_fetch_assoc($uno));
        } else {
            $datos = $usuario->todos();
            $todos = array();
            while ($fila = mysqli_fetch_assoc($datos)) {
                array_push($todos, $fila);
            }
            echo json_encode($todos);
        }
        break;
    
    case "POST":
        if (isset($_GET["op"]) && $_GET["op"] == "login") {
            // Verifica que los campos de correo y contraseña no estén vacíos
            if (empty(trim($_POST["correo"])) || empty(trim($_POST["contrasenia"]))) {
                header('Location: ../index.php?op=2'); // Redirecciona si faltan datos
                exit();
            }

            $correo = $_POST["correo"];
            $contrasena = $_POST["contrasenia"];

            $login = $usuario->loginParametros($correo, $contrasena);
            if ($login) {
                $res = mysqli_fetch_assoc($login);
                if ($res && $res['password'] == $contrasena) {
                    header('Location: ../views/dashboard.php'); // Redirecciona al dashboard si el login es exitoso
                    exit();
                } else {
                    header('Location:../index.php?op=3'); // Redirecciona si la contraseña es incorrecta
                    exit();
                }
            } else {
                header('Location:../index.php?op=1'); // Redirecciona si el usuario no se encuentra
                exit();
            }
        } elseif (isset($_GET["op"]) && $_GET["op"] == "actualizar") {
            $UsuarioId = $_POST["UsuarioId"];
            $Nombre = $_POST["Nombre"];
            $correo = $_POST["correo"];
            $password = $_POST["password"];
            $estado = $_POST["estado"];
            $RolesId = $_POST["RolesId"];

            if (!empty($UsuarioId) && !empty($correo) && !empty($password)) {
                $actualizar = $usuario->actualizar($UsuarioId, $Nombre, $correo, $password, $estado, $RolesId);
                if ($actualizar) {
                    echo json_encode(array("message" => "Se actualizo correctamente"));
                } else {
                    echo json_encode(array("message" => "Error, no se actualizo"));
                }
            } else {
                echo json_encode(array("message" => "Error, faltan datos: " . json_encode($_POST)));
            }
        } else {
            $Nombre = $_POST["Nombre"];
            $correo = $_POST["correo"];
            $password = $_POST["password"];
            $estado = $_POST["estado"];
            $RolesId = $_POST["RolesId"];

            if (!empty($correo) && !empty($password)) {
                $insertar = $usuario->insertar($Nombre, $correo, $password, $estado, $RolesId);
                if ($insertar) {
                    echo json_encode(array("message" => "Se inserto correctamente"));
                } else {
                    echo json_encode(array("message" => "Error, no se inserto"));
                }
            } else {
                echo json_encode(array("message" => "Error, faltan datos"));
            }
        }
        break;
    
    case "PUT":
        // Manejo de la actualización de recursos (a implementar si es necesario)
        break;
    
    case "DELETE":
        $datos = json_decode(file_get_contents('php://input'));
        if (!empty($datos->UsuarioId)) {
            try {
                $eliminar = $usuario->eliminar($datos->UsuarioId);
                echo json_encode(array("message" => "Se elimino correctamente"));
            } catch (Exception $th) {
                echo json_encode(array("message" => "Error, no se elimino"));
            }
        } else {
            echo json_encode(array("message" => "Error, no se envio el id"));
        }
        break;
    
    case "login":
        // Posiblemente no necesario, el login se maneja en POST
        break;
    
    default:
        echo json_encode(array("message" => "Método HTTP no soportado"));
        break;
}
