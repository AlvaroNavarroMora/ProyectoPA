<?php

session_start();
include "./sesionUtils.php";
if (existeSesion()) {
    header('Location: ../login.php');
}
if(isset($_POST['iniciarSesion'])){
    if(isset($_POST['email'])){
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    }else{
        $errores[] = "Por favor, introduzca un email";
    }
    if(isset($_POST['password'])){
        $password = filter_var($_POST['password'], FILTER_SANITIZE_MAGIC_QUOTES);
    }else{
        $errores[] = "Por favor, introduzca su contraseña";
    }
}

?>