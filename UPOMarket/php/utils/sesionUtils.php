<?php

function cerrarSesion() {
    session_destroy();
    header('Location: ./login.php');
}

function existeSesion() {
    return isset($_SESSION['email']);
}

?>
