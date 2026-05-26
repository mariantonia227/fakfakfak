<?php
session_start();
require_once("php/conexion.php");

// Validar ID
if (!isset($_GET['id'])) {
    echo "Producto no encontrado";
    exit();
}

$id = $_GET['id'];

/* ==================================
   CONSULTA PRODUCTO + IMAGEN + NEGOCIO
================================== */
$sql = "SELECT 
p.*,
i.url_imagen,
n.id_negocios,
n.nombre_negocio,
u.foto_perfil

FROM productos p

LEFT JOIN imagenes_productos i
ON p.id_producto = i.id_producto

LEFT JOIN negocios n
ON p.id_negocios = n.id_negocios

LEFT JOIN usuarios u
ON n.id_usuario = u.id_usuarios

WHERE p.id_producto = '$id'";

$resultado = $enlace->query($sql);

if (!$resultado || $resultado->num_rows == 0) {
    echo "Producto no existe";
    exit();
}

$producto = $resultado->fetch_assoc();

$id_usuario = null;

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $user = $enlace->query("
        SELECT id_usuarios 
        FROM usuarios 
        WHERE email='$email'
    ")->fetch_assoc();

    $id_usuario = $user['id_usuarios'];
}

/* ==================================
   FAVORITOS
================================== */
$favorito_activo = false;

if (isset($_SESSION['email'])) {

    $checkFav = $enlace->query("
        SELECT * FROM favoritos
        WHERE id_usuarios='$id_usuario'
        AND id_producto='$id'
    ");

    if ($checkFav && $checkFav->num_rows > 0) {
        $favorito_activo = true;
    }

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $producto['nombre']; ?></title>

<link rel="stylesheet" href="css/productos-descripcion.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

<!-- HEADER -->
<header>
<div class="barra-menu">

<a href="principal.php">
    <img src="assets/IMG-20251024-WA0034-removebg-preview.png" alt="">
</a>

<nav>

<div class="buscador-menu">
    <input type="text" placeholder="Buscar">
    <i class="fa-solid fa-magnifying-glass"></i>
</div>

<ul>

<li><a href="principal.php">Inicio</a></li>
<li><a href="productos.php">Productos</a></li>
<li><a href="contactanos.php">Contáctanos</a></li>

<?php if (isset($_SESSION['nombre'])): ?>

<li class="user-menu">
<button class="user-btn">
<?php echo $_SESSION['nombre']; ?>
<i class="fa-solid fa-caret-down"></i>
</button>

<ul class="dropdown">
<li><a href="perfil.php">Mi perfil</a></li>
<li><a href="php/logout.php">Cerrar sesión</a></li>
</ul>
</li>

<?php else: ?>

<li><a href="login.php" class="btn-login">Iniciar sesión</a></li>

<?php endif; ?>

<a href="carrito.php" class="btn-carrito">Carrito</a>

</ul>
</nav>
</div>
</header>

<?php if(isset($_GET['carrito'])): ?>
<div class="toast-carrito">
    Producto agregado al carrito
</div>
<?php endif; ?>


<!-- VOLVER -->
<div class="volver-container">
    <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'product.php'; ?>" class="btn-volver">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
</div>

<?php if(isset($_GET['carrito'])): ?>



<?php endif; ?>

<!-- PRODUCTO -->
<section class="Producto-Camiseta">

<?php if ($producto['estado_producto'] == "disponible"): ?>

<div class="producto-informacion">

<!-- IMAGEN -->
<div class="Producto-muestra">
<img src="assets/<?php echo $producto['url_imagen']; ?>" alt="">
</div>

<!-- INFO -->
<div class="descripcion">

<h2><?php echo $producto['nombre']; ?></h2>

<p><?php echo $producto['descripcion']; ?></p>

<h3 class="precio">
<span class="precio-simbolo">$</span>
<?php echo number_format($producto['precio'], 0, ',', '.'); ?>
</h3>

<!-- TALLA -->
<div class="tallas">

<h3>Talla</h3>

<div class="opcion-tallas">
<span class="btn-tallas">
<?php echo $producto['talla'] ? $producto['talla'] : 'Única'; ?>
</span>
</div>

</div>

<!-- PERFIL NEGOCIO -->
<div class="perfil-negocio">

<a href="negocio.php?id=<?php echo $producto['id_negocios']; ?>" class="perfil-negocio">

<img src="<?php
echo !empty($producto['foto_perfil'])
? str_replace('../','',$producto['foto_perfil'])
: 'assets/perfil-default.png';
?>" alt="perfil">

<span>
<?php echo !empty($producto['nombre_negocio']) ? $producto['nombre_negocio'] : 'Negocio'; ?>
</span>

</a>

</div>

<!-- BOTONES -->
<div class="contenedor-botones">

<a href="php/agregar_carrito.php?id=<?php echo $producto['id_producto']; ?>&volver=producto.php?id=<?php echo $producto['id_producto']; ?>" class="añadir-carrito">
Añadir al Carrito
</a>


<button class="corazon-btn" data-id="<?php echo $producto['id_producto']; ?>">

<i class="corazon-icon fa-heart 
<?php echo $favorito_activo ? 'fa-solid activo' : 'fa-regular'; ?>">
</i>

</button>

</div>

</div>
</div>

<?php else: ?>

<h2 style="text-align:center;">Este producto ya no está disponible</h2>

<?php endif; ?>

</section>

<section class="recomendados-producto">

<h2>Productos recomendados</h2>

<div class="contenedor-recomendados">

<?php
$recomendados = $enlace->query("
SELECT p.*, i.url_imagen
FROM productos p
LEFT JOIN imagenes_productos i 
ON p.id_producto = i.id_producto
WHERE p.id_producto != '$id'
AND p.estado_producto='disponible'
ORDER BY RAND()
LIMIT 4
");

while($rec = $recomendados->fetch_assoc()){
?>

<a href="producto.php?id=<?php echo $rec['id_producto']; ?>" class="card-recomendado">

    <img src="assets/<?php echo $rec['url_imagen']; ?>">

    <h3><?php echo $rec['nombre']; ?></h3>

    <p>$<?php echo number_format($rec['precio'],0,',','.'); ?></p>

</a>

<?php } ?>

</div>

</section>


<br><br>

<!-- FOOTER -->
<footer>

<div class="parte-abajo">
<h4>Ayuda e Información</h4>
<ul>
<li><a href="contact.php">Contactanos</a></li>
<li><a href="politica_envios.php">Politica de Envios</a></li>
<li><a href="terminos_ventas.php">Terminos y Condiciones de ventas</a></li>
<li><a href="terminos.php">Términos y condiciones</a></li>
</ul>
</div>

<div class="parte-abajo">
<h4>Sobre nosotros</h4>
<ul>
<li><a href="principal.php#info-texto">Quiénes somos</a></li>
<li><a href="principal.php#proyecto">Sobre el proyecto</a></li>
</ul>
</div>

<div class="redes">
<h4>Redes</h4>
<ul>
<li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
<li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
</ul>
</div>

<div class="slogan">
<img src="assets/Rectangle 26.png" width="300">
<p><span>Leafy</span><br>Ropa sostenible</p>
</div>

</footer>

<!-- FAVORITOS -->
<script>
document.addEventListener('DOMContentLoaded', function() {

document.querySelectorAll('.corazon-btn').forEach(btn => {

btn.addEventListener('click', function() {

const id_producto = this.dataset.id;
const icon = this.querySelector('.corazon-icon');

fetch('php/ajax_favorito.php', {
method: 'POST',
headers: {
'Content-Type': 'application/x-www-form-urlencoded'
},
body: 'id_producto=' + id_producto
})
.then(res => res.json())
.then(data => {

if (data.status === 'added') {

icon.classList.remove('fa-regular');

icon.classList.add('fa-solid');
icon.classList.add('activo');

icon.classList.remove('animar');

void icon.offsetWidth;

icon.classList.add('animar');

}

else if (data.status === 'removed') {

icon.classList.remove('fa-solid');
icon.classList.remove('activo');

icon.classList.add('fa-regular');

}

else {

alert(data.msg);

}

})
.catch(err => console.log(err));

});

});

});
</script>

</body>
</html>