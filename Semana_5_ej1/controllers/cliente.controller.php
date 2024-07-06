<?php

require_once('../models/cliente.model.php');
$cliente = new Clase_Cliente();

switch ($_GET['op']) {
    case "probar":
        $datos = array();
        $datos = $cliente->probar();
        echo json_encode($datos);
        break;
    case "todos":
        $datos = array();
        $datos = $cliente->todos();
        while ($fila = mysqli_fetch_assoc($datos)) {
            $todos[] = $fila;
        }
        echo json_encode($todos);
        break;
    case "uno":
        $id = $_POST["id"];
        $datos = array();
        $datos = $cliente->uno($id);
        echo json_encode(mysqli_fetch_assoc($datos));
        break;

    case "insertar":

        // `nombre`, `apellido`, `email`, `telefono`
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $email = $_POST["email"];
        $telefono = $_POST["telefono"];
        $datos = array();

        $datos = $cliente->insertar($nombre, $apellido, $email, $telefono);

        echo json_encode($datos);
        break;
    case "actualizar":
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $email = $_POST["email"];
        $telefono = $_POST["telefono"];
        $datos = array();
        $datos = $cliente->actualizar($id, $nombre, $apellido, $email, $telefono);
        echo json_encode($datos);
        break;
    case "eliminar":
        $id = $_POST["id"];
        $datos = array();
        $datos = $cliente->eliminar($id);
        echo json_encode($datos);
        break;
    case "buscarXNombre":
        $nombre = $_POST["nombre"];
        $datos = array();
        $datos = $cliente->buscarXNombre($nombre);
        while ($fila = mysqli_fetch_assoc($datos)) {
            $todos[] = $fila;
        }
        echo json_encode($todos);
        break;
}
//get  =>  url
//post =>  form