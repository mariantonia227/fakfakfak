<?php
session_start();
require_once "php/conexion.php";

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != "negocio") {
    header("Location: principal.php");
    exit();
}

$email = $_SESSION['email'];

/* =========================
   USUARIO
========================= */

$resultUser = $enlace->query("
SELECT id_usuarios, nombre, foto_perfil
FROM usuarios
WHERE email='$email'
");

$user = $resultUser->fetch_assoc();

$id_usuario = $user['id_usuarios'];
$nombre_usuario = $user['nombre'];
$foto = $user['foto_perfil'];

/* =========================
   NEGOCIO + ESTADO
========================= */

$resultNegocio = $enlace->query("
SELECT id_negocios, nombre_negocio, estado
FROM negocios
WHERE id_usuario = '$id_usuario'
");

$negocio = $resultNegocio->fetch_assoc();

$id_negocio = $negocio['id_negocios'];
$nombre_negocio = $negocio['nombre_negocio'];
$estado_negocio = $negocio['estado'];

/* =========================
   BLOQUEAR SI NO APROBADO
========================= */

if ($estado_negocio != "aprobado") {

    $_SESSION['toast'] = "❌ No puedes acceder a pedidos. Tu negocio no está aprobado.";

    header("Location: dashboard.php");
    exit();
}

/* =========================
   PEDIDOS
========================= */

$resultPedidos = $enlace->query("
SELECT *
FROM pedidos
WHERE id_negocios = '$id_negocio'
ORDER BY fecha DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pedidos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/dashboard_pedidos.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
<!-- SIDEBAR -->
<div class="sidebar">

<h2><?php echo $nombre_negocio; ?></h2>

<ul>
    <li><a href="dashboard.php">Inicio</a></li>
    <li><a href="dashboard_productos.php">Mis Productos</a></li>
    <li><a href="dashboard_pedidos.php">Pedidos</a></li>
</ul>

<a href="php/logout.php" class="logout">
    <i class="fa-solid fa-right-from-bracket"></i> Salir
</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
    <div class="user-menu">
            <div class="user-trigger" onclick="toggleMenu()">
            <img src="<?php echo (!empty($foto)) ? str_replace('../','',$foto) : 'assets/perfil-default.png'; ?>" class="perfil-img">
                <span>Bienvenido, <?php echo $nombre_usuario; ?></span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>

            <div class="dropdown" id="dropdownMenu">
                <a href="dashboard_configuracion.php">⚙ Configuración</a>
            </div>
        </div>
    </div>

    <!-- NOMBRE NEGOCIO -->
    <h2 class="negocio-nombre"><?php echo $nombre_negocio; ?></h2>

<div class="pedidos-header">
    <h3>Gestión de Pedidos</h3>
</div>
<div class="productos-filtros">
    <input type="text" id="buscarPedido" placeholder="Buscar pedido...">
</div>

<div class="tabla-pedidos">
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

<?php if ($resultPedidos && $resultPedidos->num_rows > 0) { ?>

    <?php while($pedido = $resultPedidos->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $pedido['id_pedido']; ?></td>
            <td><?php echo $pedido['id_usuarios']; ?></td>
            <td>$<?php echo number_format($pedido['total'], 0, ',', '.'); ?></td>
            <td>
                <span class="estado <?php echo $pedido['estado_pedido']; ?>">
                    <?php echo ucfirst($pedido['estado_pedido']); ?>
                </span>
            </td>
            <td><?php echo $pedido['fecha']; ?></td>
            <td>
                <a href="#" class="btn-ver">Ver</a>
            </td>
        </tr>
    <?php } ?>

<?php } else { ?>

    <tr>
    <td colspan="6" class="sin-pedidos">
        No hay pedidos registrados
    </td>
</tr>

<?php } ?>

</tbody>
    </table>
</div>

</div>
<script src="js/dashboard.js"></script>
<script src="js/dashboard_pedidos.js"></script>
</body>
</html>