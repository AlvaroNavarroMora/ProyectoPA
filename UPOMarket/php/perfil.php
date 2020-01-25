<?php

//funciones
function mostrarPerfil($nombre, $email, $tipo) {
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
                        <a href="./perfil.php" class="list-group-item active">Mis Datos</a>
                        <a href="./aniadirDireccion.php" class="list-group-item">Añadir Dirección</a>
                        <a href="#" class="list-group-item">Category 3</a>
                    </div>
                </div>
                <!-- /.col-lg-3 -->

                <div class="col-lg-9">
                    <div id="contenedorPerfil">
                        <div class="card mt-4">
                            <div class="card-body">
                                <h3 class="card-title">Perfil
                                    <a href="editarPerfil.php"><button id="profileButton"><i class="fa fa-edit"></i></button></a>
                                </h3>
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
        mostrarPerfil($nombre, $email, $tipo);
    }
} else {
    header('Location: ./principal.php');
}
?>

