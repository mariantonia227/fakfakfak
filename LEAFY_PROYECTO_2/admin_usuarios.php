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

/* ELIMINAR USUARIO */
if(isset($_GET['eliminar'])){
    $id = intval($_GET['eliminar']);

    $enlace->query("
    DELETE FROM usuarios
    WHERE id_usuarios='$id'
    ");

    header("Location: admin_usuarios.php?ok=1");
    exit();
}

/* BUSCADOR */
$buscar = "";

if(isset($_GET['buscar'])){
    $buscar = trim($_GET['buscar']);
}

$sql = "
SELECT id_usuarios,nombre,email,tipo_usuario,fecha_registro
FROM usuarios
WHERE 1=1
";

if($buscar != ""){
    $sql .= " AND (
        nombre LIKE '%$buscar%' OR
        email LIKE '%$buscar%'
    )";
}

$sql .= " ORDER BY id_usuarios DESC";

$usuarios = $enlace->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Usuarios</title>

<link rel="stylesheet" href="css/admin_leafy.css">
<link rel="stylesheet" href="css/admin_usuarios.css">
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
            <h1>Gestión de usuarios 👥</h1>
            <p>Hola, <?php echo $user['nombre']; ?></p>
        </div>
                <div class="perfil-admin">
            <?php echo $_SESSION['nombre']; ?>
        </div>
    </div>

    <?php if(isset($_GET['ok'])): ?>
        <div class="alerta-ok">
            Usuario eliminado correctamente.
        </div>
    <?php endif; ?>

    <div class="panel-usuarios">

        <form method="GET" class="buscador">

            <input
            type="text"
            name="buscar"
            placeholder="Buscar por nombre o correo..."
            value="<?php echo $buscar; ?>">

            <button type="submit">Buscar</button>

        </form>

        <table class="tabla-admin">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Tipo</th>
                    <th>Registro</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>

            <?php if($usuarios->num_rows > 0): ?>

                <?php while($fila = $usuarios->fetch_assoc()): ?>

                <tr>

                    <td><?php echo $fila['id_usuarios']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['email']; ?></td>

                    <td>
                        <span class="badge <?php echo $fila['tipo_usuario']; ?>">
                            <?php echo $fila['tipo_usuario']; ?>
                        </span>
                    </td>

                    <td><?php echo $fila['fecha_registro']; ?></td>

                    <td>

                        <?php if($fila['id_usuarios'] != $_SESSION['id_usuarios']): ?>

                        <a
                        href="admin_usuarios.php?eliminar=<?php echo $fila['id_usuarios']; ?>"
                        onclick="return confirm('¿Eliminar usuario?')"
                        class="btn-eliminar">
                        Eliminar
                        </a>

                        <?php else: ?>

                        <span class="tu-cuenta">Tu cuenta</span>

                        <?php endif; ?>

                    </td>

                </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6" class="sin-datos">
                        No se encontraron usuarios.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>