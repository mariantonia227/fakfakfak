<?php
session_start();
require_once("php/conexion.php");

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$qUser = $enlace->query("
SELECT nombre, tipo_usuario
FROM usuarios
WHERE email='$email'
");

$user = $qUser->fetch_assoc();

if($user['tipo_usuario'] != 'admin'){
    header("Location: principal.php");
    exit();
}

/* ELIMINAR */
if(isset($_GET['eliminar'])){
    $id = intval($_GET['eliminar']);

    $enlace->query("
    DELETE FROM comentarios
    WHERE id_comentario='$id'
    ");

    header("Location: admin_comentarios.php?ok=1");
    exit();
}

/* BUSCADOR */
$buscar = "";

if(isset($_GET['buscar'])){
    $buscar = trim($_GET['buscar']);
}

$sql = "
SELECT c.*, u.nombre
FROM comentarios c
INNER JOIN usuarios u
ON c.id_usuarios = u.id_usuarios
";

if($buscar != ""){
    $sql .= " WHERE c.comentario LIKE '%$buscar%'
              OR u.nombre LIKE '%$buscar%'";
}

$sql .= " ORDER BY c.fecha DESC";

$comentarios = $enlace->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Comentarios</title>

<link rel="stylesheet" href="css/admin_leafy.css">
<link rel="stylesheet" href="css/admin_comentarios.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

<div class="sidebar">

    <div class="logo">Leafy</div>

    <a href="admin_leafy.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="admin_usuarios.php"><i class="fa-solid fa-users"></i> Usuarios</a>
    <a href="admin_negocios.php"><i class="fa-solid fa-store"></i> Negocios</a>
    <a href="admin_productos.php"><i class="fa-solid fa-shirt"></i> Productos</a>
    <a href="admin_comentarios.php"><i class="fa-solid fa-comments"></i> Comentarios</a>
    <a href="admin_reportes.php"><i class="fa-solid fa-flag"></i> Reportes</a>
    <a href="php/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Salir</a>

</div>

<div class="main">

    <div class="topbar">
        <div>
            <h1>Moderación de comentarios 💬</h1>
            <p>Hola, <?php echo $user['nombre']; ?></p>
        </div>
                <div class="perfil-admin">
            <?php echo $_SESSION['nombre']; ?>
        </div>
    </div>

    <?php if(isset($_GET['ok'])): ?>
        <div class="alerta-ok">
            Comentario eliminado correctamente.
        </div>
    <?php endif; ?>

    <div class="panel-comentarios">

        <form method="GET" class="buscador">

            <input
            type="text"
            name="buscar"
            placeholder="Buscar comentario o usuario..."
            value="<?php echo $buscar; ?>">

            <button type="submit">Buscar</button>

        </form>

        <table class="tabla-admin">

            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Comentario</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>

            <?php if($comentarios->num_rows > 0): ?>

                <?php while($fila = $comentarios->fetch_assoc()): ?>

                <tr>

                    <td><?php echo $fila['nombre']; ?></td>

                    <td><?php echo $fila['comentario']; ?></td>

                    <td><?php echo $fila['fecha']; ?></td>

                    <td>
                        <a
                        href="admin_comentarios.php?eliminar=<?php echo $fila['id_comentario']; ?>"
                        onclick="return confirm('¿Eliminar comentario?')"
                        class="btn-eliminar">
                        Eliminar
                        </a>
                    </td>

                </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="4" class="sin-datos">
                        No se encontraron comentarios.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>