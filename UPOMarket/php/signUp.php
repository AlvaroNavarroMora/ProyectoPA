<?php
include "./utils/sesionUtils.php";
include './utils/registro.php';
include './utils/manejadorBD.php';

session_start();
if (existeSesion()) {
    header('Location: ./principal.php');
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

     if(empty($errores)) {
        //Si la insercion falla $credenciales=false, sino $credenciales tendrá el nombre de usuario y su id para guardar la sesion
        if (insertUsuario($user, $password, $email) > 0) {
            $_SESSION['idUser'] = $email;
            $_SESSION['user'] = $user;
            $path = "../img/usrFotos/$email"; /* Carpeta para almacenar fotos de los usuarios si hiciese falta */
            mkdir($path);
            header("Location: ./principal.php");
        } else {
            $errores[] = "Usuario ya registrado";
        }
    }
    if(!empty($errores)) {
        foreach ($errores as $e) {
            echo "$e<br>";
        }
    }
}
?>
<!DOCTYPE html>

<html>
    <head>
        <title>Registro</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../frameworks/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="../css/login.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                    <div class="card card-signin my-5">
                        <div class="card-body">
                            <a href="principal.php">
                                <img id="logo" src="../img/upomarket.png" alt="Logo de UPOMarket"/>
                            </a>
                            <h4 class="card-title text-center">Registro</h4>
                            <form class="form-signin" action="#" method="post">
                                <div class="form-label-group">
                                    <input name="usuario" type="text" id="inputNombre" class="form-control" placeholder="Nombre" required autofocus>
                                </div>
                                <br />
                                <div class="form-label-group">
                                    <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Correo electrónico" required autofocus>
                                </div>
                                <br />
                                <div class="form-label-group">
                                    <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required>
                                </div>
                                <br />
                                <div class="form-label-group">
                                    <input name="passwordConfirm" type="password" id="inputConfirmPassword" class="form-control" placeholder="Confirmar Contraseña" required>
                                </div>
                                <br />
                                <button name="btnRegistrar" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Registrarse</button>
                                <br />
                                <p>¿Ya tienes una cuenta? <a href="login.php">¡Inicia sesión!</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

