<?php
session_start();
require_once("php/conexion.php");

/* PUBLICAR COMENTARIO */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['email'])) {

    $comentario = trim($_POST['comentario']);

    if ($comentario != "") {

        $email = $_SESSION['email'];

        $usuario = $enlace->query("
            SELECT id_usuarios 
            FROM usuarios 
            WHERE email='$email'
        ")->fetch_assoc();

        $id_usuario = $usuario['id_usuarios'];

        $comentario = $enlace->real_escape_string($comentario);

        $enlace->query("
            INSERT INTO comentarios (id_usuarios, comentario, fecha)
            VALUES ('$id_usuario', '$comentario', NOW())
        ");
    }

    header("Location: comentarios.php?ok=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comentarios</title>

<link rel="stylesheet" href="css/comentarios.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>
<body>

<header>
<div class="barra-menu">

<a href="principal.php">
<img src="assets/IMG-20251024-WA0034-removebg-preview.png" alt="">
</a>

<nav>

<div class="buscador-menu">
<input type="text" placeholder="Buscar">
<i class="fa-solid fa-magnifying-glass"></i>
</div>

<ul>

<li><a href="principal.php">Inicio</a></li>
<li><a href="productos.php">Productos</a></li>
<li><a href="contactanos.php">Contáctanos</a></li>

<?php if (isset($_SESSION['nombre'])): ?>

<li class="user-menu">
<button class="user-btn">
<?php echo $_SESSION['nombre']; ?>
<i class="fa-solid fa-caret-down"></i>
</button>

<ul class="dropdown">
<li><a href="perfil.php">Mi perfil</a></li>
<li><a href="php/logout.php">Cerrar sesión</a></li>
</ul>
</li>

<?php else: ?>

<li><a href="login.php" class="btn-login">Iniciar sesión</a></li>

<?php endif; ?>

<a href="carrito.php" class="btn-carrito">Carrito</a>

</ul>
</nav>
</div>
</header>


<?php if(isset($_GET['ok'])): ?>
<div class="toast-comentario">
Comentario publicado
</div>
<?php endif; ?>


<section>

<div class="barra-comentarios">

<div class="comentarios-header">
<h3>Comentarios</h3>

<div class="filtro">
<button class="btn-filtrar">Más reciente</button>
</div>

</div>

<hr>

</div>


<section class="comentarios-contenedor">

<?php
$comentarios = $enlace->query("
SELECT c.*, u.nombre, u.foto_perfil
FROM comentarios c
INNER JOIN usuarios u
ON c.id_usuarios = u.id_usuarios
ORDER BY c.fecha DESC
");

if($comentarios->num_rows > 0){

while($fila = $comentarios->fetch_assoc()){

$id_mio = 0;

if(isset($_SESSION['email'])){
$yo = $enlace->query("
SELECT id_usuarios 
FROM usuarios 
WHERE email='{$_SESSION['email']}'
")->fetch_assoc();

$id_mio = $yo['id_usuarios'];
}
?>

<div class="comentario">

<div class="comentario-header">

<img 
src="<?php echo !empty($fila['foto_perfil']) ? str_replace('../','',$fila['foto_perfil']) : 'assets/perfil-default.png'; ?>" 
class="avatar">

<div class="usuario-info">
<h4><?php echo $fila['nombre']; ?></h4>
<span class="fecha"><?php echo $fila['fecha']; ?></span>
</div>

</div>

<p class="comentario-texto">
<?php echo $fila['comentario']; ?>
</p>

<div class="acciones-comentario">

<button class="btn-like" data-id="<?php echo $fila['id_comentario']; ?>">
👍
<span id="like-<?php echo $fila['id_comentario']; ?>">
<?php
$q = $enlace->query("
SELECT COUNT(*) total 
FROM comentarios_reacciones
WHERE id_comentario='{$fila['id_comentario']}'
AND tipo='like'
");
echo $q->fetch_assoc()['total'];
?>
</span>
</button>


<button class="btn-dislike" data-id="<?php echo $fila['id_comentario']; ?>">
👎
<span id="dislike-<?php echo $fila['id_comentario']; ?>">
<?php
$q2 = $enlace->query("
SELECT COUNT(*) total 
FROM comentarios_reacciones
WHERE id_comentario='{$fila['id_comentario']}'
AND tipo='dislike'
");
echo $q2->fetch_assoc()['total'];
?>
</span>
</button>


<?php if($id_mio == $fila['id_usuarios']){ ?>

<button
class="btn-editar"
onclick="abrirPopup(
'<?php echo $fila['id_comentario']; ?>',
`<?php echo htmlspecialchars($fila['comentario'], ENT_QUOTES); ?>`
)">
✏️ Editar
</button>

<?php } ?>


<button
class="btn-reportar"
onclick="abrirReporte('<?php echo $fila['id_comentario']; ?>')">
<i class="fa-solid fa-flag"></i>
Reportar
</button>

</div>
</div>

<?php
}

}else{
?>

<div class="sin-comentarios">
<h3>No hay comentarios todavía</h3>
<p>Sé la primera persona en comentar.</p>
</div>

<?php } ?>



<?php if(isset($_SESSION['email'])): ?>

<form method="POST" action="comentarios.php" class="publicar">

<div class="publicar-top">

<img
src="<?php echo isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : 'assets/perfil-default.png'; ?>"
class="avatar-publicar">

<h3>¿Qué opinas?</h3>

</div>

<textarea
name="comentario"
id="comentarioInput"
placeholder="Escribe tu opinión aquí..."
required></textarea>

<div class="acciones-publicar">

<div class="emojis">
<button type="button" class="btn-emoji" onclick="agregarEmoji('😊')">😊</button>
<button type="button" class="btn-emoji" onclick="agregarEmoji('😍')">😍</button>
<button type="button" class="btn-emoji" onclick="agregarEmoji('🔥')">🔥</button>
<button type="button" class="btn-emoji" onclick="agregarEmoji('👏')">👏</button>
</div>

<button type="submit" class="btn-publicar">
Publicar
</button>

</div>
</form>

<?php else: ?>

<div class="publicar">
<h3>Debes iniciar sesión para comentar</h3>
</div>

<?php endif; ?>

</section>
</section>



<!-- POPUP EDITAR -->
<div class="popup-editar" id="popupEditar">

<div class="popup-box">

<h3>Editar comentario</h3>

<form method="POST" action="php/editar_comentario.php">

<input type="hidden" name="id_comentario" id="editId">

<textarea
name="comentario"
id="editTexto"
required></textarea>

<div class="popup-botones">
<button type="button" onclick="cerrarPopup()">Cancelar</button>
<button type="submit">Guardar</button>
</div>

</form>
</div>
</div>



<!-- POPUP REPORTE -->
<div class="popup-reporte" id="popupReporte">

<div class="popup-box-reporte">

<h3>Reportar comentario</h3>

<form method="POST" action="php/reportar_comentario.php">

<input type="hidden" name="id_comentario" id="reporteId">

<label>Motivo</label>

<select name="motivo" required>
<option value="">Selecciona uno</option>
<option>Spam</option>
<option>Lenguaje ofensivo</option>
<option>Acoso</option>
<option>Información falsa</option>
<option>Contenido inapropiado</option>
<option>Otro</option>
</select>

<textarea
name="detalle"
placeholder="Cuéntanos qué ocurrió (opcional)"></textarea>

<div class="popup-botones">
<button type="button" onclick="cerrarReporte()">Cancelar</button>
<button type="submit">Enviar reporte</button>
</div>

</form>
</div>
</div>



<script>
function agregarEmoji(emoji){
document.getElementById("comentarioInput").value += emoji;
}
</script>


<script>
function abrirPopup(id,texto){
document.getElementById("popupEditar").style.display="flex";
document.getElementById("editId").value=id;
document.getElementById("editTexto").value=texto;
}

function cerrarPopup(){
document.getElementById("popupEditar").style.display="none";
}
</script>


<script>
function abrirReporte(id){
document.getElementById("popupReporte").style.display="flex";
document.getElementById("reporteId").value=id;
}

function cerrarReporte(){
document.getElementById("popupReporte").style.display="none";
}
</script>



<script>
document.querySelectorAll('.btn-like, .btn-dislike').forEach(btn=>{

btn.addEventListener('click', function(){

let id = this.dataset.id;
let tipo = this.classList.contains('btn-like') ? 'like' : 'dislike';

fetch('php/reaccion.php',{
method:'POST',
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:`id_comentario=${id}&tipo=${tipo}`
})
.then(res=>res.json())
.then(data=>{

document.getElementById("like-"+id).textContent = data.likes;
document.getElementById("dislike-"+id).textContent = data.dislikes;

});

});

});
</script>

</body>
</html>