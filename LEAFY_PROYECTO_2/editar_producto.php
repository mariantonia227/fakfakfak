<?php
require_once("php/conexion.php");

$id = $_GET['id'];

$result = $enlace->query("SELECT * FROM productos WHERE id_producto='$id'");
$producto = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>editar producto</title>
    <link rel="stylesheet" href="css/editar_producto.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<a href="dashboard_productos.php" class="btn-volver">← Volver</a>
<div class="main-container">

<div class="form-container">

<h2>Editar producto</h2>

<form action="php/actualizar_producto.php" method="POST">

<input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

<label>Nombre</label>
<input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>">

<label>Descripción</label>
<textarea name="descripcion"><?php echo $producto['descripcion']; ?></textarea>

<label>Precio</label>
<input type="text" name="precio" value="<?php echo $producto['precio']; ?>">

<label>Talla</label>
<input type="text" name="talla" value="<?php echo $producto['talla']; ?>">

<label>Estado</label>
<select name="estado_producto">

<option value="disponible"
<?php if($producto['estado_producto']=="disponible") echo "selected"; ?>>
Disponible
</option>

<option value="vendido"
<?php if($producto['estado_producto']=="vendido") echo "selected"; ?>>
Vendido
</option>

</select>

<button type="submit">Guardar cambios</button>

</form>

</div>



</body>
</html>