<?php
session_start();
require_once("php/conexion.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$sql = "SELECT * FROM usuarios WHERE email='$email'";
$user = $enlace->query($sql)->fetch_assoc();

$id_usuario = $user['id_usuarios'];

/* CONTADORES */

$totalFavoritos = $enlace->query("
SELECT COUNT(*) total
FROM favoritos
WHERE id_usuarios='$id_usuario'
")->fetch_assoc()['total'];

$totalCompras = $enlace->query("
SELECT COUNT(*) total
FROM pedidos
WHERE id_usuarios='$id_usuario'
")->fetch_assoc()['total'];

$totalComentarios = $enlace->query("
SELECT COUNT(*) total
FROM comentarios
WHERE id_usuarios='$id_usuario'
")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mi Perfil</title>
<link rel="stylesheet" href="css/perfil.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

<div class="perfil-layout">



<!-- SIDEBAR -->

<div class="perfil-sidebar">

<div class="volver-container">

<button onclick="history.back()" class="btn-volver">
<i class="fa-solid fa-arrow-left"></i>
</button>

</div>

<img src="<?php echo (!empty($user['foto_perfil']))
? str_replace('../','',$user['foto_perfil'])
: 'assets/perfil-default.png'; ?>"
class="perfil-img">

<h2><?php echo $user['nombre']; ?></h2>

<p class="email">
<?php echo $user['email']; ?>
</p>



<div class="stats">

<div class="stat">
<strong><?php echo $totalFavoritos; ?></strong>
<span>Favoritos</span>
</div>

<div class="stat">
<strong><?php echo $totalCompras; ?></strong>
<span>Compras</span>
</div>

<div class="stat">
<strong><?php echo $totalComentarios; ?></strong>
<span>Comentarios</span>
</div>

</div>

</div>

<!-- CONTENIDO -->

<div class="perfil-content">

<!-- TABS -->

<div class="tabs">

<button class="active" data-tab="overview">
General
</button>

<button data-tab="compras">
Compras
</button>

<button data-tab="favoritos">
Favoritos
</button>

<button data-tab="comentarios">
Comentarios
</button>

</div>

<!-- OVERVIEW -->

<div id="overview" class="tab active">

<div class="section">

<h3>Información</h3>

<div class="info-box">

<p>
<strong>Nombre:</strong>
<?php echo $user['nombre']; ?>
</p>

<p>
<strong>Email:</strong>
<?php echo $user['email']; ?>
</p>

<p>
<strong>Tipo de cuenta:</strong>
<?php echo ucfirst($user['tipo_usuario']); ?>
</p>

</div>

</div>

<div class="section">

<h3>Editar perfil</h3>

<form action="php/actualizar_perfil.php"
method="POST"
enctype="multipart/form-data">

<div class="input-group">
<label>Foto perfil</label>
<input type="file" name="foto">
</div>

<div class="input-group">
<label>Nombre</label>
<input type="text"
name="nombre"
value="<?php echo $user['nombre']; ?>">
</div>

<div class="input-group">
<label>Email</label>
<input type="email"
name="email"
value="<?php echo $user['email']; ?>">
</div>

<div class="input-group">
<label>Nueva contraseña</label>
<input type="password"
name="password"
placeholder="Nueva contraseña">
</div>

<button type="submit" class="btn-save">
Guardar cambios
</button>

</form>

</div>

</div>

<!-- COMPRAS -->

<div id="compras" class="tab">

<h3>Compras recientes</h3>

<?php

$compras = $enlace->query("
SELECT *
FROM pedidos
WHERE id_usuarios='$id_usuario'
ORDER BY id_pedido DESC
");

if($compras && $compras->num_rows > 0):

while($compra = $compras->fetch_assoc()):
?>

<div class="activity-card">

<div>
<h4>
Pedido #<?php echo $compra['id_pedido']; ?>
</h4>

<p>
$<?php echo number_format($compra['total']); ?>
</p>
</div>

<span class="estado <?php echo $compra['estado_pedido']; ?>">
<?php echo ucfirst($compra['estado_pedido']); ?>
</span>

</div>

<?php endwhile; else: ?>

<p class="empty">
No tienes compras aún 
</p>

<?php endif; ?>

</div>

<!-- FAVORITOS -->

<div id="favoritos" class="tab">

<h3>Favoritos</h3>

<?php

$favoritos = $enlace->query("
SELECT p.*, f.id_favorito
FROM favoritos f
JOIN productos p
ON f.id_producto = p.id_producto
WHERE f.id_usuarios='$id_usuario'
");

if($favoritos && $favoritos->num_rows > 0):

while($prod = $favoritos->fetch_assoc()):
?>

<div class="activity-card">

<div class="producto-info">

<img src="<?php echo $prod['imagen']; ?>">

<div>

<h4>
<?php echo $prod['nombre']; ?>
</h4>

<p>
$<?php echo number_format($prod['precio']); ?>
</p>

</div>

</div>

<a href="php/eliminar_favorito.php?id=<?php echo $prod['id_favorito']; ?>"
class="remove-btn">

Eliminar

</a>

</div>

<?php endwhile; else: ?>

<p class="empty">
No tienes favoritos 
</p>

<?php endif; ?>

</div>

<!-- COMENTARIOS -->

<div id="comentarios" class="tab">

<h3>Comentarios</h3>

<?php

$comentarios = $enlace->query("
SELECT *
FROM comentarios
WHERE id_usuarios='$id_usuario'
ORDER BY fecha DESC
");

if($comentarios && $comentarios->num_rows > 0):

while($coment = $comentarios->fetch_assoc()):
?>

<div class="activity-card">

<div>

<h4>
Comentario
</h4>

<p>
<?php echo $coment['comentario']; ?>
</p>

<small>
<?php echo $coment['fecha']; ?>
</small>

</div>

</div>

<?php endwhile; else: ?>

<p class="empty">
No has comentado aún 
</p>

<?php endif; ?>

</div>

</div>

</div>

<script>

const botones = document.querySelectorAll(".tabs button");
const tabs = document.querySelectorAll(".tab");

botones.forEach(btn => {

btn.addEventListener("click", () => {

botones.forEach(b => b.classList.remove("active"));
tabs.forEach(t => t.classList.remove("active"));

btn.classList.add("active");

document.getElementById(btn.dataset.tab).classList.add("active");

});

});

</script>

</body>
</html>