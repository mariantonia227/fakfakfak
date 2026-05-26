<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDatos = "proyectoleafy";
$enlace = new mysqli($servidor, $usuario, $clave, $baseDatos);

if ($enlace->connect_error) {
    die("Connection Failed: " . $enlace->connect_error);
}
?>