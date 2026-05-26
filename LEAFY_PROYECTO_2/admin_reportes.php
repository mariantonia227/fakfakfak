<?php
session_start();
require_once("php/conexion.php");

/* SEGURIDAD */
if(!isset($_SESSION['email']) || $_SESSION['tipo_usuario'] != "admin"){
    header("Location: login.php");
    exit();
}

/* MARCAR REVISADO */
if(isset($_GET['revisar'])){
    $id = intval($_GET['revisar']);

    $enlace->query("
    UPDATE comentarios_reportes
    SET estado_reporte='revisado'
    WHERE id_reporte='$id'
    ");

    header("Location: admin_reportes.php");
    exit();
}

/* ELIMINAR REPORTE */
if(isset($_GET['eliminar'])){
    $id = intval($_GET['eliminar']);

    $enlace->query("
    DELETE FROM comentarios_reportes
    WHERE id_reporte='$id'
    ");

    header("Location: admin_reportes.php");
    exit();
}

/* ELIMINAR COMENTARIO */
if(isset($_GET['borrar_comentario'])){
    $id = intval($_GET['borrar_comentario']);

    $enlace->query("
    DELETE FROM comentarios
    WHERE id_comentario='$id'
    ");

    header("Location: admin_reportes.php");
    exit();
}

/* LISTAR REPORTES */
$reportes = $enlace->query("
SELECT r.*, c.comentario, u.nombre
FROM comentarios_reportes r
LEFT JOIN comentarios c ON r.id_comentario = c.id_comentario
LEFT JOIN usuarios u ON r.id_usuarios = u.id_usuarios
ORDER BY 
CASE 
WHEN r.estado_reporte='pendiente' THEN 1
ELSE 2
END,
r.id_reporte DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Reportes</title>

<link rel="stylesheet" href="css/admin_leafy.css">
<link rel="stylesheet" href="css/admin_reportes.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">

<div class="logo">Leafy</div>

<a href="admin_leafy.php">
<i class="fa-solid fa-chart-line"></i> Dashboard
</a>

<a href="admin_usuarios.php">
<i class="fa-solid fa-users"></i> Usuarios
</a>

<a href="admin_negocios.php">
<i class="fa-solid fa-store"></i> Negocios
</a>

<a href="admin_productos.php">
<i class="fa-solid fa-shirt"></i> Productos
</a>

<a href="admin_comentarios.php">
<i class="fa-solid fa-comments"></i> Comentarios
</a>

<a href="admin_reportes.php" class="activo">
<i class="fa-solid fa-flag"></i> Reportes
</a>

<a href="php/logout.php">
<i class="fa-solid fa-right-from-bracket"></i> Salir
</a>

</div>

<!-- MAIN -->
<div class="main">

<!-- TOPBAR -->
<div class="topbar">

<div class="titulo">
<h1>Reportes</h1>
<p>Gestiona los comentarios reportados</p>
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
<th>Usuario</th>
<th>Comentario</th>
<th>Motivo</th>
<th>Detalle</th>
<th>Estado</th>
<th>Fecha</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

<?php if($reportes->num_rows > 0): ?>

<?php while($fila = $reportes->fetch_assoc()): ?>

<tr>

<td><?php echo $fila['id_reporte']; ?></td>

<td><?php echo $fila['nombre']; ?></td>

<td>
<?php echo !empty($fila['comentario']) ? $fila['comentario'] : 'Comentario eliminado'; ?>
</td>

<td><?php echo $fila['motivo']; ?></td>

<td><?php echo $fila['detalle']; ?></td>

<td>
<span class="estado <?php echo $fila['estado_reporte']; ?>">
<?php echo ucfirst($fila['estado_reporte']); ?>
</span>
</td>

<td><?php echo $fila['fecha']; ?></td>

<td>

<div class="acciones">

<?php if($fila['estado_reporte']=="pendiente"): ?>

<a href="?revisar=<?php echo $fila['id_reporte']; ?>" class="btn revisar">
Revisar
</a>

<?php endif; ?>

<?php if(!empty($fila['comentario'])): ?>

<a href="?borrar_comentario=<?php echo $fila['id_comentario']; ?>"
onclick="return confirm('¿Eliminar comentario?')"
class="btn eliminar">
Eliminar comentario
</a>

<?php endif; ?>

<a href="?eliminar=<?php echo $fila['id_reporte']; ?>"
onclick="return confirm('¿Cerrar reporte?')"
class="btn cerrar">
Cerrar
</a>

</div>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="8" class="vacio">
No hay reportes registrados.
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>