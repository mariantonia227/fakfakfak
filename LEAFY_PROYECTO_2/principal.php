<?php
session_start(); // Para poder leer si hay sesión activa
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEAFY</title>
    <link rel="stylesheet" href="css/style.css">
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
    <div class="imagen-principal">
        <img src="assets/istockphoto-1337195739-612x612 (1).jpg" alt="imagen de presentacion">
        <div class="slider-izquierdo">
    <div class="slider-track">
        <img src="assets/ropa1.jpg">
        <img src="assets/ropa2.jpg">
        <img src="assets/ropa3.jpg">

        <!-- duplicadas para efecto infinito -->
        <img src="assets/ropa1.jpg">
        <img src="assets/ropa2.jpg">
        <img src="assets/ropa3.jpg">
    </div>
</div>
        <div class="overlay-texto">
        <h1>Bienvenido a Leafy</h1>
        <p>Descubre ropa de segunda mano con estilo, historia y un impacto positivo en el planeta.</p>
    </div>
    </div>
</section>

<?php
require_once("php/conexion.php");

$result = $enlace->query("
SELECT productos.*, imagenes_productos.url_imagen
FROM productos
LEFT JOIN imagenes_productos 
ON productos.id_producto = imagenes_productos.id_producto
ORDER BY productos.fecha_publicacion DESC
LIMIT 6
");
?>

<section class="productos-recomendados">

    <h2>Productos Recomendados</h2>

    <div class="slider-productos">
        <div class="contenedor-productos">

        <?php if ($result && $result->num_rows > 0) { ?>

            <?php
            // 🔥 GUARDAMOS PRODUCTOS EN ARRAY
            $productos = [];

            while ($producto = $result->fetch_assoc()) {
                $productos[] = $producto;
            }

            // 🔥 DUPLICAMOS PARA LOOP INFINITO SUAVE
            $productos_continuos = array_merge($productos, $productos);
            ?>

            <!-- 🔥 LOOP ÚNICO (CONTINUO) -->
            <?php foreach ($productos_continuos as $producto) { ?>

                <a href="producto.php?id=<?php echo $producto['id_producto']; ?>" class="link-producto">

                    <div class="card-producto">

                        <div class="img-container">
                            <img src="assets/<?php echo $producto['url_imagen']; ?>" alt="producto">
                        </div>

                        <div class="info-producto">
                            <h3><?php echo $producto['nombre']; ?></h3>
                            <p class="precio">
                                $<?php echo number_format($producto['precio'], 0, ',', '.'); ?>
                            </p>
                        </div>

                    </div>

                </a>

            <?php } ?>

        <?php } else { ?>

            <p>No hay productos disponibles</p>

        <?php } ?>

        </div>
    </div>

</section>

<section class="info-seccion" id="info-texto">
    <div class="info-contenedor">
        
        <div class="info-texto">
            <h4>QUIÉNES SOMOS EN EL MUNDO DE LO REUTILIZABLE </h4>
            
            <h1>Dale una Seguna Oportunidad a la Moda Vintage</h1>

            <p>
              En Leafy creemos en transformar prendas olvidadas en piezas únicas listas para una nueva historia. 
              Cada compra es un paso hacia un consumo más consciente.
            </p>

            <p> <b>Promociones Exclusivas:</b> Sé el primero en conocer nuestras ofertas y nuevos productos.</p>
            <p> <b>Estilo a Tu Medida:</b> Recomendaciones personalizadas.</p>
            <p>
                Pon tu granito de arena, compra ropa reutilizable.  
                <br>
                ¡Aporta al planeta, compra ropa con historia! 
            </p>
        </div>
  <div class="info-imagen">
    <img src="assets/IMG-20251024-WA0034-removebg-preview.png" alt="leafy">
        </div>
        <br>
    </div>



<section class="Sobre-el-proyecto " id="proyecto"> 
    <h2>Leafy y Nuestra intención</h2>
    <div class="info-leafy">
        <div class="columna">
            <h3>Sobre el proyecto</h3>
            <p>
            Leafy es un proyecto tecnológico desarrollado por la empresa de software BioCode, 
            cuyo objetivo es crear una plataforma web que conecte negocios de ropa de segunda 
            mano con personas interesadas en comprar este tipo de productos de manera fácil y 
            accesible. 
            </p>
        </div>
        <div class="columna">
            <h3>Apoya al planeta con la Moda Sostenible</h3>
            <p>
            Esta plataforma conecta negocios de ropa de segunda mano con personas que buscan 
            comprar de forma fácil y accesible, impulsando la economía circular. Al dar una 
            segunda vida a las prendas, reducimos el desperdicio y contribuimos a un futuro 
            más sostenible.
            </p>
        </div>
    </div>
</section>


    <section class="testimonios-seccion">
    <div class="testimonios"></div>

    <div class="comentarios">
        <h3>comentarios</h3>
        <h1>Que dicen de nosotros</h1>
        <p>¡Explora las opiniones de nuestros clientes y deja tus sugerencias hacerca de la pagina!</p>

        <div class="estrellas">
            ★★★★★
        </div>
        <div class="btn-comentarios">
          <ul>
           <li><a href="comentarios.php">Comentarios</a></li>
          </ul>
           </div>
    </div>
</section>

<section class="info-extra">
  <h4></h4>
    <div class="info-card">
        <i class="fa-solid fa-truck"></i>
        <h3>Envíos</h3>
        <p>Realizamos envíos rápidos y seguros a todo el país. Consulta los tiempos según tu ciudad.</p>
    </div>

    <div class="info-card">
        <i class="fa-solid fa-bag-shopping"></i>
        <h3>Tus Compras</h3>
        <p>Procesamos tus pedidos con total seguridad y actualización constante del estado de tu compra.</p>
    </div>

    <div class="info-card">
        <i class="fa-solid fa-rotate-left"></i>
        <h3>Devoluciones & Reembolsos</h3>
        <p>Si tu producto no cumple tus expectativas, puedes solicitar devolución o reembolso fácilmente.</p>
    </div>

    <div class="info-card">
        <i class="fa-solid fa-headset"></i>
        <h3>Trabaja con Nosotros</h3>
        <p>Se uno de nuestros valiosos proveedores y envíanos tu hoja de vida al correo: Leafy@gmail.co.com.</p>
    </div>
</section>
</section>
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
            <li><a href="#info-texto">Quiénes somos</a></li>
            <li><a href="#proyecto">Sobre el proyecto</a></li>
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
    <div>
          <p><span>Leafy</span><br>¡Ropa <br> sostenible <br> para todo el mundo!</p>
        </div>
        </div>
      </footer>
    </section>
    <script src="js/script.js"></script>

    
</body>
</html>