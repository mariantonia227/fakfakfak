<?php
session_start();
require_once("conexion.php");

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$email_actual = $_SESSION['email'];

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$contraseña = $_POST['password'];

// FOTO
if (!empty($_FILES['foto']['name'])) {
    $ruta = "../uploads/" . $_FILES['foto']['name'];
    move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);

    $sqlFoto = "UPDATE usuarios SET foto_perfil='$ruta' WHERE email='$email_actual'";
    $enlace->query($sqlFoto);
}

// PASSWORD (solo si escribe)
if (!empty($password)) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sqlPass = "UPDATE usuarios SET password='$password' WHERE email='$email_actual'";
    $enlace->query($sqlPass);
}

// DATOS
$sql = "UPDATE usuarios SET nombre='$nombre', email='$email' WHERE email='$email_actual'";
$enlace->query($sql);

// ACTUALIZAR SESIÓN
$_SESSION['email'] = $email;
$_SESSION['nombre'] = $nombre;

header("Location: ../perfil.php");
exit();