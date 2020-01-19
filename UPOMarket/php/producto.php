<?php
session_start();

if (isset($_POST["idProducto"])) {
    include './utils/utilsProductos.php';
    $idProducto = filter_var($_POST["idProducto"], FILTER_SANITIZE_NUMBER_INT);
    $producto = obtenerProducto($idProducto);
    $ruta = "../img/usrFotos/".$_SESSION['email']."/products/";
    $img = $producto["imagen"];
    if($img == "ninguna" || $img == "") {
        $img = $ruta."productDefaultImage.jpg";
    }else {
        $img = $ruta.$img;
    }
    $caracteristicas = listarCaracteristicasProducto($idProducto);
    $valoraciones = listarValoracionesProcucto($idProducto);
    //$categorias = obtenerCategoriasProducto($idProducto);
    echo print_r($producto);
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Nombre Producto - Upomarket</title>
    <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/shop-homepage.css" rel="stylesheet">
    <link href="../css/header.css" rel="stylesheet">
    <link href="../css/footer.css" rel="stylesheet">
    <link href="../css/principal.css" rel="stylesheet" type="text/css"/>
    <link href="../css/producto.css" rel="stylesheet" type="text/css"/>
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
                <div class="card mt-4">
                    <img id='imgProducto' class="card-img-top img-fluid" src='<?php echo $img ?>' alt="">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo $producto['nombre']?></h3>
                        <h4><?php echo $producto['precio']?>€</h4>
                        <p class="card-text"><?php echo $producto['descripcion']?></p>
                        <span class="text-warning">&#9733; &#9733; &#9733; &#9733; &#9734;</span>
                        4.0 estrellas
                    </div>
                </div>
                <!-- /.card caracteristicas -->
                <div class="card card-outline-secondary my-4">
                    <div class="card-header">
                        Características
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php
                                foreach ($caracteristicas as $c) {
                                    echo '<li class="list-group-item">'.$c["nombre_caracteristica"]." | ".$c["valor"].'</li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
                <!-- fin /.card caracteristicas-->
                <!-- /.card Opiniones-->
                <div class="card card-outline-secondary my-4">
                    <div class="card-header">
                        Opiniones del producto
                    </div>
                    <div class="card-body">
                        <?php
                        foreach($valoraciones as $v) {
                            echo '<p>'.$v['descripcion'].'</p>';
                            echo '<small>Dicho por: '.$v['email_cliente'].'el'.$v['fecha'].'</small>';
                            echo "<hr>";
                        }
                        ?>
                        <a id="btn-coment" href="#" class="btn btn-success">Deja un comentario!</a>
                    </div>
                </div>
                <!-- fin /.card Opiniones-->
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