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
                        <p class="list-group-item active">Opciones</p>
                        <a href="perfil.php" class="list-group-item">Ver Perfil</a>
                        <a href="cambiarImagenDePerfil.php" class="list-group-item">Cambiar Imagen</a>
                        <a href="cambiarNombreDeUsuario.php" class="list-group-item">Cambiar Nombre</a>
                        <a href="cambiarContrasenia.php" class="list-group-item">Cambiar Contraseña</a>
                        <?php if ($tipo == "cliente") {
                            ?>
                            <a href="convertirseEnVendedor.php" class="list-group-item">Convertirse en vendedor</a>
                        <?php }
                        ?>
                    </div>
                </div>


                <div class="col-lg-3">

                    <div class="card mt-4">
                        <div class="card-body">
                            <h3 class="card-title">Editar Direccones</h3>
                            
                        </div>
                    </div>

                </div>
                <!-- /.col-lg-9 -->

                <div class="col" id="contenedorDirecciones">

                    <h3 id="titDirecciones">Mis direcciones</h3>

                    <?php
                    $query = "SELECT direccion_cliente FROM direcciones_clientes WHERE email_cliente='" . $_SESSION['email'] . "'";
                    $result = ejecutarConsulta($query);
                    if (mysqli_num_rows($result) <= 0) {
                        echo "<p>No tiene ninguna dirección registrada aún, para poder realizar una compra registre una.</p>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $dirId = $row['direccion_cliente'];
                            $sentencia = "SELECT nombre, linea_1, linea_2, provincia, ciudad, cp FROM direcciones WHERE id='" . $dirId . "'";
                            $result2 = ejecutarConsulta($sentencia);
                            $row2 = mysqli_fetch_array($result2);
                            ?>
                            <div class="card mt-4 mr-4 d-inline-block col-lg-4">
                                <div class="card-body">
                                    <label><strong>Nombre</strong></label>
                                    <p><?php echo $row2['nombre'];?></p>
                                    <label><strong>Línea 1</strong></label>
                                    <p><?php echo $row2['linea_1'];?></p>
                                    <label><strong>Línea 2</strong></label>
                                    <p><?php echo $row2['linea_2'];?></p>
                                    <label><strong>Ciudad</strong></label>
                                    <p><?php echo $row2['ciudad'];?></p>
                                    <label><strong>Provincia</strong></label>
                                    <p><?php echo $row2['provincia'];?></p>
                                    <label><strong>Código Postal</strong></label>
                                    <p><?php echo $row2['cp'];?></p>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>

                </div>
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