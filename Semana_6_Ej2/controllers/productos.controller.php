<?php
// productos.controller.php

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

// Asegura que la respuesta sea siempre JSON
header('Content-Type: application/json');

// Función para enviar respuesta JSON
function enviarRespuesta($status, $message, $data = null) {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Manejo de diferentes tipos de solicitudes HTTP
switch ($metodo) {
    case "GET":
        if (isset($_GET["ProductoId"])) {
            $uno = $producto->obtenerProducto($_GET["ProductoId"]);
            $resultado = mysqli_fetch_assoc($uno);
            if ($resultado) {
                echo json_encode($resultado);
            } else {
                enviarRespuesta('error', 'Producto no encontrado');
            }
        } else {
            $datos = $producto->listarProducto();
            $todos = array();
            while ($fila = mysqli_fetch_assoc($datos)) {
                $todos[] = $fila;
            }
            echo json_encode($todos);
        }
        break;
    
    case "POST":
        $datos = $_POST;
        if (isset($datos['op']) && $datos['op'] === 'eliminar') {
            if (!empty($datos["id"])) {
                try {
                    $eliminar = $producto->eliminarProducto($datos["id"]);
                    enviarRespuesta('success', 'Producto eliminado correctamente');
                } catch (Exception $th) {
                    enviarRespuesta('error', 'Error al eliminar el producto');
                }
            } else {
                enviarRespuesta('error', 'No se proporcionó el ID del producto');
            }
        } else {
            $nombre = $datos["nombre"] ?? '';
            $precio = $datos["precio"] ?? '';
            $stock = $datos["stock"] ?? '';

            if (!empty($nombre) && !empty($precio) && !empty($stock)) {
                $insertar = $producto->insertarProducto($nombre, $precio, $stock);
                if ($insertar) {
                    enviarRespuesta('success', 'Producto insertado correctamente');
                } else {
                    enviarRespuesta('error', 'Error al insertar el producto');
                }
            } else {
                enviarRespuesta('error', 'Faltan datos para insertar el producto');
            }
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
                enviarRespuesta('success', 'Producto actualizado correctamente');
            } else {
                enviarRespuesta('error', 'Error al actualizar el producto');
            }
        } else {
            enviarRespuesta('error', 'No se proporcionó el ID del producto');
        }
        break;
    
    case "DELETE":
        $datos = json_decode(file_get_contents('php://input'), true);
        if (!empty($datos["ProductoId"])) {
            try {
                $eliminar = $producto->eliminarProducto($datos["ProductoId"]);
                enviarRespuesta('success', 'Producto eliminado correctamente');
            } catch (Exception $th) {
                enviarRespuesta('error', 'Error al eliminar el producto');
            }
        } else {
            enviarRespuesta('error', 'No se proporcionó el ID del producto');
        }
        break;
    
    default:
        enviarRespuesta('error', 'Método HTTP no soportado');
        break;
}
?>