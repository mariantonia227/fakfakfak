<?php
require_once("php/conexion.php");

$id = $_GET['id'];

$pedido = $enlace->query("SELECT * FROM pedidos WHERE id_pedido='$id'")->fetch_assoc();
?>

<h2>Detalle del pedido #<?php echo $pedido['id_pedido']; ?></h2>
<p>Total: $<?php echo number_format($pedido['total']); ?></p>
<p>Fecha: <?php echo $pedido['fecha']; ?></p>
<p>Estado: <?php echo $pedido['estado_pedido']; ?></p>

<a href="perfil.php">← Volver</a>