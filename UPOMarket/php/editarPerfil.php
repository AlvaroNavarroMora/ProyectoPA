<?php

//Funciones

function mostrarPantallaEditarPerfil($errores = null) {
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
                                <?php
                                if ($errores != null) {
                                    echo "<p id='mensajeErrores'>";
                                    foreach ($errores as $error) {
                                        echo $error . "<br />";
                                    }
                                    echo "</p>";
                                }
                                ?>
                                <div id="formEditarPerfil">
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
                                        <?php
                                        echo '<input name="nombre" type="text" id="inputNombre" class="form-control" placeholder="Nombre" required autofocus value="' . $_SESSION['nombre'] . '">';
                                        if ($_SESSION['tipo'] === 'cliente') {
                                            echo '<h6">Convertirse en vendedor: </h6>';
                                            echo '<span><input name="vendedor" type="checkbox" id="checkboxVendedorPerfil"></span>';
                                        }
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
include "./utils/sesionUtils.php";
include "./utils/manejadorBD.php";
session_start();
if (isset($_SESSION['email'])) {
    if (isset($_POST['actualizarPerfil'])) {
        if (isset($_POST['nombre'])) {
            $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_MAGIC_QUOTES);
        } else {
            $errores[] = "Nombre no válido";
        }
        if (isset($_POST['actualpsswd'])) {
            $actualpsswd = filter_var($_POST['actualpsswd'], FILTER_SANITIZE_MAGIC_QUOTES);
        } else {
            $errores[] = "Contraseña no válida";
        }
        if (isset($_POST['newpsswd'])) {
            $newpsswd = filter_var($_POST['newpsswd'], FILTER_SANITIZE_MAGIC_QUOTES);
        } else {
            $errores[] = "Nueva contraseña no válida";
        }
        if (isset($_POST['confirmpsswd'])) {
            $confirmpsswd = filter_var($_POST['confirmpsswd'], FILTER_SANITIZE_MAGIC_QUOTES);
        } else {
            $errores[] = "Nueva contraseña no válida";
        }
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
                )){
            $errores[] = "El formato de la imagen no es válido.";
        }
        if (!isset($errores) && $newpsswd === $confirmpsswd && $newpsswd != "" && $nombre != "") {
            $sentencia = "SELECT password FROM usuarios WHERE email='" . $_SESSION['email'] . "'";
            $result = ejecutarConsulta($sentencia);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $psswdHash = $row['password'];
                if (password_verify($actualpsswd, $psswdHash)) {
                    $newpsswdhash = password_hash($actualpsswd, PASSWORD_DEFAULT);
                    if (isset($_POST['vendedor'])) {
                        $sentencia = "UPDATE usuarios SET nombre='$nombre', password='$newpsswdhash', tipo='vendedor', foto='" . $imgName . "' WHERE email='" . $_SESSION['email'] . "'";
                        $pathProductos = "../img/usrFotos/".$_SESSION['email']."/products";
                        mkdir($pathProductos);
                    } else {
                        $sentencia = "UPDATE usuarios SET nombre='$nombre', password='$newpsswdhash', foto='" . $imgName . "' WHERE email='" . $_SESSION['email'] . "'";
                    }
                    $result = ejecutarConsulta($sentencia);
                    move_uploaded_file($_FILES['imagen']['tmp_name'], $imgRuta);
                    $_SESSION['nombre'] = $nombre;
                    header('Location: ./perfil.php');
                } else {
                    $errores[] = "Contraseña actual no válida";
                }
            } else {
                $errores[] = "Email no encontrado";
            }
        } else {
            if ($newpsswd === $confirmpsswd) {
                if (!isset($errores)) {
                    $errores[] = "Campos no válidos";
                }
            } else {
                $errores[] = "Las contraseñas no coinciden";
            }
        }
        if (isset($errores)) {
            mostrarPantallaEditarPerfil($errores);
        }
    } else {
        mostrarPantallaEditarPerfil();
    }
} else {
    header('Location: ./principal.php');
}
?>
