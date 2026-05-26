<?php
session_start();
session_unset();
session_destroy();
header("Location: /LEAFY_PROYECTO_2/principal.php");
exit();    n
?>       