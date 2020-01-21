<?php
session_start();

if (isset($_GET["idProducto"])) {
    include './utils/utilsProductos.php';
    $idProducto = filter_var($_GET["idProducto"], FILTER_SANITIZE_NUMBER_INT);
    $producto = obtenerProducto($idProducto);
    $ruta = "../img/usrFotos/" . $producto["email_vendedor"] . "/products/";
    $img = $producto["imagen"];
    if ($img == "ninguna" || $img == "") {
        $img = $ruta . "productDefaultImage.jpg";
    } else {
        $img = $ruta . $img;
    }
    $caracteristicas = listarCaracteristicasProducto($idProducto);
    $valoraciones = listarValoracionesProcucto($idProducto);
    $puntuacion = obtenerPuntuacionProducto($idProducto);
    //$categorias = obtenerCategoriasProducto($idProducto);
} else {
    //header("location:principal.php");
}
if (isset($_GET["enviarValoracion"])) {
    $idProducto = filter_var($_GET["idProducto"], FILTER_SANITIZE_NUMBER_INT);
    $puntuacion_nueva = filter_var($_GET["puntuacion"], FILTER_SANITIZE_NUMBER_INT);
    $valoracion_nueva = trim(filter_var($_GET["valoracion"], FILTER_SANITIZE_STRING));
    header("location:producto.php?idProducto=$idProducto");

    valorarProducto($_SESSION["email"], $idProducto, $puntuacion_nueva, $valoracion_nueva);
}

function mostrarValorar() {
    ?>
    <form id='formValoracionProducto' class="md-form mr-auto mb-4" method="GET">
        <textarea class="form-control" name="valoracion" placeholder="Valora el producto" required></textarea>
        <?php
        for ($index = 1; $index <= 5; $index++) {
            echo "<span id='puntuacion-$index' class='review fa fa-star unchecked'></span>";
        }
        ?>
        <input id="puntuacion" type="number" name="puntuacion" hidden>
        <input name="idProducto" type="number" value="<?php echo $_GET["idProducto"] ?>" hidden>
        <input id="btn-coment" type="submit" name="enviarValoracion" value="Valora el producto!" class="btn btn-success">
    </form>
    <?php
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $producto['nombre'] ?> - UPOMarket</title>
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
    <script>
        $(document).ready(function () {
            $(".fa-star").click(function () {
                var id = $(this).attr('id');
                var puntuacion = parseInt(id.substring(id.length - 1, id.length));
                var estrellas = $(".review");
                for (var i = 0; i < estrellas.length; i++) {
                    if (i < puntuacion) {
                        $(estrellas[i]).addClass("checked");
                        $(estrellas[i]).removeClass("unchecked");
                    } else {
                        $(estrellas[i]).removeClass("checked");
                        $(estrellas[i]).addClass("unchecked");
                    }
                }
            });
            $("#formValoracionProducto").submit(function () {
                var estrellas = $(".fa-star");
                var cont = 0;
                for (var i = 0; i < estrellas.length; i++) {
                    if ($(estrellas[i]).hasClass("checked")) {
                        cont++;
                    }
                }
                $("#puntuacion").val(cont);
                if (cont > 0) {
                    return true;
                } else {
                    alert("Debe puntuar el producto");
                    return false;
                }

            });
            var puntuacion = parseFloat(<?php echo number_format($puntuacion, 2) ?>);
            var k=0;
            for (k = 0; k < puntuacion; k++) {
                var text = $("#productRating").text();
                if (k < puntuacion && k + 1 > puntuacion)
                    $("#productRating").append($("<i class='fas fa-star-half-alt'></i>"));
                else
                    $("#productRating").append($("<i class='fas fa-star'></i>"));
            }
            for(var j=k; j<5; j++) {
                $("#productRating").append($("<i class='far fa-star'></i>"));
            }
        });
    </script>

</head>

<body>
    <?php
    include './header.php';
    include './utils/encriptar.php';
    ?>

    <!-- Page Content -->
    <main class="container">
        <div class="row">
            <!-- LISTA DE CATEGORÍAS -->
            <div class="col-lg-3">
                <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                <nav id='categorias' class="list-group">
                    <a href="#" class="list-group-item active">Category 1</a>
                    <a href="#" class="list-group-item">Category 2</a>
                    <a href="#" class="list-group-item">Category 3</a>
                </nav>
            </div>
            <!-- /.col-lg-3 -->
            <div class="col-lg-9">
                <div class="card mt-4">
                    <img id='imgProducto' class="card-img-top img-fluid" src='<?php echo $img ?>' alt="">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo $producto['nombre'] ?></h3>
                        <h4><?php echo $producto['precio'] ?>€</h4>
                        <p class="card-text"><?php echo $producto['descripcion'] ?></p>
                        <div id="productRating" class="text-warning"></div>
                        <?php echo number_format($puntuacion, 1) ?> estrellas
                        <br />
                        <br />
                        <form action="./utils/anadirEliminarCarrito.php" method="post">
                            <input type="hidden" name="id" value="<?php echo encriptar($producto['id']); ?>">
                            <input type="hidden" name="nombre" value="<?php echo encriptar($producto['nombre']); ?>">
                            <button class="btn btn-primary" name="btnAgregarCarrito" value="Agregar al carrito" type="submit">Agregar al carrito</button>
                        </form>

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
                                echo '<li class="list-group-item"><strong>' . $c["nombre_caracteristica"] . ":</strong> " . $c["valor"] . '</li>';
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
                        foreach ($valoraciones as $v) {
                            echo "<span class='text-warning'>";
                            $nota = $v["puntuacion"];
                            for ($i = 0; $i < $nota; $i++) {
                                echo "&#9733;";
                            }
                            echo "</span>";
                            echo "<br>";
                            echo '<p>' . $v['descripcion'] . '</p>';
                            echo '<small>Por: ' . $v['email_cliente'] . '</small>';
                            echo "<br>";
                            echo '<small>Fecha: ' . $v['fecha'] . '</small>';
                            echo "<hr>";
                        }
                        if (isset($_SESSION["email"])) {
                            mostrarValorar();
                        }
                        ?>
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