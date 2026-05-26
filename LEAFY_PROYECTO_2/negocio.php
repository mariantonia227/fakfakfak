<?php
session_start();
require_once("php/conexion.php");

/* VALIDAR ID */
if (!isset($_GET['id'])) {
    echo "Negocio no encontrado";
    exit();
}

$id_negocio = (int) $_GET['id'];

/* CONSULTA NEGOCIO */
$sqlNegocio = "SELECT 
n.*,
u.foto_perfil
FROM negocios n
LEFT JOIN usuarios u
ON n.id_usuario = u.id_usuarios
WHERE n.id_negocios = '$id_negocio'";

$resNegocio = $enlace->query($sqlNegocio);

if (!$resNegocio || $resNegocio->num_rows == 0) {
    echo "Negocio no existe";
    exit();
}

$negocio = $resNegocio->fetch_assoc();


$resultRating = $enlace->query("
SELECT 
AVG(calificacion) AS promedio,
COUNT(*) AS total
FROM reseñas_negocios
WHERE id_negocio='$id_negocio'
");

$rating = $resultRating->fetch_assoc();

$promedio = round($rating['promedio'], 1);
$total_reseñas = $rating['total'];

/* PRODUCTOS DEL NEGOCIO */
$sqlProductos = "SELECT 
p.*,
i.url_imagen
FROM productos p
LEFT JOIN imagenes_productos i
ON p.id_producto = i.id_producto
WHERE p.id_negocios = '$id_negocio'
ORDER BY p.fecha_publicacion DESC";

$resProductos = $enlace->query($sqlProductos);

$totalProductos = $resProductos->num_rows;
?>

<?php

$ratingQuery = $enlace->query("
SELECT 
AVG(calificacion) AS promedio,
COUNT(id_reseña) AS total_reseñas
FROM reseñas_negocios
WHERE id_negocio = '$id_negocio'
");

$rating = $ratingQuery->fetch_assoc();

$promedio = round($rating['promedio'], 1);
$total_reseñas = $rating['total_reseñas'];

?>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $negocio['nombre_negocio']; ?></title>

<link rel="stylesheet" href="css/negocio.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

<header>
<a href="javascript:history.back()" class="btn-volver">
    <i class="fa-solid fa-arrow-left"></i>
</a>
<a href="principal.php" class="logo-leafy">
    <img src="assets/IMG-20251024-WA0034-removebg-preview.png" alt="Leafy">
</a>
</header>

<section class="perfil-negocio">

<img src="<?php 
echo !empty($negocio['foto_perfil']) 
? str_replace('../','',$negocio['foto_perfil']) 
: 'assets/perfil-default.png';
?>">

<div class="info">

<h1><?php echo $negocio['nombre_negocio']; ?></h1>

<div class="stats">
<p><span><?php echo $totalProductos; ?></span> productos</p>
<p>⭐ <?php echo $promedio ? $promedio : '0.0'; ?>
<span>
(<?php echo $total_reseñas; ?> reseñas)
</span></p>
</div>

<p class="bio">
<?php 
echo !empty($negocio['descripcion']) 
? $negocio['descripcion'] 
: 'Este negocio aún no tiene descripción.';
?>
</p>

</div>

<a href="https://wa.me/57<?php echo preg_replace('/[^0-9]/', '', $negocio['telefono']); ?>?text=Hola,%20vi%20tu%20negocio%20en%20Leafy%20y%20quiero%20más%20información." 
target="_blank"
class="btn-contacto-negocio">
Contactar por WhatsApp
</a>

</section>

<section class="seccion-productos">

<div class="grid-productos">

<?php while($producto = $resProductos->fetch_assoc()) { ?>

<a href="producto.php?id=<?php echo $producto['id_producto']; ?>" class="card">

<img src="assets/<?php echo $producto['url_imagen']; ?>">

<div class="card-info">
<h3><?php echo $producto['nombre']; ?></h3>
<p class="precio">$<?php echo number_format($producto['precio'],0,',','.'); ?></p>
</div>

</a>

<?php } ?>

</div>

</section>




<!-- RESEÑAS -->

<div class="contenedor-reseñas">
<section class="seccion-reseñas">

<h2>Reseñas</h2>

<?php

$puedeComentar = false;


$id_usuario = null;

if(isset($_SESSION['email'])){

    $email = $_SESSION['email'];

    $usuario = $enlace->query("
    SELECT id_usuarios
    FROM usuarios
    WHERE email='$email'
    ");

    $usuarioData = $usuario->fetch_assoc();

    $id_usuario = $usuarioData['id_usuarios'];

}


?>

<?php if($puedeComentar): ?>

<form action="php/guardar_reseña.php" method="POST" class="form-reseña">

<input type="hidden" name="id_negocio" value="<?php echo $id_negocio; ?>">

<select name="calificacion" required>
<option value="">Calificación</option>
<option value="5">⭐⭐⭐⭐⭐</option>
<option value="4">⭐⭐⭐⭐</option>
<option value="3">⭐⭐⭐</option>
<option value="2">⭐⭐</option>
<option value="1">⭐</option>
</select>

<textarea 
name="comentario" 
placeholder="¿Qué te pareció este negocio?"
required
></textarea>

<button type="submit">
Publicar reseña
</button>

</form>

<?php else: ?>

<p class="mensaje-reseña">
Debes comprar en este negocio para dejar una reseña.
</p>

<?php endif; ?>
</section>
</div>


</body>
</html>