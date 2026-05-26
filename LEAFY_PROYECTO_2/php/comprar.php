<?php
session_start();

// Vaciar carrito después de comprar
unset($_SESSION['carrito']);
?>

<h2>✅ Compra realizada con éxito</h2>
<a href="principal.php">Volver al inicio</a>