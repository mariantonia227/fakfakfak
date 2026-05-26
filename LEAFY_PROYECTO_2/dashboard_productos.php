<?php
session_start();
require_once "php/conexion.php";

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != "negocio") {
    header("Location: principal.php");
    exit();
}

$email = $_SESSION['email'];

/* =========================
   OBTENER USUARIO
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
   OBTENER NEGOCIO + ESTADO
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

    $_SESSION['toast'] = "❌ No puedes acceder a productos. Tu negocio no está aprobado.";

    header("Location: dashboard.php");
    exit();
}

/* =========================
   PRODUCTOS
========================= */

$resultProductos = $enlace->query("
SELECT *
FROM productos
WHERE id_negocios = '$id_negocio'
ORDER BY fecha_publicacion DESC
");
?>





<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Productos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/dashboard_productos.css">
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

    <!-- HEADER PRODUCTOS -->
    <div class="productos-header">
        <h3>Gestión de Productos</h3>
        <a href="dashboard_producto_nuevo.php" class="btn-nuevo">+ Nuevo Producto</a>
    </div>

    <!-- BUSCADOR -->
    <div class="productos-filtros">
        <input type="text" placeholder="Buscar producto...">
    </div>

    <!-- TABLA PRODUCTOS -->
    <div class="tabla-productos">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

<?php if ($resultProductos && $resultProductos->num_rows > 0) { ?>

    <?php while($producto = $resultProductos->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $producto["id_producto"]; ?></td>
            <td><?php echo $producto["nombre"]; ?></td>
            <td>$<?php echo number_format($producto["precio"], 0, ',', '.'); ?></td>
            <td><?php echo $producto["talla"] ? $producto["talla"] : '—'; ?></td>
            <td>
<?php if ($producto["estado_producto"] == "disponible"): ?>
    <span class="estado activo">Disponible</span>
<?php else: ?>
    <span class="estado inactivo">Vendido</span>
<?php endif; ?>
            </td>
            <td>
            <a class="btn-editar" href="editar_producto.php?id=<?php echo $producto['id_producto']; ?>">Editar</a>

<a class="btn-eliminar" href="php/eliminar_producto.php?id=<?php echo $producto['id_producto']; ?>"
onclick="return confirm('¿Seguro que quieres eliminar este producto?');">
Eliminar
</a>
            </td>
        </tr>
    <?php } ?>

<?php } else { ?>

    <tr>
    <td colspan="6" class="sin-pedidos">
            No hay productos registrados
        </td>
    </tr>

<?php } ?>

</tbody>
        </table>
    </div>

</div>

<!-- SCRIPT DROPDOWN -->
<script src="js/dashboard.js"></script>

</body>
</html>