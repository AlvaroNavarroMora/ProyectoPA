<?php
include "./utils/sesionUtils.php";
include "./utils/manejadorBD.php";

//Funciones
function cargarLogin($errores = null) {
    ?>
    <!DOCTYPE html>

    <html>
        <head>
            <title>Iniciar Sesión - UPOMarket</title>
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
                                <h4 class="card-title text-center">Inicio de sesión</h4>
                                <?php
                                if (isset($errores)) {
                                    echo "<p id='mensajeErrores'>";
                                    foreach ($errores as $error) {
                                        echo $error . "<br />";
                                    }
                                    echo "</p>";
                                }
                                ?>
                                <form class="form-signin" action="#" method="post">
                                    <div class="form-label-group">
                                        <?php
                                        if (isset($_COOKIE['emailUsuarioUPOMKT'])) {
                                            $cookieEmail = filter_var($_COOKIE['emailUsuarioUPOMKT'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                            echo '<input name="email" type="email" id="inputEmail" class="form-control" placeholder="Correo electrónico" value="' . $cookieEmail . '" required autofocus>';
                                        } else {
                                            ?>
                                            <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Correo electrónico" required autofocus>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <br />
                                    <div class="form-label-group">
                                        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required>
                                    </div>
                                    <br />
                                    <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" value="Iniciar Sesión" name="iniciarSesion"></input>
                                    <br />
                                    <p>¿Aún no tienes una cuenta? <a href="signUp.php">¡Regístrate!</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </html>

    <?php
}
?>

<?php
session_start();
if (!isset($_SESSION['email'])) {
    if (isset($_POST['iniciarSesion'])) {
        if (isset($_POST['email'])) {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        } else {
            $errores[] = "Por favor, introduzca un email";
        }
        if (isset($_POST['password'])) {
            $password = filter_var($_POST['password'], FILTER_SANITIZE_MAGIC_QUOTES);
        } else {
            $errores[] = "Por favor, introduzca su contraseña";
        }

        if (!isset($errores)) {
            //comprobamos que existe el usuario
            $sentencia = "SELECT email, password, nombre, tipo FROM usuarios WHERE email = '" . $email . "'";
            $result = ejecutarConsulta($sentencia);
            if (mysqli_num_rows($result) > 0) {
                //crear la cookie del email del usuario
                setcookie("emailUsuarioUPOMKT", $email, 1000 * 60 * 60 * 24 * 15);
                $row = mysqli_fetch_array($result);
                //obtener $psswdHash de la BD
                $psswdHash = $row['password'];

                if (password_verify($password, $psswdHash)) {
                    //obtener $emailBD de la BD
                    $emailBD = $row['email'];
                    //obtener $nombre de la BD
                    $nombre = $row['nombre'];
                    //obtener $tipo de la BD
                    $tipo = $row['tipo'];
                    $_SESSION['email'] = $emailBD;
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['tipo'] = $tipo;
                    header('Location: ./principal.php');
                } else {
                    $errores[] = "Credenciales no válidos";
                }
            } else {
                $errores[] = "Credenciales no válidos";
            }
        }
        if (isset($errores)) {
            cargarLogin($errores);
        }
    } else {
        cargarLogin();
    }
} else {
    header('Location: ./principal.php');
}
?>
