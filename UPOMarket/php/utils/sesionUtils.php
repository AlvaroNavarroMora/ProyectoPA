<?php
function cerrarSesion() {
    unset($_SESSION['usuario']);
    session_destroy();
    header('Location: ./login.php');
}

function existeSesion() {
    return isset($_SESSION['email']);
}
