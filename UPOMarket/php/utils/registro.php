<?php

include './manejadorBD.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function registrarUsuario($nombre, $email, $password, $tipo) {
    $salida = false;
    if (!existeUsuario($email)) {
        $salida = insertUsuario($nombre, $password, $email, $tipo);
    }
    return $salida;
}

/* Si la funcion devuelve true ya hay un usuario registrado con ese mail */

function existeUsuario($email) {
    $query = "SELECT * FROM `usuarios` WHERE `email`='$email'";
    $result = ejecutarConsulta($query);
    $salida = false;

    $aux = mysqli_fetch_all($result);
    if (sizeof($aux) > 0) {
        $salida = true;
    }

    return $salida;
}

function insertUsuario($user, $pass, $email) {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $query = "INSERT INTO `usuarios`(`nombre`, `clave`, `email`) VALUES ('$user', '$hash', '$email')";

    ejecutarConsulta($query);
    $salida = existeUsuario($user, $pass);

    return $salida;
}

//Abro la conexion, ejecuto la consulta pasada por parametros y devuelvo el resultado tras cerrar la conexion
function ejecutarConsulta($query) {
    $link = openCon();
    $result = mysqli_query($link, $query);

    closeCon($link);
    return $result;
}

/* Añadir nombre del formulario registro */
if (isset($_POST['btnRegistrar'])) {
    $user = filter_var($_POST['usuario'], FILTER_SANITIZE_MAGIC_QUOTES);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_MAGIC_QUOTES);
    $passwordConfirm = filter_var($_POST['passwordConfirm'], FILTER_SANITIZE_MAGIC_QUOTES);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if ($user === false || $password === false || $passwordConfirm === false || $email === false) {
        $errores[] = "Error con los datos del formulario";
    }

    if (strlen(trim($user)) < 1) {
        $errores[] = "El campo usuario es obligatorio";
    }
    if (strlen(trim($password)) < 1) {
        $errores[] = "El campo contrasenia es obligatorio";
    }
    if (strlen(trim($passwordConfirm)) < 1) {
        $errores[] = "El campo contrasenia es obligatorio";
    }
    if ($password !== $passwordConfirm) {
        $errores[] = "Las contraseñas no coinciden";
    }
    if (strlen(trim($email)) < 1) {
        $errores[] = "El campo email es obligatorio";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El campo email esta mal rellenado";
    }

    if (isset($errores)) {
        /*
         * Tratamiento de los errores
         */
    } else {
        //Si la insercion falla $credenciales=false, sino $credenciales tendrá el nombre de usuario y su id para guardar la sesion
        if (!existeUsuario($email)) {
            $seHaInsertado = insertUsuario($user, $pass, $email);
            if ($seHaInsertado) {
                $_SESSION['idUser'] = $email;
                $_SESSION['user'] = $user;
                $path = "../img/usrFotos/$email";/*Carpeta para almacenar fotos de los usuarios si hiciese falta*/
                mkdir($path);
                header("Location: ./principal.php");
            }else{
                $errores[] = "Error al registrar, intentelo de nuevo más tarde";
            }
        } else {
            $errores[] = "usuario ya registrado";
        }
    }
}