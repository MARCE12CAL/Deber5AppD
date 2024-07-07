<?php
error_reporting(0);
require_once('../config/cors.php');
require_once('../models/roles.model.php');

$roles = new Clase_Roles();
$metodo = $_SERVER['REQUEST_METHOD'];
switch ($metodo) {
    case "GET":
        $datos = $roles->todos();
        $todos = array();
        while ($fila = mysqli_fetch_assoc($datos)) {
            array_push($todos, $fila);
        }
        echo json_encode($todos);
        break;
}
