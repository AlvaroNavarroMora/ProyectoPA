<?php
/*

  Esta página es en la que nos encargamos de procesar el formulario para cambiar el nombre del usuario
 */

function mostrarPerfil($nombre, $email, $tipo) {
    ?>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Perfil - UPOMarket</title>
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
                    <nav class="list-group">
                        <h4 class="text-center">Perfil De Usuario</h4>
                        <ul class="list-unstyled">
                            <li><a href="perfil.php" class="list-group-item">Ver Perfil</a></li>
                            <li><a href="cambiarImagenDePerfil.php" class="list-group-item">Cambiar Imagen</a></li>
                            <li><a href="cambiarNombreDeUsuario.php" class="list-group-item active">Cambiar Nombre</a></li>
                            <?php
                            if($_SESSION["tipo"] == "cliente" || $_SESSION["tipo"] == "vendedor") {
                            echo '<li><a href="editarDireccion.php" class="list-group-item">Direcciones de envío</a></li>';
                            }
                            ?>
                            <li><a href="cambiarContrasenia.php" class="list-group-item">Cambiar Contraseña</a></li>
                            <?php if ($tipo == "cliente") {
                                ?>
                                <li><a href="convertirseEnVendedor.php" class="list-group-item">Convertirse en vendedor</a></li>
                            <?php }
                            ?>
                        </ul>
                    </nav>
                </div>
                <!-- /.col-lg-3 -->

                <div class="col-lg-3">
                    <div id="contenedorPerfil">
                        <div class="card mt-4">
                            <div class="card-body">
                                <form class="form-signin" action="#" method="post" enctype="multipart/form-data">
                                    <?php
                                    $query = "SELECT foto FROM usuarios WHERE email='" . $_SESSION['email'] . "' AND (foto is not null)";
                                    $result = ejecutarConsulta($query);
                                    if (mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_array($result);
                                        $rutaImg = $row['foto'];
                                        echo '<img src="../img/usrFotos/' . $_SESSION['email'] . "/" . $rutaImg . '" alt="Imagen de perfil" id="imgPerfil"/>';
                                    } else {
                                        echo '<img src="../img/defaultProfile.png" alt="Imagen de perfil" id="imgPerfil"/>';
                                    }
                                    ?>


                                    <h6 class="labelPerfil">Nombre:</h6>
                                    <p>
                                        <?php
                                        echo '<input name="nombre" type="text" id="inputNombre" class="form-control" placeholder="Nombre" required autofocus value="' . $_SESSION['nombre'] . '">';
                                        ?>
                                    </p>
                                    <h6 class="labelPerfil">Email:</h6>
                                    <p>
                                        <?php
                                        echo $email;
                                        ?>
                                    </p>
                                    <h6 class="labelPerfil">Tipo de Usuario:</h6>
                                    <p>
                                        <?php
                                        echo $tipo;
                                        ?>
                                    </p>

                                    <input class="btn btn-md btn-primary btn-block text-uppercase" type="submit" type="submit" value="Confirmar" name="cambiarNombre"></input>
                                </form>
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
include "./utils/sesionUtils.php";
include "./utils/manejadorBD.php";
session_start();
if (isset($_SESSION['email'])) {

    $sql = "SELECT nombre, email, tipo FROM usuarios WHERE email='" . $_SESSION['email'] . "'";
    $result = ejecutarConsulta($sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $nombre = $row['nombre'];
        $email = $row['email'];
        $tipo = $row['tipo'];
    }

    if (isset($_POST['cambiarNombre'])) {

        if (isset($_POST['nombre'])) {
            $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
        } else {
            $errores[] = "Nombre no válido";
        }

        if (!isset($errores) && $nombre != "") {
            $sentencia = "UPDATE usuarios SET nombre='" . $nombre . "' WHERE email='" . $_SESSION['email'] . "'";
            $result = ejecutarConsulta($sentencia);
            $_SESSION['nombre'] = $nombre;
        }
        header('Location: ./perfil.php');
    } else {
        mostrarPerfil($nombre, $email, $tipo);
    }
} else {
    header('Location: ./principal.php');
}
?>

