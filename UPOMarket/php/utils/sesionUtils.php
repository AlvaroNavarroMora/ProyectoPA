<?php

function cerrarSesion() {
    unset($_SESSION['email']);
    session_destroy();
    header('Location: ./principal.php');
}

function existeSesion() {
    return isset($_SESSION['email']);
}

function comprobarSesionActual($email) {
    return $_SESSION['email'] == $email;
}

function comprobarUsuarioContraseña($email, $password) {

    $sentencia = "SELECT password FROM usuarios WHERE email = '" . $email . "'";
    $result = ejecutarConsulta($sentencia);

    $row = mysqli_fetch_array($result);
    //obtener $psswdHash de la BD
    $psswdHash = $row['password'];
    return password_verify($password, $psswdHash);
}
