<?php
// Muestra todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluye archivos necesarios
require_once('../config/cors.php');
require_once('../models/productos.model.php');

// Crea una instancia de la clase de productos
$producto = new Clase_Productos();

// Obtiene el método HTTP utilizado
$metodo = $_SERVER['REQUEST_METHOD'];

// Manejo de diferentes tipos de solicitudes HTTP
switch ($metodo) {
  case "GET":
      if (isset($_GET["ProductoId"])) {
          $uno = $producto->obtenerProducto($_GET["ProductoId"]);
          echo json_encode(mysqli_fetch_assoc($uno));
      } else {
          $datos = $producto->listarProducto();
          $todos = array();
          while ($fila = mysqli_fetch_assoc($datos)) {
              array_push($todos, $fila);
          }
          echo json_encode($todos);
      }
      break;
  
  case "POST":
      $nombre = $_POST["nombre"];
      $precio = $_POST["precio"];
      $stock = $_POST["stock"];

      if (!empty($nombre) && !empty($precio) && !empty($stock)) {
          $insertar = $producto->insertarProducto($nombre, $precio, $stock);
          if ($insertar) {
              echo json_encode(array("message" => "Se inserto correctamente"));
          } else {
              echo json_encode(array("message" => "Error, no se inserto"));
          }
      } else {
          echo json_encode(array("message" => "Error, faltan datos"));
      }
      break;
  
  case "PUT":
      $datos = json_decode(file_get_contents('php://input'), true);
      if (!empty($datos["ProductoId"])) {
          $nombre = $datos["nombre"];
          $precio = $datos["precio"];
          $stock = $datos["stock"];

          $actualizar = $producto->actualizarProducto($datos["ProductoId"], $nombre, $precio, $stock);
          if ($actualizar) {
              echo json_encode(array("message" => "Se actualizo correctamente"));
          } else {
              echo json_encode(array("message" => "Error, no se actualizo"));
          }
      } else {
          echo json_encode(array("message" => "Error, no se envio el id"));
      }
      break;
  
  case "DELETE":
      $datos = json_decode(file_get_contents('php://input'), true);
      if (!empty($datos["ProductoId"])) {
          try {
              $eliminar = $producto->eliminarProducto($datos["ProductoId"]);
              echo json_encode(array("message" => "Se elimino correctamente"));
          } catch (Exception $th) {
              echo json_encode(array("message" => "Error, no se elimino"));
          }
      } else {
          echo json_encode(array("message" => "Error, no se envio el id"));
      }
      break;
  
  default:
      echo json_encode(array("message" => "Método HTTP no soportado"));
      break;
}
?>