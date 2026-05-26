<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>



  <section class="login-screen"> 
  <div class="login-left">
    <img src="assets/iniciarregistrar.jpg" alt="Leafy imagen">
  </div>

  <div class="login-right">
    <div class="login-box">
      <h2>Bienvenido a <span>Leafy</span></h2>
      <p class="slogan">Ropa sostenible para todo el mundo 🌿</p>
      
      <!-- 🔹 FORMULARIO LOGIN -->
      <form action="php/generador.php" method="POST">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" placeholder="Ingresa tu correo" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>

        <!-- 🔹 Botón con name="entrar" -->
        <button type="submit" name="entrar" class="btn-login">CONTINUAR</button>
      </form>

      <p class="registro">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
      <a href="principal.php" class="volver">← Volver al inicio</a>
    </div>
  </div>
</section>

<div id="toast-container"></div>

<script src="js/dashboard.js"></script>

<?php if(isset($_SESSION['toast'])): ?>

<script>

window.addEventListener("load", function(){

showToast(
<?php echo json_encode($_SESSION['toast']); ?>,
"success"
);

});

</script>

<?php unset($_SESSION['toast']); ?>

<?php endif; ?>


</body>
</html>