<?php
include "./utils/sesionUtils.php";
//Funciones
function mostrarSignUp() {
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
                                <form class="form-signin">
                                    <div class="form-label-group">
                                        <input type="text" id="inputNombre" class="form-control" placeholder="Nombre" required autofocus>
                                    </div>
                                    <br />
                                    <div class="form-label-group">
                                        <input type="email" id="inputEmail" class="form-control" placeholder="Correo electrónico" required autofocus>
                                    </div>
                                    <br />
                                    <div class="form-label-group">
                                        <input type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required>
                                    </div>
                                    <br />
                                    <div class="form-label-group">
                                        <input type="password" id="inputConfirmPassword" class="form-control" placeholder="Confirmar Contraseña" required>
                                    </div>
                                    <br />
                                    <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Registrarse</button>
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
    <?php
}
?>


<?php
session_start();
if (!existeSesion()) {
    mostrarSignUp();
} else {
    header('Location: ./principal.php');
}
?>

