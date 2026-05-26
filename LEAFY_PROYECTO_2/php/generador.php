<?php
session_start();
require_once 'conexion.php';

// ======================
// REGISTRO
// ======================
if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tipo_usuario = $_POST['tipo'];

    // Verificar si el email ya existe
    $checkEmail = $enlace->query("SELECT email FROM usuarios WHERE email = '$email'");

    if ($checkEmail->num_rows > 0) {
        $_SESSION['toast'] = '¡El email ya está registrado!';
        header("Location: ../registro.php");
        exit();
    }

    // Insertar usuario
    $insertUsuario = $enlace->query("INSERT INTO usuarios(nombre, email, contraseña, tipo_usuario) 
                                     VALUES ('$nombre', '$email', '$password', '$tipo_usuario')");

    if ($insertUsuario) {

        // Obtener ID del usuario creado
        $id_usuario = $enlace->insert_id;

        // Si es negocio, guardar en tabla negocios
        if ($tipo_usuario == "negocio") {

            $nombre_negocio = $_POST['nombre_negocio'];
            $descripcion = $_POST['descripcion'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];

            /* 🔥 YA LO TENÍAS BIEN */
            $enlace->query("INSERT INTO negocios(
                                id_usuario,
                                nombre_negocio,
                                descripcion,
                                telefono,
                                direccion,
                                estado
                            )
                            VALUES (
                                '$id_usuario',
                                '$nombre_negocio',
                                '$descripcion',
                                '$telefono',
                                '$direccion',
                                'pendiente'
                            )");
        }
    }

    $_SESSION['toast'] = '¡Registro exitoso! Ahora inicia sesión.';
    header("Location: ../login.php");
    exit();
}

// ======================
// LOGIN
// ======================
if (isset($_POST['entrar'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Buscar usuario en la base de datos
    $result = $enlace->query("SELECT * FROM usuarios WHERE email = '$email'");

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar contraseña
        if (password_verify($password, $user['contraseña'])) {

            $_SESSION['id_usuarios'] = $user['id_usuarios'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['email']  = $user['email'];
            $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

            /* 🔥 NUEVO: obtener estado del negocio */
            if ($user['tipo_usuario'] == "negocio") {

                $resNegocio = $enlace->query("
                    SELECT estado 
                    FROM negocios 
                    WHERE id_usuario = '".$user['id_usuarios']."'
                ");

                if ($resNegocio && $resNegocio->num_rows > 0) {
                    $neg = $resNegocio->fetch_assoc();
                    $_SESSION['estado_negocio'] = $neg['estado'];
                }
            }

            // Redirecciones
            if ($user['tipo_usuario'] == "admin") {

                header("Location: ../admin_leafy.php");

            } elseif ($user['tipo_usuario'] == "negocio") {

                header("Location: ../dashboard.php");

            } else {

                header("Location: ../principal.php");
            }

            exit();
        }
    }

    // Login fallido
    $_SESSION['toast'] = "Email o contraseña incorrectos";
    header("Location: ../login.php");
    exit();
}
?>