<?php
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    unset($_SESSION['carrito'][$id]);

    // reordenar array
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
}

header("Location: ../carrito.php");
exit();