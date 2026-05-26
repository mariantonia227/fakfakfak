<?php
session_start();
require_once("php/conexion.php");

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$q = $enlace->query("
SELECT nombre, tipo_usuario 
FROM usuarios 
WHERE email='$email'
");

$user = $q->fetch_assoc();

if($user['tipo_usuario'] != 'admin'){
    header("Location: principal.php");
    exit();
}

/* CONTADORES */
$usuarios = $enlace->query("SELECT COUNT(*) total FROM usuarios")->fetch_assoc()['total'];
$negocios = $enlace->query("SELECT COUNT(*) total FROM usuarios WHERE tipo_usuario='negocio'")->fetch_assoc()['total'];
$productos = $enlace->query("SELECT COUNT(*) total FROM productos")->fetch_assoc()['total'];
$comentarios = $enlace->query("SELECT COUNT(*) total FROM comentarios")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leafy Admin</title>

<link rel="stylesheet" href="css/admin_leafy.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

<div class="sidebar">

    <div class="logo">Leafy</div>

    <a href="admin_leafy.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="admin_usuarios.php"><i class="fa-solid fa-users"></i> Usuarios</a>
    <a href="admin_negocios.php"><i class="fa-solid fa-store"></i> Negocios</a>
    <a href="admin_productos.php"><i class="fa-solid fa-shirt"></i> Productos</a>
    <a href="admin_comentarios.php"><i class="fa-solid fa-comments"></i> Comentarios</a>
    <a href="admin_reportes.php"><i class="fa-solid fa-flag"></i> Reportes</a>
    <a href="php/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Salir</a>

</div>

<div class="main">

    <div class="topbar">
        <div>
            <h1>Hola, <?php echo $user['nombre']; ?> </h1>
            <p>Panel administrativo general de Leafy</p>
        </div>
                <div class="perfil-admin">
            <?php echo $_SESSION['nombre']; ?>
        </div>
    </div>

    <div class="cards">

        <div class="card">
            <i class="fa-solid fa-users"></i>
            <h3>Usuarios</h3>
            <span><?php echo $usuarios; ?></span>
        </div>

        <div class="card">
            <i class="fa-solid fa-store"></i>
            <h3>Negocios</h3>
            <span><?php echo $negocios; ?></span>
        </div>

        <div class="card">
            <i class="fa-solid fa-shirt"></i>
            <h3>Productos</h3>
            <span><?php echo $productos; ?></span>
        </div>

        <div class="card">
            <i class="fa-solid fa-comments"></i>
            <h3>Comentarios</h3>
            <span><?php echo $comentarios; ?></span>
        </div>

    </div>

    <div class="panel">

        <h2>Actividad rápida</h2>

        <div class="actividad">
            <p> Nuevos usuarios registrados</p>
            <p> Negocios activos en crecimiento</p>
            <p> Comentarios recientes</p>
            <p> Reportes pendientes por revisar</p>
        </div>

    </div>

</div>

</body>
</html>