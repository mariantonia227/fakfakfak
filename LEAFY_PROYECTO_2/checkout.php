<link rel="stylesheet" href="css/checkout.css">
<a href="carrito.php" class="btn-volver">← Volver al carrito</a>
<div class="checkout-container">

<form action="php/procesar_pedido.php" method="POST">

<h2>Finalizar compra</h2>

<h3>Datos de envío</h3>

<input type="text" name="nombre" placeholder="Nombre completo" required>
<input type="text" name="direccion" placeholder="Dirección" required>
<input type="text" name="telefono" placeholder="Teléfono" required>

<h3>Método de pago</h3>

<select name="metodo_pago" required>
    <option value="contra_entrega">Contra entrega</option>
    <option value="transferencia">Transferencia</option>
</select>

<button type="submit">Confirmar pedido</button>

</form>

</div>