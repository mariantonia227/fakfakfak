<?php
session_start();

/* VALIDAR LOGIN */
if (!isset($_SESSION['nombre'])) {
    header("Location: /LEAFY_PROYECTO_2/login.php");
    exit();
}

/* CONEXIÓN */
require_once("conexion.php");

/* VALIDAR ID */
if (!isset($_GET['id'])) {
    header("Location: ../principal.php");
    exit();
}

$id = $_GET['id'];

/* OBTENER PRECIO */
$result = $enlace->query("SELECT precio FROM productos WHERE id_producto='$id'");
$producto = $result->fetch_assoc();

$precio = $producto['precio'];

/* CREAR CARRITO */
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

/* GUARDAR PRODUCTO */
$_SESSION['carrito'][] = [
    "id_producto" => $id,
    "precio" => $precio
];

/* REDIRECCIÓN CON MENSAJE */
if (isset($_GET['volver'])) {

    $volver = $_GET['volver'];

    $separador = (strpos($volver, '?') !== false) ? '&' : '?';

    header("Location: ../" . $volver . $separador . "carrito=1");

} else {

    header("Location: ../principal.php?carrito=1");
}

exit();
?>