<?php

//Funciones

function mostrarPantallaEditarPerfil() {
    ?>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Perfil</title>
        <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="../css/shop-homepage.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet">
        <link href="../css/principal.css" rel="stylesheet" type="text/css"/>
        <link href="../css/perfil.css" rel="stylesheet" type="text/css"/>
        <script src="../frameworks/jquery/jquery.min.js"></script>
        <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

    </head>

    <body>
        <?php
        include './header.php';
        ?>

        <!-- Page Content -->
        <main class="container">

            <div class="row">
                <!-- LISTA DE CATEGORÍAS -->
                <div class="col-lg-3">
                    <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                    <div class="list-group">
                        <a href="#" class="list-group-item active">Category 1</a>
                        <a href="#" class="list-group-item">Category 2</a>
                        <a href="#" class="list-group-item">Category 3</a>
                    </div>
                </div>
                <!-- /.col-lg-3 -->

                <div class="col-lg-9">
                    <div id="contenedorPerfil">
                        <div class="card mt-4">
                            <div class="card-body">
                                <h3 class="card-title">Perfil</h3>
                                <div id="formEditarPerfil">
                                    <form class="form-signin">
                                        <img src="../img/defaultProfile.png" alt="Imagen de perfil" id="imgPerfil"/>
                                        <br />
                                        <button id="profileButton"><i class="fas fa-folder-open"></i></button>
                                        <h6 class="labelPerfil">Nombre:</h6>
                                        <?php
                                        echo '<input name="nombre" type="text" id="inputNombre" class="form-control" placeholder="Nombre" required autofocus value="'.$_SESSION['nombre'].'">';
                                        ?>
                                        <h6 class="labelPerfil">Contraseña actual:</h6>
                                        <input name="actualpsswd" type="password" id="inputPassword" class="form-control" placeholder="Contraseña actual" required>
                                        <h6 class="labelPerfil">Nueva contraseña:</h6>
                                        <input name="newpsswd" type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required>
                                        <h6 class="labelPerfil">Confirmar Contraseña:</h6>
                                        <input name="confirmpsswd" type="password" id="inputConfirmPassword" class="form-control" placeholder="Contraseña" required>
                                        <br />
                                        <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" type="submit" value="Actualizar Perfil" name="actualizarPerfil"></input>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-9 -->
            </div>

        </main>
        <!-- /.container -->
        <?php
        include '../html/footer.html';
        ?>
    </body>

    </html>
    <?php
}
?>


<?php
session_start();
if (isset($_SESSION['email'])) {
    if (isset($_POST['actualizarPerfil'])) {
        if (isset($_POST['nombre'])) {
            $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_MAGIC_QUOTES);
        }
        if (isset($_POST['actualpsswd'])) {
            $actualpsswd = filter_var($_POST['actualpsswd'], FILTER_SANITIZE_MAGIC_QUOTES);
        }
        if (isset($_POST['newpsswd'])) {
            $newpsswd = filter_var($_POST['newpsswd'], FILTER_SANITIZE_MAGIC_QUOTES);
        }
        if (isset($_POST['confirmpsswd'])) {
            $confirmpsswd = filter_var($_POST['confirmpsswd'], FILTER_SANITIZE_MAGIC_QUOTES);
        }
    } else {
        mostrarPantallaEditarPerfil();
    }
} else {
    header('Location: ./principal.php');
}
?>
