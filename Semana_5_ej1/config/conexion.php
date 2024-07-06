<?php

//POO
class Clase_Conectar
{
    public $conexion;
    protected $db;
    private $host = "127.0.0.1";
    private $usuario = "root";
    private $clave = "";
    private $base = "quinto3";
    private $port ="3307"; 

    public function Procedimiento_Conectar()
    {
        $this->conexion = mysqli_connect($this->host, $this->usuario, $this->clave, $this->base, $this->port);

        if (!$this->conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        $this->db = mysqli_select_db($this->conexion, $this->base);

        if (!$this->db) {
            die("Error al seleccionar la base de datos: " . mysqli_error($this->conexion));
        }

        return $this->conexion;
    }
}
/*
Angular

Backend  => PHP
FrontEnd => PHP - Html - CSS - Bootstrap - Javascript - Jquery

*/