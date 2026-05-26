<?php
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}

require_once("php/conexion.php");

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

<!-- BOTÓN VOLVER -->
<a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'principal.php'; ?>" class="seguir-comprando">
    <i class="fa-solid fa-arrow-left"></i> Seguir comprando
</a>

<h1>🛒 Mi Carrito</h1>

<table border="1" cellpadding="10">
    <tr>
        <th>Producto</th>
        <th>Precio</th>
        <th>Acción</th>
    </tr>

<?php
$total = 0;

if (!empty($carrito)) {

    foreach ($carrito as $index => $item) {

        // 🔥 evitar errores si hay datos viejos
        if (!is_array($item)) continue;

        $id = $item['id_producto'];

        $sql = "SELECT * FROM productos WHERE id_producto = '$id'";
        $resultado = $enlace->query($sql);
        $producto = $resultado->fetch_assoc();

        if (!$producto) continue;

        // 🔥 usar precio del carrito (más eficiente)
        $total += $item['precio'];
?>

<tr>
    <td><?php echo $producto['nombre']; ?></td>
    <td>$<?php echo number_format($item['precio'], 0, ',', '.'); ?></td>
    <td>
        <a href="php/eliminar_carrito.php?id=<?php echo $index; ?>" class="btn-eliminar">
            Eliminar
        </a>
    </td>
</tr>

<?php
    }

} else {
?>

<tr>
    <td colspan="3" style="text-align:center;">
        No tienes productos en el carrito 🛒
    </td>
</tr>

<?php } ?>

<tr>
    <td><strong>Total</strong></td>
    <td colspan="2">
        <strong>$<?php echo number_format($total, 0, ',', '.'); ?></strong>
    </td>
</tr>

</table>

<br>

<a href="checkout.php" class="btn-comprar">
    🛍️ Comprar todo
</a>

</body>
</html>