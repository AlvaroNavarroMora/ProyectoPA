<?php

include './sesionUtils.php';
/* Este fichero contiene los métodos necesarios para poder procesar un nuevo usuario correctamente, asegurándonos de que efectivamente no existía */

function registrarUsuario($nombre, $email, $password, $tipo) {
    $salida = false;
    if (!existeUsuario($email)) {
        $salida = insertUsuario($nombre, $password, $email, $tipo);
    }
    return $salida;
}

/* Si la funcion devuelve true ya hay un usuario registrado con ese mail */
/*
  function existeUsuario($email) {
  $query = "SELECT * FROM `usuarios` WHERE `email`='$email'";
  $result = ejecutarConsulta($query);
  $salida = false;

  $aux = mysqli_fetch_all($result);
  if (sizeof($aux) > 0) {
  $salida = true;
  }

  return $salida;
  } */

function insertUsuario($user, $pass, $email) {
    $link = openCon();
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $query = "INSERT INTO usuarios(email, nombre, password) VALUES ('$email', '$user', '$hash')";

    mysqli_query($link, $query);

    $salida = mysqli_affected_rows($link);
    closeCon($link);

    return $salida;
}

function insertUsuarioVendedor($user, $pass, $email) {
    $link = openCon();
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $query = "INSERT INTO usuarios(email, nombre, password,tipo) VALUES ('$email', '$user', '$hash','vendedor')";

    mysqli_query($link, $query);

    $salida = mysqli_affected_rows($link);
    closeCon($link);

    return $salida;
}
