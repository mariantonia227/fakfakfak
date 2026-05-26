<?php
session_start();
require_once("php/conexion.php");

/* SEGURIDAD */
if(!isset($_SESSION['email']) || $_SESSION['tipo_usuario'] != "admin"){
    header("Location: login.php");
    exit();
}

/* ELIMINAR PRODUCTO */
if(isset($_GET['eliminar'])){
    $id = intval($_GET['eliminar']);

    $enlace->query("DELETE FROM productos WHERE id_producto='$id'");

    header("Location: admin_productos.php");
    exit();
}

/* LISTAR PRODUCTOS */
$productos = $enlace->query("
SELECT DISTINCT
p.id_producto,
p.nombre,
p.precio,
p.estado_producto,
n.nombre_negocio
FROM productos p
LEFT JOIN negocios n
ON p.id_negocios = n.id_negocios
ORDER BY p.id_producto DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Productos</title>
<link rel="stylesheet" href="css/admin_leafy.css">
<link rel="stylesheet" href="css/admin_productos.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="logo">Leafy</div>

    <a href="admin_leafy.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>

    <a href="admin_usuarios.php"><i class="fa-solid fa-users"></i> Usuarios</a>

    <a href="admin_negocios.php"><i class="fa-solid fa-store"></i> Negocios</a>

    <a href="admin_productos.php" class="activo">
        <i class="fa-solid fa-shirt"></i> Productos
    </a>

    <a href="admin_comentarios.php"><i class="fa-solid fa-comments"></i> Comentarios</a>

    <a href="admin_reportes.php"><i class="fa-solid fa-flag"></i> Reportes</a>

    <a href="php/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Salir</a>

</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">

        <div class="titulo">
            <h1>Productos</h1>
            <p>Administra todos los productos publicados</p>
        </div>

        <div class="perfil-admin">
            <?php echo $_SESSION['nombre']; ?>
        </div>

    </div>

    <!-- TABLA -->
    <div class="tabla-box">

        <table>
<thead>
<tr>
<th>ID</th>
<th>Producto</th>
<th>Precio</th>
<th>Negocio</th>
<th>Estado</th>
<th>Acción</th>
</tr>
</thead>

            <tbody>

            <?php if($productos->num_rows > 0): ?>

                <?php while($fila = $productos->fetch_assoc()): ?>

<tr>

<td><?php echo $fila['id_producto']; ?></td>

<td><?php echo $fila['nombre']; ?></td>

<td>$<?php echo number_format($fila['precio']); ?></td>

<td><?php echo $fila['nombre_negocio']; ?></td>

<td>
<?php if($fila['estado_producto'] == 'disponible'): ?>
<span class="estado activo">Disponible</span>
<?php else: ?>
<span class="estado agotado">Vendido</span>
<?php endif; ?>
</td>

<td>
<a href="?eliminar=<?php echo $fila['id_producto']; ?>"
onclick="return confirm('¿Eliminar producto?')"
class="btn eliminar">
Eliminar
</a>
</td>

</tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" class="vacio">
                        No hay productos registrados.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>