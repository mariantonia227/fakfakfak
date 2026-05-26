<?php
session_start();
require_once(__DIR__ . "/php/conexion.php");

/* ============================
   VERIFICAR SESIÓN
============================ */

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != "negocio") {
    header("Location: principal.php");
    exit();
}

$email = $_SESSION['email'];

/* ============================
   1️⃣ USUARIO
============================ */

$resultUser = $enlace->query("
SELECT id_usuarios, nombre, foto_perfil
FROM usuarios
WHERE email = '$email'
");

$user = $resultUser->fetch_assoc();

$id_usuario = $user['id_usuarios'];
$nombre_usuario = $user['nombre'];
$foto = $user['foto_perfil'];

/* ============================
   2️⃣ NEGOCIO + ESTADO
============================ */

$resultNegocio = $enlace->query("
SELECT id_negocios, nombre_negocio, estado
FROM negocios
WHERE id_usuario = '$id_usuario'
");

$negocio = $resultNegocio->fetch_assoc();

$id_negocio = $negocio['id_negocios'];
$nombre_negocio = $negocio['nombre_negocio'];
$estado_negocio = $negocio['estado'];

/* ============================
   BLOQUEO
============================ */

$bloqueado = false;

if ($estado_negocio != "aprobado") {
    $bloqueado = true;
}

/* ============================
   MÉTRICAS
============================ */

$totalProductos = 0;
$totalPedidos = 0;
$totalPendientes = 0;
$totalVentas = 0;

if (!$bloqueado) {

    $resultProductos = $enlace->query("
    SELECT COUNT(*) as total
    FROM productos
    WHERE id_negocios = '$id_negocio'
    ");
    $totalProductos = $resultProductos->fetch_assoc()['total'];

    $resultPedidos = $enlace->query("
    SELECT COUNT(*) as total
    FROM pedidos
    WHERE id_negocios = '$id_negocio'
    ");
    $totalPedidos = $resultPedidos->fetch_assoc()['total'];

    $resultPendientes = $enlace->query("
    SELECT COUNT(*) as total
    FROM pedidos
    WHERE id_negocios = '$id_negocio'
    AND estado_pedido = 'pendiente'
    ");
    $totalPendientes = $resultPendientes->fetch_assoc()['total'];

    $resultVentas = $enlace->query("
    SELECT SUM(total) as total
    FROM pedidos
    WHERE id_negocios = '$id_negocio'
    AND estado_pedido = 'completado'
    ");
    $totalVentas = $resultVentas->fetch_assoc()['total'];

    if (!$totalVentas) {
        $totalVentas = 0;
    }
}


/* ============================
   📊 VENTAS ÚLTIMOS 7 DÍAS
============================ */

$ventas_dias = [];
$labels = [];

for($i = 6; $i >= 0; $i--){

    $fecha = date("Y-m-d", strtotime("-$i days"));
    $labels[] = date("D", strtotime($fecha)); // Lun, Mar, etc

    $query = $enlace->query("
    SELECT SUM(total) as total
    FROM pedidos
    WHERE id_negocios = '$id_negocio'
    AND estado_pedido = 'completado'
    AND DATE(fecha) = '$fecha'
    ");

    $row = $query->fetch_assoc();

    $ventas_dias[] = ($row['total']) ? (float)$row['total'] : 0;
}

/* ============================
   📊 ANALYTICS AVANZADO
============================ */

// Arrays
$ventas_actual = [];
$ventas_anterior = [];
$labels = [];

$total_actual = 0;
$total_anterior = 0;

// Últimos 7 días vs semana anterior
for($i = 6; $i >= 0; $i--){

    $fecha_actual = date("Y-m-d", strtotime("-$i days"));
    $fecha_anterior = date("Y-m-d", strtotime("-".($i+7)." days"));

    $labels[] = date("D", strtotime($fecha_actual));

    // Semana actual
    $q1 = $enlace->query("
    SELECT SUM(total) as total
    FROM pedidos
    WHERE id_negocios = '$id_negocio'
    AND estado_pedido = 'completado'
    AND DATE(fecha) = '$fecha_actual'
    ");

    $r1 = $q1->fetch_assoc();
    $v1 = ($r1['total']) ? (float)$r1['total'] : 0;

    $ventas_actual[] = $v1;
    $total_actual += $v1;

    // Semana pasada
    $q2 = $enlace->query("
    SELECT SUM(total) as total
    FROM pedidos
    WHERE id_negocios = '$id_negocio'
    AND estado_pedido = 'completado'
    AND DATE(fecha) = '$fecha_anterior'
    ");

    $r2 = $q2->fetch_assoc();
    $v2 = ($r2['total']) ? (float)$r2['total'] : 0;

    $ventas_anterior[] = $v2;
    $total_anterior += $v2;
}

// 📈 Crecimiento %
$crecimiento = 0;

if($total_anterior > 0){
    $crecimiento = (($total_actual - $total_anterior) / $total_anterior) * 100;
}



?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard - <?php echo $nombre_negocio; ?></title>

<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

<h2><?php echo $nombre_negocio; ?></h2>

<ul>
    <li><a href="dashboard.php">Inicio</a></li>
    <li><a href="dashboard_productos.php">Mis Productos</a></li>
    <li><a href="dashboard_pedidos.php">Pedidos</a></li>
</ul>

<a href="php/logout.php" class="logout">
    <i class="fa-solid fa-right-from-bracket"></i> Salir
</a>
</div>

<!-- MAIN -->
<div class="main">

<!-- TOPBAR -->
<div class="topbar">

<div class="user-menu">

<div class="user-trigger" onclick="toggleMenu()">

<img src="<?php echo (!empty($foto)) ? str_replace('../','',$foto) : 'assets/perfil-default.png'; ?>" class="perfil-img">

<span>Bienvenido, <?php echo $nombre_usuario; ?></span>

<i class="fa-solid fa-chevron-down"></i>

</div>

<div class="dropdown" id="dropdownMenu">
<a href="dashboard_configuracion.php">⚙ Configuración</a>
</div>

</div>
</div>

<h2 class="negocio-nombre"><?php echo $nombre_negocio; ?></h2>

<!-- MENSAJES SEGÚN ESTADO -->

<?php if($estado_negocio == "pendiente"): ?>

<div class="alert pendiente">
⏳ Tu negocio está en revisión. Cuando sea aprobado podrás usar todas las funciones.
</div>

<?php elseif($estado_negocio == "rechazado"): ?>

<div class="alert rechazado">
❌ Tu solicitud fue rechazada. Contacta soporte.
</div>

<?php elseif($estado_negocio == "suspendido"): ?>

<div class="alert suspendido">
🚫 Tu negocio está suspendido temporalmente.
</div>

<?php endif; ?>

<!-- CARDS -->
<div class="cards">

<div class="card">
<h3>📦 Productos</h3>
<p><?php echo $totalProductos; ?></p>
</div>

<div class="card">
<h3>🛒 Pedidos</h3>
<p><?php echo $totalPedidos; ?></p>
</div>

<div class="card">
<h3>⏳ Pendientes</h3>
<p><?php echo $totalPendientes; ?></p>
</div>

<div class="card">
<h3>💰 Ventas</h3>
<p>$<?php echo number_format($totalVentas,0,',','.'); ?></p>
</div>

</div>

<div class="grid-dashboard">

    <!-- GRAFICA -->
    <div class="grafica-box">
        <h3>📊 Ventas (últimos 7 días)</h3>
        <canvas id="graficaVentas"></canvas>
    </div>

    <!-- NOTIFICACIONES -->
    <div class="notificaciones">
        <h3>🔔 Notificaciones</h3>

        <ul>
            <?php if(!$bloqueado): ?>

                <?php
                $notis = $enlace->query("
                SELECT * FROM pedidos
                WHERE id_negocios = '$id_negocio'
                ORDER BY fecha DESC
                LIMIT 5
                ");

                if($notis && $notis->num_rows > 0):
                    while($n = $notis->fetch_assoc()):
                ?>

                <li>
                    🛒 Nuevo pedido #<?php echo $n['id_pedido']; ?>
                </li>

                <?php endwhile; else: ?>
                    <li class="vacio">No hay notificaciones</li>
                <?php endif; ?>

            <?php else: ?>
                <li class="vacio">Funciones bloqueadas</li>
            <?php endif; ?>
        </ul>

    </div>

</div>


<div class="analytics-resumen">

    <div class="mini-card">
        <h4>Semana actual</h4>
        <p>$<?php echo number_format($total_actual,0,',','.'); ?></p>
    </div>

    <div class="mini-card">
        <h4>Semana pasada</h4>
        <p>$<?php echo number_format($total_anterior,0,',','.'); ?></p>
    </div>

    <div class="mini-card">
        <h4>Crecimiento</h4>
        <p class="<?php echo ($crecimiento >= 0) ? 'positivo' : 'negativo'; ?>">
            <?php echo round($crecimiento,1); ?>%
        </p>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labels = <?php echo json_encode($labels); ?>;
const actual = <?php echo json_encode($ventas_actual); ?>;
const anterior = <?php echo json_encode($ventas_anterior); ?>;

new Chart(document.getElementById('graficaVentas'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Esta semana',
                data: actual,
                tension: 0.4,
                fill: true
            },
            {
                label: 'Semana pasada',
                data: anterior,
                tension: 0.4,
                borderDash: [5,5]
            }
        ]
    },
    options: {
        responsive: true
    }
});
</script>


<div id="toast-container"></div>
<script src="js/dashboard.js"></script>

<?php if(isset($_SESSION['toast'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    showToast("<?php echo $_SESSION['toast']; ?>", "error");
});
</script>
<?php unset($_SESSION['toast']); ?>
<?php endif; ?>


</body>
</html>