<?php
session_start();
require_once("conexion.php");

if (!isset($_GET['id'])) {
    header("Location: ../perfil.php");
    exit();
}

$id = $_GET['id'];

$enlace->query("DELETE FROM favoritos WHERE id_favorito='$id'");

header("Location: ../perfil.php");
?>