<?php
session_start();
include './utils/utilsProductos.php';
$categorias = listarCategorias();

if (isset($_GET["categoria"])) {
    $categoria = trim(filter_var($_GET["categoria"], FILTER_SANITIZE_STRING));
    $productosCategoria = listarProductosDeCategoria($categoria);
}

function mostrarProducto($producto) {
    $puntuacion = obtenerPuntuacionProducto($producto["id"]);
    if (file_exists($producto["imagen"])) {
        $img = $producto["imagen"];
    } else {
        $img = "../img/productDefaultImage.jpg";
    }
    ?>
    <div class = "col-lg-4 col-md-6 mb-4">
        <div class = "card h-100">
            <a href = "./producto.php?idProducto=<?php echo $producto["id"] ?>"><img class = "card-img-top" src = "<?php echo $img ?>" alt = ""></a>
            <div class = "card-body">
                <h4 class = "card-title">
                    <a href = "./producto.php?idProducto=<?php echo $producto["id"] ?>"><?php echo $producto["nombre"] ?></a>
                </h4>
                <h5><?php echo $producto["precio"] ?>&euro;</h5>
                <p class = "card-text"><?php echo $producto["descripcion"] ?></p>
            </div>
            <div class = "card-footer">
                <small class = "text-muted stars"><span hidden><?php echo $puntuacion ?></span></small>
            </div>
        </div>
    </div>
    <?php
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Categoría/<?php echo $categoria ?> - UPOMarket</title>
        <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="../css/shop-homepage.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet">
        <link href="../css/principal.css" rel="stylesheet">
        <script src="../frameworks/jquery/jquery.min.js"></script>
        <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

        <script>
            $(document).ready(function () {
                var estrellas = $(".stars");
                for (var i = 0; i < estrellas.length; i++) {
                    var puntuacion = parseFloat($(estrellas[i]).first().text());
                    if (isNaN(puntuacion)) {
                        puntuacion = 0;
                    }
                    var k = 0;
                    for (k = 0; k < puntuacion; k++) {
                        if (k < puntuacion && k + 1 > puntuacion)
                            $(estrellas[i]).append($("<i class='fas fa-star-half-alt'></i>"));
                        else
                            $(estrellas[i]).append($("<i class='fas fa-star'></i>"));
                    }
                    for (var j = k; j < 5; j++) {
                        $(estrellas[i]).append($("<i class='far fa-star'></i>"));
                    }
                }
            });
        </script>

    </head>

    <body>
        <?php
        include './header.php';
        ?>
        <!-- Page Content -->
        <main class="container">
            <div class="row">
                <div class="col-lg-3">
                    <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                    <nav id='categorias' class="list-group">
                        <ul class="list-unstyled">
                            <h4 class="text-center">Categorías</h4>
                            <?php
                            foreach ($categorias as $c) {
                                if ($c[0] === $categoria) {
                                    echo '<li><a href="./categoria.php?categoria=' . $c[0] . '" class="list-group-item"><strong>' . $c[0] . '</strong></a></li>';
                                } else {
                                    echo '<li><a href="./categoria.php?categoria=' . $c[0] . '" class="list-group-item">' . $c[0] . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </nav>

                </div>
                <!-- /.col-lg-3 -->

                <div class="col-lg-9">
                    <?php
                    include './barraBusqueda.php';
                    
                    if (!empty($productosCategoria)) {
                        echo '<div class="row">';
                        foreach ($productosCategoria as $producto) {
                            mostrarProducto($producto);
                        }
                        echo '</div>';
                    } else {
                        echo "<h5>No hay productos para mostrar.</h5";
                    }
                    ?>
                </div>
                <!-- /.col-lg-9 -->

            </div>
            <!-- /.row -->

        </main>
        <!-- /.container -->
        <?php
        include '../html/footer.html';
        ?>
    </body>

</html>