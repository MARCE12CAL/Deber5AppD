<?php 
/*TODO: Requerimientos */
require_once("../config/cors.php");
require_once("../models/Usuarios_Roles.models.php");
error_reporting(0); 

$Usuarios_Roles= new Usuarios_Roles;
switch ($_GET["op"]) {
/*TODO: Procedimiento para listar todos los registros */
case 'todos': 
$datos = array();
$datos = $Usuarios_Roles->todos();
while ($row = mysqli_fetch_assoc($datos)) {
$todos[] = $row;
} 
echo json_encode($todos);
break; 
/*TODO: Procedimiento para sacar un registro */
 case 'uno': 
$Usuarios_idUsuarios = $_POST["Usuarios_idUsuarios"];
$datos = array();
$datos = $Usuarios_Roles->uno($Usuarios_idUsuarios);
$res = mysqli_fetch_assoc($datos);
echo json_encode($res);
break; 
/*TODO: Procedimiento para insertar */
 case 'insertar': 
 $Usuarios_idUsuarios= $_POST["Usuarios_idUsuarios"];$Roles_idRoles= $_POST["Roles_idRoles"];$idUsuariosRoles=$_POST["idUsuariosRoles"]; 

$datos = array();
$datos = $Usuarios_Roles->Insertar($Usuarios_idUsuarios,$Roles_idRoles,$idUsuariosRoles);
echo json_encode($datos);  
break; 
/*TODO: Procedimiento para actualizar */
 case 'actualizar': 
$Usuarios_idUsuarios= $_POST["Usuarios_idUsuarios"];$Roles_idRoles= $_POST["Roles_idRoles"];$idUsuariosRoles=$_POST["idUsuariosRoles"]; 
$datos = array();
$datos = $Usuarios_Roles->Actualizar($Usuarios_idUsuarios,$Roles_idRoles,$idUsuariosRoles); 
echo json_encode($datos);
break; 
/*TODO: Procedimiento para eliminar */
 case 'eliminar': 
$Usuarios_idUsuarios=$_POST["Usuarios_idUsuarios"]; 
$datos = array(); 
$datos = $Usuarios_Roles->Eliminar($Usuarios_idUsuarios);
echo json_encode($datos);
break; } 
