<?php
session_start();
require_once("conexion.php");

/* VALIDAR LOGIN */
if(!isset($_SESSION['email'])){
    echo json_encode([
        "error" => "login"
    ]);
    exit();
}

/* VALIDAR DATOS */
if(
!isset($_POST['id_comentario']) ||
!isset($_POST['tipo'])
){
    echo json_encode([
        "error" => "datos"
    ]);
    exit();
}

$id_comentario = intval($_POST['id_comentario']);
$tipo = $_POST['tipo'];

/* SOLO like o dislike */
if($tipo != "like" && $tipo != "dislike"){
    echo json_encode([
        "error" => "tipo"
    ]);
    exit();
}

/* OBTENER ID USUARIO */
$email = $_SESSION['email'];

$usuario = $enlace->query("
SELECT id_usuarios
FROM usuarios
WHERE email='$email'
")->fetch_assoc();

$id_usuario = $usuario['id_usuarios'];


/* VER SI YA REACCIONÓ */
$existe = $enlace->query("
SELECT *
FROM comentarios_reacciones
WHERE id_usuarios='$id_usuario'
AND id_comentario='$id_comentario'
");

/* SI YA EXISTE */
if($existe->num_rows > 0){

$actual = $existe->fetch_assoc();

/* si es la misma reacción = quitar */
if($actual['tipo'] == $tipo){

$enlace->query("
DELETE FROM comentarios_reacciones
WHERE id_usuarios='$id_usuario'
AND id_comentario='$id_comentario'
");

}else{

/* cambiar like por dislike o viceversa */
$enlace->query("
UPDATE comentarios_reacciones
SET tipo='$tipo'
WHERE id_usuarios='$id_usuario'
AND id_comentario='$id_comentario'
");

}

}else{

/* insertar nueva reacción */
$enlace->query("
INSERT INTO comentarios_reacciones
(id_comentario,id_usuarios,tipo)
VALUES
('$id_comentario','$id_usuario','$tipo')
");

}


/* CONTAR LIKES */
$q1 = $enlace->query("
SELECT COUNT(*) total
FROM comentarios_reacciones
WHERE id_comentario='$id_comentario'
AND tipo='like'
");

$likes = $q1->fetch_assoc()['total'];


/* CONTAR DISLIKES */
$q2 = $enlace->query("
SELECT COUNT(*) total
FROM comentarios_reacciones
WHERE id_comentario='$id_comentario'
AND tipo='dislike'
");

$dislikes = $q2->fetch_assoc()['total'];


/* RESPUESTA JSON */
echo json_encode([
    "likes" => $likes,
    "dislikes" => $dislikes
]);
?>