<?php
session_start();
require_once("conexion.php");

header('Content-Type: application/json');

// 🚨 Verificar sesión
if (!isset($_SESSION['email'])) {
    echo json_encode([
        "status" => "error",
        "msg" => "Debes iniciar sesión"
    ]);
    exit();
}

// Obtener usuario
$email = $_SESSION['email'];
$user = $enlace->query("SELECT id_usuarios FROM usuarios WHERE email='$email'")->fetch_assoc();
$id_usuario = $user['id_usuarios'];

// Obtener producto
if (!isset($_POST['id_producto'])) {
    echo json_encode([
        "status" => "error",
        "msg" => "Producto no válido"
    ]);
    exit();
}

$id_producto = $_POST['id_producto'];

// 🔍 Verificar si ya existe
$check = $enlace->query("
    SELECT * FROM favoritos 
    WHERE id_usuarios='$id_usuario' 
    AND id_producto='$id_producto'
");

if ($check->num_rows > 0) {

    // ❌ Eliminar
    $enlace->query("
        DELETE FROM favoritos 
        WHERE id_usuarios='$id_usuario' 
        AND id_producto='$id_producto'
    ");

    echo json_encode([
        "status" => "removed"
    ]);

} else {

    // ❤️ Insertar
    $enlace->query("
        INSERT INTO favoritos (id_usuarios, id_producto, fecha_agregado)
        VALUES ('$id_usuario', '$id_producto', NOW())
    ");

    echo json_encode([
        "status" => "added"
    ]);
}
?>