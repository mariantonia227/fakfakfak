
<?php
session_start();
require_once(__DIR__ . "/php/conexion.php");

// Verificar que sea usuario tipo negocio
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != "negocio") {
    header("Location: principal.php");
    exit();
}

$email = $_SESSION['email'];

/* ============================
   1️⃣ Obtener ID del usuario
============================ */

$resultUser = $enlace->query("SELECT id_usuarios FROM usuarios WHERE email = '$email'");

if (!$resultUser || $resultUser->num_rows == 0) {
    die("Usuario no encontrado.");
}

$user = $resultUser->fetch_assoc();
$id_usuario = $user['id_usuarios'];

/* ============================
   2️⃣ Obtener ID del negocio
============================ */

$resultNegocio = $enlace->query("SELECT id_negocios FROM negocios WHERE id_usuario = '$id_usuario'");

if (!$resultNegocio || $resultNegocio->num_rows == 0) {
    die("Este usuario no tiene un negocio registrado.");
}

$negocio = $resultNegocio->fetch_assoc();
$id_negocio = $negocio['id_negocios'];

/* ============================
   3️⃣ Guardar producto
============================ */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $talla = $_POST['talla'];
    $estado = $_POST['estado_producto'];
    $categoria = $_POST['categoria'];

    // 📸 IMAGEN
    $imagen = $_FILES['imagen']['name'];
    $tmp = $_FILES['imagen']['tmp_name'];

    // nombre único
    $nombre_imagen = time() . "_" . $imagen;

    // ruta (IMPORTANTE: esta carpeta debe existir)
    $ruta = "assets/" . $nombre_imagen;

    // mover imagen
    move_uploaded_file($tmp, $ruta);

    // guardar producto
    $insert = "INSERT INTO productos 
    (nombre, descripcion, precio, talla, categoria, estado_producto, id_negocios, fecha_publicacion)
    VALUES 
    ('$nombre', '$descripcion', '$precio', '$talla', '$categoria', '$estado', '$id_negocio', NOW())";

    if ($enlace->query($insert)) {

        // obtener ID del producto
        $id_producto = $enlace->insert_id;

        // guardar imagen en tabla
        $enlace->query("INSERT INTO imagenes_productos (id_producto, url_imagen)
        VALUES ('$id_producto', '$nombre_imagen')");

        header("Location: dashboard_productos.php");
        exit();

    } else {
        echo "Error al guardar el producto: " . $enlace->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Producto</title>
    <link rel="stylesheet" href="css/dashboard_producto_nuevo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
</head>
<body>
<a href="dashboard_productos.php" class="btn-volver">← Volver</a>
<div class="main-container">


    <!-- Contenedor del formulario -->
    <div class="form-container">

        <h2>Crear Nuevo Producto</h2>

        <form method="POST" enctype="multipart/form-data" class="form-producto">

            <label>Nombre del producto</label>
            <input type="text" name="nombre" required>

            <label>Imagen del producto</label>
            <input type="file" name="imagen" accept="image/*" required>

            <label>Descripción</label>
            <textarea name="descripcion" required></textarea>

            <label>Precio</label>
            <input type="number" step="0.01" name="precio" required>

            <label>Talla</label>
            <input type="text" name="talla">
            <label>Categoría</label>

<select name="categoria">
    <option value="mujer">Mujer</option>
    <option value="hombre">Hombre</option>
    <option value="nino">Niño</option>
    <option value="nina">Niña</option>
</select>

            <label>Estado</label>
<select name="estado_producto">
    <option value="disponible">Disponible</option>
    <option value="vendido">Vendido</option>
</select>



            <button type="submit" class="btn-guardar">Guardar Producto</button>

        </form>

    </div>

</div>

</body>
</html>