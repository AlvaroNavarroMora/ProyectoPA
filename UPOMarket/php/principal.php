<?php
session_start();
include './utils/utilsProductos.php';
$categorias = listarCategorias();

if (isset($_SESSION['email']) && isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'admin') {
    header("location: ./vistaAdministrador.php");
}

if (isset($_GET["ordenar"])) {
    $opcion = filter_var($_GET["ordenar"], FILTER_SANITIZE_NUMBER_INT);
    switch ($opcion) {
        case 0: $productos = listarProductosPorValoracion();
            break;
        case 1: $productos = listarProductosMasVendidos();
            break;
        case 2: $productos = listarProductosMasRecientes();
            break;
        case 3: $productos = listarProductosPorPrecio("ASC");
            break;
        case 4: $productos = listarProductosPorPrecio("DESC");
            break;
        default: $productos = listarProductos();
            break;
    }
    $ids = Array();
    foreach ($productos as $p) {
        $ids[] = $p["id"];
    }
    if (!empty($productos)) {
        $restoProductos = listarRestoProductos(implode(", ", $ids));
        $productos = array_merge($productos, $restoProductos);
    } else {
        $productos = listarProductos();
    }
} else {
    $productos = listarProductos();
}

$productosCarrusel = listarTopVentas(3);

function mostrarProducto($producto) {
    $puntuacion = obtenerPuntuacionProducto($producto["id"]);
    $puntuacion = obtenerPuntuacionProducto($producto["id"]);
    if (file_exists($producto["imagen"])) {
        $img = $producto["imagen"];
    } else {
        $img = "../img/productDefaultImage.jpg";
    }
    ?>
    <div class = "col-lg-4 col-md-6 mb-4">
        <div class = "card h-100">
            <a href = "./producto.php?idProducto=<?php echo $producto["id"] ?>"><img class = "card-img-top lazyload" data-src = "<?php echo $img ?>" alt = ""></a>
            <div class = "card-body">
                <h4 class = "card-title">
                    <a href = "./producto.php?idProducto=<?php echo $producto["id"] ?>"><?php echo $producto["nombre"] ?></a>
                </h4>
                <h5><?php echo $producto["precio"] ?>&euro;</h5>
                <p class = "card-text">
                    <?php
                    $descripcion = $producto["descripcion"];
                    if (strlen($descripcion) > 50) {
                        $descripcion = substr($descripcion, 0, 50);
                        $descripcion .= "...";
                    }
                    echo $descripcion;
                    ?>
                </p>
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

        <title>Inicio - UPOMarket</title>
        <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="../css/shop-homepage.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet">
        <link href="../css/principal.css" rel="stylesheet">
        <script src="../frameworks/jquery/jquery.min.js"></script>
        <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->
        <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script><!-- Para lazy loading de imagenes-->

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
                $("img").onerror = function () {
                    $(this).attr("src", "../img/productDefaultImage.jpg");
                };

                $("img.lazyload").lazyload();
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
                                echo '<li><a href="./categoria.php?categoria=' . $c[0] . '" class="list-group-item">' . $c[0] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>

                </div>
                <!-- /.col-lg-3 -->

                <div class="col-lg-9">
                    <?php
                    include './barraBusqueda.php';

                    if (!empty($productosCarrusel)) {
                        echo '<div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">';
                        echo '<ol class="carousel-indicators">';
                        foreach ($productosCarrusel as $key => $producto) {
                            echo '<li data-target="#carouselExampleIndicators" data-slide-to="' . $key . '" ';
                            if ($key == 0) {
                                echo 'class="active"';
                            }
                            echo '></li>';
                        }
                        echo '</ol>';

                        echo '<div class="carousel-inner" role="listbox">';
                        foreach ($productosCarrusel as $key => $value) {
                            echo '
                                    <div class="carousel-item ';
                            if ($key === 0) {
                                echo 'active';
                            }
                            echo '"><a href="./producto.php?idProducto=' . $value["id"] . '">
                                        <img width="1000" class="d-block img-fluid" src="' . $value["imagen"] . '" alt="Imagen carrusel ' . $key . '">
                                    </a>
                                    </div>';
                        }
                        echo '</div>';
                        echo '<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>';
                    }
                    ?>
                    <div class="row">
                        <?php
                        if (!empty($productos)) {
                            foreach ($productos as $producto) {
                                mostrarProducto($producto);
                            }
                        } else {
                            echo "No hay productos para mostrar.";
                        }
                        ?>
                    </div>
                    <!-- /.row -->

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