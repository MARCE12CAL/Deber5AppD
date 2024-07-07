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
                
                // Estructura de la página de bienvenida
                $nombreCompleto = isset($usuario['Nombres']) ? htmlspecialchars($usuario['Nombres']) : "Usuario";
                $apellidos = isset($usuario['Apellidos']) ? htmlspecialchars($usuario['Apellidos']) : "";
                $correoUsuario = isset($usuario['Correo']) ? htmlspecialchars($usuario['Correo']) : "No disponible";

                $estructuraHTML = "
                <!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Bienvenido</title>
                    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css' rel='stylesheet'>
                    <style>
                        body {
                            background-color: #f8f9fa;
                        }
                        .container {
                            margin-top: 50px;
                        }
                        .welcome-card {
                            background-color: #ffffff;
                            border-radius: 10px;
                            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                            padding: 30px;
                            text-align: center;
                        }
                        .table {
                            margin-top: 20px;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='row justify-content-center'>
                            <div class='col-md-8'>
                                <div class='welcome-card'>
                                    <h1>Bienvenido, " . $nombreCompleto . "!</h1>
                                    <p>Has ingresado exitosamente al sistema.</p>
                                    <div class='mt-4'>
                                        <h2>Información del Usuario</h2>
                                        <table class='table table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th>Campo</th>
                                                    <th>Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Nombre Completo</td>
                                                    <td>" . $nombreCompleto . " " . $apellidos . "</td>
                                                </tr>
                                                <tr>
                                                    <td>Correo Electrónico</td>
                                                    <td>" . $correoUsuario . "</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class='mt-4'>
                                        <a href='#' class='btn btn-primary'>Opción 1</a>
                                        <a href='#' class='btn btn-secondary'>Opción 2</a>
                                        <a href='#' class='btn btn-success'>Opción 3</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js'></script>
                </body>
                </html>";
    
                echo $estructuraHTML;
                exit();
            } else {
                echo json_encode(array("status" => "error", "message" => "Contraseña incorrecta"));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Usuario no encontrado"));
        }
        break;
}
