<?php
session_start();
require_once("conexion.php");

// Verificar sesión
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

// Obtener usuario
$email = $_SESSION['email'];
$resultUser = $enlace->query("SELECT id_usuarios FROM usuarios WHERE email='$email'");
$user = $resultUser->fetch_assoc();
$id_usuario = $user['id_usuarios'];

// Verificar carrito
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "El carrito está vacío";
    exit();
}

// Calcular total
$total = 0;
$id_negocio = 0;

foreach($_SESSION['carrito'] as $item){

    $id_producto = $item['id_producto'];
    $total += $item['precio'];

    // Obtener id_negocio desde el producto
    $resultProd = $enlace->query("SELECT id_negocios FROM productos WHERE id_producto='$id_producto'");
    $prod = $resultProd->fetch_assoc();

    $id_negocio = $prod['id_negocios']; // ⚠️ toma el último (simplificado)
}

// Insertar pedido
$enlace->query("
INSERT INTO pedidos (id_usuarios, id_negocios, fecha, total, estado_pedido)
VALUES ('$id_usuario','$id_negocio', NOW(), '$total', 'pendiente')
");

// Vaciar carrito
unset($_SESSION['carrito']);

// Redirigir
header("Location: ../pedido_exitoso.php");
exit();
?>