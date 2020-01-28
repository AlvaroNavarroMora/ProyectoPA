<?php

//funciones
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
        <script src="../js/jsEditarPerfil.js" type="text/javascript"></script>

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
                            <li><a href="cambiarImagenDePerfil.php" class="list-group-item active">Cambiar Imagen</a></li>
                            <li><a href="cambiarNombreDeUsuario.php" class="list-group-item">Cambiar Nombre</a></li>
                            <li><a href="editarDireccion.php" class="list-group-item">Direcciones</a></li>
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


                                    <br />
                                    <br />
                                    <div class="custom-file">
                                        <input name="imagen" type="file" class="custom-file-input" id="imgPerfilInput" >
                                        <label class="custom-file-label" for="customFile" id="lblSelImgPerfil">Seleccionar Imagen</label>
                                    </div>



                                    <h6 class="labelPerfil">Nombre:</h6>
                                    <p>
                                        <?php
                                        echo $nombre;
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

                                    <input class="btn btn-md btn-primary btn-block text-uppercase" type="submit" type="submit" value="Confirmar" name="cambiarImagen"></input>
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

    if (isset($_POST['cambiarImagen'])) {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0 && $_FILES['imagen']['size'] <= (5 * 1024 * 1024)) {
            $img = $_FILES['imagen']['tmp_name'];
            $imgRuta = "../img/usrFotos/" . $_SESSION['email'] . "/" . $_SESSION['email'] . "_" . time();
            $imgName = $_SESSION['email'] . "_" . time();
        } else {
            $errores[] = "Imágen no válida";
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
                $finfo->file($_FILES['imagen']['tmp_name']), array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
                ), true
                )) {
            $errores[] = "El formato de la imagen no es válido.";
        }
        if (!isset($errores)) {
            $sentencia = "UPDATE usuarios SET foto='" . $imgName . "' WHERE email='" . $_SESSION['email'] . "'";
            $result = ejecutarConsulta($sentencia);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $imgRuta);
        }
        header('Location: ./perfil.php');
    } else {
        mostrarPerfil($nombre, $email, $tipo);
    }
} else {
    header('Location: ./principal.php');
}
?>

