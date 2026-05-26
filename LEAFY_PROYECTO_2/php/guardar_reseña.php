<?php
session_start();
require_once("conexion.php");

if(!isset($_SESSION['email'])){
    exit();
}

$email = $_SESSION['email'];

$user = $enlace->query("
SELECT id_usuarios
FROM usuarios
WHERE email='$email'
")->fetch_assoc();

$id_usuario = $user['id_usuarios'];

$id_negocio = $_POST['id_negocio'];
$calificacion = $_POST['calificacion'];
$comentario = $_POST['comentario'];

/* EVITAR DOBLE RESEÑA */

$check = $enlace->query("
SELECT *
FROM reseñas_negocios
WHERE id_usuario='$id_usuario'
AND id_negocio='$id_negocio'
");

if($check->num_rows > 0){
    die("Ya hiciste una reseña");
}

/* GUARDAR */

$enlace->query("
INSERT INTO reseñas_negocios
(id_negocio, id_usuario, calificacion, comentario)
VALUES
('$id_negocio','$id_usuario','$calificacion','$comentario')
");

header("Location: ../negocio.php?id=$id_negocio");
?>