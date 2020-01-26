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

    $sentencia = "SELECT email, password, nombre, tipo FROM usuarios WHERE email = '" . $email . "'";

    $result = ejecutarConsulta($sentencia);

    $row = mysqli_fetch_array($result);
    //obtener $psswdHash de la BD
    $psswdHash = $row['password'];
    $salida = false;
    if (password_verify($password, $psswdHash)) {
        $salida = 0;
    }

    return $salida;
}

function esAdministrador($email, $tipo) {
    $query = "SELECT * FROM `usuarios` WHERE `email`='$email' AND `tipo`='$tipo'";
    $result = ejecutarConsulta($query);
    $salida = false;

    $aux = mysqli_fetch_all($result);
    if (sizeof($aux) > 0) {
        $salida = true;
    }

    return $salida;
}
