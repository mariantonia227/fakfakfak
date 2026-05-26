```php
<?php
session_start();
require_once("conexion.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

$id_usuario = $_POST['id_usuario'];
$id_negocio = $_POST['id_negocio'];

$nombre_usuario = $_POST['nombre_usuario'] ?? "";
$nombre_negocio = $_POST['nombre_negocio'] ?? "";
$descripcion = $_POST['descripcion'] ?? "";
$direccion = $_POST['direccion'] ?? "";
$email = $_POST['email'] ?? "";
$password = $_POST['password'] ?? "";


/* =========================
ACTUALIZAR USUARIO
========================= */

if(!empty(trim($nombre_usuario))){
    $nombre_usuario = $enlace->real_escape_string($nombre_usuario);

    $enlace->query("
        UPDATE usuarios 
        SET nombre='$nombre_usuario'
        WHERE id_usuarios='$id_usuario'
    ");
}

if(!empty(trim($email))){
    $email = $enlace->real_escape_string($email);

    $enlace->query("
        UPDATE usuarios 
        SET email='$email'
        WHERE id_usuarios='$id_usuario'
    ");
}


/* =========================
ACTUALIZAR NEGOCIO
========================= */

if(!empty(trim($nombre_negocio))){
    $nombre_negocio = $enlace->real_escape_string($nombre_negocio);

    $enlace->query("
        UPDATE negocios 
        SET nombre_negocio='$nombre_negocio'
        WHERE id_negocios='$id_negocio'
    ");
}

if(!empty(trim($descripcion))){
    $descripcion = $enlace->real_escape_string($descripcion);

    $enlace->query("
        UPDATE negocios 
        SET descripcion='$descripcion'
        WHERE id_negocios='$id_negocio'
    ");
}

if(!empty(trim($direccion))){
    $direccion = $enlace->real_escape_string($direccion);

    $enlace->query("
        UPDATE negocios 
        SET direccion='$direccion'
        WHERE id_negocios='$id_negocio'
    ");
}


/* =========================
ACTUALIZAR CONTRASEÑA
========================= */

if(!empty($password)){
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $enlace->query("
        UPDATE usuarios 
        SET password='$passwordHash'
        WHERE id_usuarios='$id_usuario'
    ");
}


/* =========================
SUBIR FOTO DE PERFIL
========================= */

if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){

    $carpeta = "../uploads/";
    if(!is_dir($carpeta)){
        mkdir($carpeta, 0777, true);
    }

    $nombreArchivo = time() . "_" . $_FILES['foto']['name'];
    $ruta = $carpeta . $nombreArchivo;

    if(move_uploaded_file($_FILES['foto']['tmp_name'], $ruta)){

        $enlace->query("
            UPDATE usuarios 
            SET foto_perfil='$ruta'
            WHERE id_usuarios='$id_usuario'
        ");

    }

}


/* =========================
VOLVER AL DASHBOARD
========================= */

header("Location: ../dashboard_configuracion.php");
exit();

}
?>
```
