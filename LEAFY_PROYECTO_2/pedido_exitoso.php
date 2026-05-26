<?php
session_start();

// opcional: proteger acceso directo
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedido realizado</title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">

<style>
body{
    font-family: 'Inter', sans-serif;
    background:#f4f6f9;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

.contenedor{
    background:white;
    padding:40px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

.contenedor h1{
    color:#2D6CDF; /* azul principal */
    margin-bottom:10px;
}

.contenedor p{
    color:#555;
    margin-bottom:20px;
}

.btn-volver{
    display:inline-block;
    padding:12px 20px;
    background:#4A90E2; /* azul botón */
    color:white;
    text-decoration:none;
    border-radius:6px;
    font-weight:bold;
    transition: 0.3s ease;
}

.btn-volver:hover{
    background:#2D6CDF; /* azul más fuerte al hover */
}
</style>

</head>
<body>

<div class="contenedor">
    <h1>✅ Pedido realizado</h1>
    <p>Tu pedido fue procesado correctamente</p>

    <a href="principal.php" class="btn-volver">
        Volver a la página principal
    </a>
</div>

</body>
</html>