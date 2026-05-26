<?php
session_start();
require_once(__DIR__ . "/php/conexion.php");

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != "negocio") {
    header("Location: principal.php");
    exit();
}

$email = $_SESSION['email'];

/* =========================
   USUARIO
========================= */

$resultUser = $enlace->query("
SELECT id_usuarios, nombre, email, foto_perfil
FROM usuarios
WHERE email='$email'
");

$user = $resultUser->fetch_assoc();

$id_usuario = $user['id_usuarios'];
$nombre_usuario = $user['nombre'];
$email_usuario = $user['email'];
$foto = $user['foto_perfil'];

/* =========================
   NEGOCIO
========================= */

$resultNegocio = $enlace->query("
SELECT *
FROM negocios
WHERE id_usuario='$id_usuario'
");

$negocio = $resultNegocio->fetch_assoc();

$id_negocio = $negocio['id_negocios'];
$nombre_negocio = $negocio['nombre_negocio'];
$descripcion = $negocio['descripcion'];
$direccion = $negocio['direccion'];

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Configuración</title>

<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="css/dashboard_configuracion.css">

<link rel="stylesheet"
href="https://fonts.googleapis.com/css2?family=Inter&display=swap">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h2><?php echo $nombre_negocio; ?></h2>

<ul>

<li>
<a href="dashboard.php">
Inicio
</a>
</li>

<li>
<a href="dashboard_productos.php">
Mis Productos
</a>
</li>

<li>
<a href="dashboard_pedidos.php">
Pedidos
</a>
</li>

</ul>

<a href="php/logout.php" class="logout">
<i class="fa-solid fa-right-from-bracket"></i>
Salir
</a>

</div>

<!-- MAIN -->

<div class="main">

<!-- TOPBAR -->

<div class="topbar">

<div class="user-menu">

<div class="user-trigger">

<img src="<?php echo (!empty($foto))
? str_replace('../','',$foto)
: 'assets/perfil-default.png'; ?>"
class="perfil-img">

<span>
Bienvenido, <?php echo $nombre_usuario; ?>
</span>

<i class="fa-solid fa-chevron-down"></i>

</div>

<div class="dropdown" id="dropdownMenu">

<a href="dashboard_configuracion.php">
⚙ Configuración
</a>

</div>

</div>

</div>

<!-- CONFIG -->

<h2 class="config-title">
Configuración del negocio
</h2>

<div class="config-layout">

<!-- SIDEBAR PERFIL -->

<div class="config-sidebar">

<div class="perfil-preview">

<img src="<?php echo (!empty($foto))
? str_replace('../','',$foto)
: 'assets/perfil-default.png'; ?>"
class="preview-img">

<h3>
<?php echo $nombre_negocio; ?>
</h3>

<p>
@<?php echo strtolower(str_replace(' ','',$nombre_negocio)); ?>
</p>

<span class="estado-negocio">
<?php echo ucfirst($negocio['estado']); ?>
</span>

</div>

</div>

<!-- FORMULARIO -->

<div class="config-content">

<form action="php/guardar_configuracion.php"
method="POST"
enctype="multipart/form-data">

<input type="hidden"
name="id_usuario"
value="<?php echo $id_usuario; ?>">

<input type="hidden"
name="id_negocio"
value="<?php echo $id_negocio; ?>">

<div class="input-group">

<label>
Logo del negocio
</label>

<input type="file" name="foto">

</div>

<div class="input-group">

<label>
Nombre del negocio
</label>

<input type="text"
name="nombre_negocio"
value="<?php echo $nombre_negocio; ?>">

</div>

<div class="input-group">

<label>
Nombre del usuario
</label>

<input type="text"
name="nombre_usuario"
value="<?php echo $nombre_usuario; ?>">

</div>

<div class="input-group">

<label>
Descripción
</label>

<textarea name="descripcion"><?php echo $descripcion; ?></textarea>

</div>

<div class="input-group">

<label>
Dirección
</label>

<input type="text"
name="direccion"
value="<?php echo $direccion; ?>">

</div>

<div class="input-group">

<label>
Email
</label>

<input type="email"
name="email"
value="<?php echo $email_usuario; ?>">

</div>

<div class="input-group">

<label>
Nueva contraseña
</label>

<input type="password"
name="password"
placeholder="••••••••">

</div>

<button type="submit" class="btn-save">
Guardar cambios
</button>

</form>

</div>

</div>

</div>

<script src="js/dashboard.js"></script>
<script src="js/dashboard_pedidos.js"></script>

</body>
</html>
