<?php
function cerrarSesion() {
    unset($_SESSION['email']);
    session_destroy();
    header('Location: ./principal.php');
}

function existeSesion() {
    return isset($_SESSION['email']);
}
