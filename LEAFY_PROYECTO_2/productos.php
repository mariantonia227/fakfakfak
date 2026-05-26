<?php
session_start(); // Para poder leer si hay sesión activa

require_once("php/conexion.php");

$where = [];
$order = "";

/* =========================
   FILTRO POR CATEGORÍA
========================= */
if (isset($_GET['categoria']) && $_GET['categoria'] != "") {
    $categoria = $enlace->real_escape_string($_GET['categoria']);
    $where[] = "categoria = '$categoria'";
}

/* =========================
   ORDEN A-Z / Z-A
========================= */
if (isset($_GET['orden'])) {
    if ($_GET['orden'] == "az") {
        $order = "ORDER BY nombre ASC";
    } elseif ($_GET['orden'] == "za") {
        $order = "ORDER BY nombre DESC";
    }
}

/* =========================
   FILTRO POR PRECIO
========================= */
if (isset($_GET['min']) && isset($_GET['max'])) {
    $min = (int) $_GET['min'];
    $max = (int) $_GET['max'];

    if ($min >= 0 && $max > 0) {
        $where[] = "precio BETWEEN $min AND $max";
    }
}

/* =========================
   ARMAR CONSULTA FINAL
========================= */
$sql = "SELECT * FROM productos";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " $order";

$resultado = $enlace->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="css/styleproduct.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <section>
      <header>
    <div class="barra-menu">
        <a href="principal.php"><img src="assets/IMG-20251024-WA0034-removebg-preview.png" alt=""></a>
        <nav>
          <div class="buscador-menu">
                    <input type="text" placeholder="Buscar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            <ul>
                <li><a href="principal.php">Inicio</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="contactanos.php">Contáctanos</a></li>
    <!-- Control de sesión -->
    <?php if (isset($_SESSION['nombre'])): ?>
    <li class="user-menu">
        <button class="user-btn">
            <?php echo $_SESSION['nombre']; ?> <i class="fa-solid fa-caret-down"></i>
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
</section>
<br>
<br>

<section>
  <div class="barra-productos">
    <div class="productos-header">
   <h3>Productos</h3>
   <div class="filtros">
    <div class="categorias-lista">
    <button class="btn-filtro btnCategorias" id="btnCategorias">Categorías</button>
        <ul class="lista-categorias" id="listaCategorias">
          <li><a href="productos.php?categoria=mujer">Mujer</a></li>
          <li><a href="productos.php?categoria=hombre">Hombre</a></li>
          <li><a href="productos.php?categoria=nino">Niño</a></li>
          <li><a href="productos.php?categoria=nina">Niña</a></li>
        </ul>
    </div>
    <div class="a-z">
    <li class="btn-filtro">
      <a href="productos.php?orden=az">A-Z</a>
  </li>
</div>
<div class="rango-precio">
  <button class="btn-filtro" onclick="togglePrecio()">Rango de precio</button>

  <form method="GET" action="productos.php" id="formPrecio" style="display:none;">
      <input type="number" name="min" placeholder="Precio mínimo">
      <input type="number" name="max" placeholder="Precio máximo">
      <button type="submit">Filtrar</button>
  </form>
</div>
  </div>
    </div>
   <hr>
  </div>
</section>

<section>
<?php
$sql = "
SELECT productos.*, imagenes_productos.url_imagen
FROM productos
LEFT JOIN imagenes_productos 
ON productos.id_producto = imagenes_productos.id_producto
";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

if (!empty($order)) {
    $sql .= " $order";
} else {
    $sql .= " ORDER BY productos.fecha_publicacion DESC";
}

$resultado = $enlace->query($sql);
?>

<div class="contenedor-productos">

<?php while($producto = $resultado->fetch_assoc()) { ?>

  <a href="producto.php?id=<?php echo $producto['id_producto']; ?>" class="producto">

    <img src="assets/<?php echo $producto['url_imagen']; ?>" alt="producto">

    <h3><?php echo $producto['nombre']; ?></h3>

    <p>$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>

    <small>
      <?php echo $producto['descripcion']; ?>
    </small>

  </a>

<?php } ?>

</div>

</section>
<br>
<br>
<br>
    <section>
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
          <h4>Donde encontrarnos</h4>
          <ul>
            <li><a href="https://www.instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
            <li><a href="https://www.facebook.com" target="_blank"><i class="fa-brands fa-square-facebook"></i></a></li>
            <li><a href="https://www.tiktok.com" target="_blank"><i class="fa-brands fa-tiktok"></i></a></li>
          </ul>
        </div>
    
        <div class="slogan">
          <div class="imagen-sloga">
        <img src="assets/Rectangle 26.png" alt="imagen del slogan"  width="600" height="300">
    </div>
          <br>
          <p><span>Leafy</span><br>¡Ropa <br> sostenible <br> para todo el <br> mundo!</p>
        </div>
      </footer>
    </section>
    <script src="js/producto.js"></script>
    <script>
function togglePrecio() {
    const form = document.getElementById("formPrecio");

    if (form.style.display === "none") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}
</script>
</body>
</html>