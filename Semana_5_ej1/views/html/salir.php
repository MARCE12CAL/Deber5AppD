<?php


require_once("../../models/Salida.models.php");

$Accesos = new Accesos;
$datos2 = array();
$datos2 = $Accesos->Insertar(date("Y-m-d H:i:s"), $_SESSION["idUsuarios"], 'salida');

session_destroy();
header('Location:../../index.php');
exit();
