<?php
require_once("conexion.php");

if(isset($_POST['id_producto'])){

    $id = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $talla = $_POST['talla'];
    $estado = $_POST['estado_producto'];

    $sql = "UPDATE productos SET 
                nombre='$nombre',
                descripcion='$descripcion',
                precio='$precio',
                talla='$talla',
                estado_producto='$estado'
            WHERE id_producto='$id'";

    $enlace->query($sql);
}

header("Location: ../dashboard_productos.php");
exit();
?>