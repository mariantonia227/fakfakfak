<?php
require_once("conexion.php");

if(isset($_GET['id'])){

    $id = $_GET['id'];

    // borrar imagen relacionada
    $enlace->query("DELETE FROM imagenes_productos WHERE id_producto='$id'");

    // borrar producto
    $enlace->query("DELETE FROM productos WHERE id_producto='$id'");

}

header("Location: ../dashboard_productos.php");
exit();
?>