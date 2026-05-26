<?php
session_start(); // Para poder leer si hay sesión activa
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact us</title>
    <link rel="stylesheet" href="css/contactus.css">
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
<br>
<section class="contacto">
  <h2>Contáctanos</h2>
  <form class="form-contacto">
    <div class="grupo-input">
      <label>Tu nombre</label>
      <input type="text" placeholder="Ingresa tu nombre" required>
    </div>

    <div class="grupo-input">
      <label>Tu correo</label>
      <input type="email" placeholder="Ingresa tu correo" required>
    </div>

    <div class="grupo-input">
      <label>Mensaje</label>
      <textarea placeholder="Escribe tu mensaje" required></textarea>
    </div>

    <button type="submit" class="btn-enviar">Enviar</button>
  </form>
</section>
<br>
<br>
<section>
    <!-- Aquí va lo que tenga esta página -->
</section>
<br>
    <section>
      <footer>
        <div class="parte-abajo">
          <h4>Ayuda e Información</h4>
          <ul>
            <li><a href="#">Contactanos</a></li>
            <li><a href="#">Politica de Envios</a></li>
            <li><a href="#">Terminos y Condiciones de ventas</a></li>
            <li><a href="#">Términos y condiciones</a></li>
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
    <script src="js/script.js"></script>
</body>
</html>