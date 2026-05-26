<?php
session_start();
require_once("conexion.php");

if(!isset($_SESSION['email'])){
    header("Location: ../login.php");
    exit();
}

$usuario = $enlace->query("
SELECT id_usuarios FROM usuarios
WHERE email='{$_SESSION['email']}'
")->fetch_assoc();

$id_usuario = $usuario['id_usuarios'];

$id = $_POST['id_comentario'];
$motivo = $_POST['motivo'];
$detalle = $_POST['detalle'];

$motivo = $enlace->real_escape_string($motivo);
$detalle = $enlace->real_escape_string($detalle);

$enlace->query("
INSERT INTO comentarios_reportes
(id_comentario,id_usuarios,motivo,detalle,fecha)
VALUES
('$id','$id_usuario','$motivo','$detalle',NOW())
");

header("Location: ../comentarios.php?reportado=1");
exit();
?>