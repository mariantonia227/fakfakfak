<?php
session_start();
require_once("conexion.php");

if(!isset($_SESSION['email'])){
    header("Location: ../login.php");
    exit();
}

$id = $_POST['id_comentario'];
$comentario = trim($_POST['comentario']);

$usuario = $enlace->query("
SELECT id_usuarios 
FROM usuarios
WHERE email='{$_SESSION['email']}'
")->fetch_assoc();

$id_usuario = $usuario['id_usuarios'];

$comentario = $enlace->real_escape_string($comentario);

$enlace->query("
UPDATE comentarios
SET comentario='$comentario'
WHERE id_comentario='$id'
AND id_usuarios='$id_usuario'
");

header("Location: ../comentarios.php?edit=1");
exit();
?>