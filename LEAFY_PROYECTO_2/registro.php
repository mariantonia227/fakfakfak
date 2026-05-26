<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="stylesheet" href="css/registro.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>
<body>
<section class="register-screen">
  <div class="register-left">
    <img src="assets/iniciarregistrar.jpg" alt="Leafy imagen">
  </div>

  <div class="register-right">
    <div class="register-box">
      <h2>Crear cuenta en <span>Leafy</span></h2>
      <p class="slogan">Únete a nuestra comunidad sostenible 🌿</p>
      
      <!-- 🔹 FORMULARIO REGISTRO -->
      <form action="php/generador.php" method="POST">
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre completo" required>

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" placeholder="Ingresa tu correo" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Crea una contraseña" required>

        <label for="tipo">Tipo de usuario</label>
        <select id="tipo" name="tipo" required>
          <option value="cliente">Cliente</option>
          <option value="negocio">Negocio</option>
        </select>

        <!-- 🔹 CAMPOS ADICIONALES SOLO PARA NEGOCIO (AGREGADO) -->
        <div class="campos-negocio" id="campos-negocio" style="display:none;">

          <label for="nombre_negocio">Nombre del negocio</label>
          <input type="text" id="nombre_negocio" name="nombre_negocio" placeholder="Ej: Ropa Vintage Bogotá">

          <label for="telefono">Teléfono de contacto</label>
          <input type="text" id="telefono" name="telefono" placeholder="Número de contacto">

          <label for="ciudad">Ciudad</label>
          <input type="text" id="ciudad" name="direccion" placeholder="Ciudad del negocio">

          <label for="descripcion">Descripción del negocio</label>
          <textarea id="descripcion" name="descripcion" placeholder="Cuéntanos sobre tu negocio"></textarea>

        </div>


        <!-- 🔹 Botón con name="registrar" -->
        <button type="submit" name="registrar" class="btn-register">REGISTRARME</button>
      </form>

      <p class="inicia">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
      <a href="principal.php" class="volver">← Volver al inicio</a>
    </div>
  </div>
</section>
<script src="js/registrar.js"></script>

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