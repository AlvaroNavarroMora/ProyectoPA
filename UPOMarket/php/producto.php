<?php
session_start();

if (isset($_GET["idProducto"])) {
    include './utils/utilsProductos.php';

    $idProducto = filter_var($_GET["idProducto"], FILTER_SANITIZE_NUMBER_INT);
    $producto = obtenerProducto($idProducto);
    if ($producto) {
        if (file_exists($producto["imagen"])) {
            $img = $producto["imagen"];
        } else {
            $img = "../img/productDefaultImage.jpg";
        }
        $caracteristicas = listarCaracteristicasProducto($idProducto);
        if (isset($_SESSION["email"])) {
            $miValoracion = obtenerMiValoracionDelProducto($_SESSION["email"], $idProducto);
            $valoraciones = listarRestoValoracionesProducto($_SESSION["email"], $idProducto);
        } else {
            $valoraciones = listarValoracionesProducto($idProducto);
        }
        $puntuacion = obtenerPuntuacionProducto($idProducto);
        $categorias = listarCategorias();
    } else {
        $error = "Este producto no está disponible temporalmente";
    }
} else {
    header("location:principal.php");
}

if (isset($_GET["enviarValoracion"])) {
    $idProducto = filter_var($_GET["idProducto"], FILTER_SANITIZE_NUMBER_INT);
    $puntuacion_nueva = filter_var($_GET["puntuacion"], FILTER_SANITIZE_NUMBER_INT);
    $valoracion_nueva = trim(filter_var($_GET["valoracion"], FILTER_SANITIZE_STRING));
    if (filter_var($valoracion, FILTER_VALIDATE_INT, array("options" =>
                array("min_range" => 1, "max_range" => 5))) === false) {
        $error = "La valoración introducida no es válida";
    } else {
        valorarProducto($_SESSION["email"], $idProducto, $puntuacion_nueva, $valoracion_nueva);
    }

    header("location:producto.php?idProducto=$idProducto");
} else if (isset($_GET["editarValoracion"])) {
    $idProducto = filter_var($_GET["idProducto"], FILTER_SANITIZE_NUMBER_INT);
    $puntuacion_nueva = filter_var($_GET["puntuacion"], FILTER_SANITIZE_NUMBER_INT);
    $valoracion_nueva = trim(filter_var($_GET["valoracion"], FILTER_SANITIZE_STRING));

    actualizarValoracion($_SESSION["email"], $idProducto, $puntuacion_nueva, $valoracion_nueva);
    header("location:producto.php?idProducto=$idProducto");
} else if (isset($_GET["eliminarValoracion"])) {
    $idProducto = filter_var($_GET["idProducto"], FILTER_SANITIZE_NUMBER_INT);

    eliminarValoracion($_SESSION["email"], $idProducto);
    header("location:producto.php?idProducto=$idProducto");
}

function mostrarValorar() {
    ?>
    <form id='formValoracionProducto' class="md-form mr-auto mb-4" method="GET">
        <textarea class="form-control" name="valoracion" placeholder="Valora el producto" required></textarea>
        <?php
        for ($index = 1; $index <= 5; $index++) {
            echo "<span id = 'puntuacion-$index' class = 'review fa fa-star unchecked'></span>";
        }
        ?>
        <input id="puntuacion" type="number" name="puntuacion" hidden>
        <input name="idProducto" type="number" value="<?php echo $_GET["idProducto"]
        ?>" hidden>
        <br>
        <input id="btn-coment" type="submit" name="enviarValoracion" value="Enviar" class="btn btn-success">
    </form>
    <?php
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Producto/<?php echo $producto['nombre'] ?> - UPOMarket</title>
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
            animaEstrellas();
            obtenerValoracion();
            var puntuacion = parseFloat(<?php echo number_format($puntuacion, 2) ?>);
            var k = 0;
            for (k = 0; k < puntuacion; k++) {
                var text = $("#productRating").text();
                if (k < puntuacion && k + 1 > puntuacion)
                    $("#productRating").append($("<i class='fas fa-star-half-alt'></i>"));
                else
                    $("#productRating").append($("<i class='fas fa-star'></i>"));
            }
            for (var j = k; j < 5; j++) {
                $("#productRating").append($("<i class='far fa-star'></i>"));
            }
            $("#btnEliminar").click(function () {
                var idProducto = $("<input name='idProducto' type='number' hidden>");
                $(idProducto).val(<?php echo $_GET["idProducto"] ?>);
                $("#formEliminarValoracion").append(idProducto);
                $("#eliminarSubmit").click();
            });
        });
        function obtenerValoracion() {
            $("#formValoracionProducto").submit(function () {
                var estrellas = $("#formValoracionProducto .fa-star");
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
        }
        function animaEstrellas() {
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
        }
        function mostrarEditable() {
            $("#miValoracion").hide();
            var descripcion = $("#miValoracion p").text();
            var puntuacion = $("#miValoracion span").text().length;
            var form = $("<form id='formValoracionProducto' method='get'></form>");
            var text = $("<textarea class='form-control' name='valoracion'></textarea>");
            $(text).css("width", "100%");
            $(text).val(descripcion);
            $(form).append(text);
            for (var i = 1; i <= 5; i++) {
                var valora = $("<span class='review fa fa-star unchecked'></span>");
                $(valora).attr("id", "puntuacion-" + i);
                $(form).append(valora);
            }
            var btn = $("<input type='submit' class='btn btn-sm btn-success' value='Guardar' name='editarValoracion'>");
            $(btn).css("margin", "10px 10px 10px 0");
            $(form).append($("<br>"));
            $(form).append(btn);
            btn = $("<button type='button' class='btn btn-sm btn-warning'>Cancelar</button>");
            $(form).append(btn);
            $(form).append($("<input id='puntuacion' type='number' name='puntuacion' hidden>"));
            var idProducto = $("<input name='idProducto' type='number' hidden>");
            $(idProducto).val(<?php echo $_GET["idProducto"] ?>);
            $(form).append(idProducto);
            $("#miValoracion").after(form);
            animaEstrellas();
            obtenerValoracion();
            $(btn).click(function () {
                $("#formValoracionProducto").remove();
                $("#miValoracion").show();

            });
        }
    </script>

</head>

<body>
    <?php
    $categorias = listarCategorias();
    include './header.php';
    ?>
    <!-- Page Content -->
    <main class="container">
        <div class="row">
            <?php
            if (!empty($error)) {
                echo '<div class="alert alert-warning">' . $error . "</div>";
            } else {
                include './utils/encriptar.php';
                ?>
                <!-- LISTA DE CATEGORÍAS -->
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
                    ?>
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
                            <?php
                            if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                                $index = -1;
                                foreach ($_SESSION['carrito'] as $indice => $productoSes) {
                                    if ($productoSes['id'] == $producto['id']) {
                                        $index = $indice;
                                    }
                                }
                                if ($index == -1) {
                                    ?>
                                    <form action="./utils/anadirEliminarCarrito.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo encriptar($producto['id']); ?>">
                                        <input type="hidden" name="nombre" value="<?php echo encriptar($producto['nombre']); ?>">
                                        <button class="btn btn-primary" name="btnAgregarCarrito" value="Agregar al carrito" type="submit">Agregar al carrito</button>
                                    </form>
                                    <?php
                                } else {
                                    ?>
                                    <form action="./utils/anadirEliminarCarrito.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo encriptar($producto['id']); ?>">
                                        <button class="btn btn-primary" name="btnEliminarCarritoProducto" value="Eliminar del carrito" type="submit">Eliminar del carrito</button>
                                    </form>
                                    <?php
                                }
                            } else if (isset($_SESSION["email"])) {
                                ?>
                                <form action="./utils/anadirEliminarCarrito.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo encriptar($producto['id']); ?>">
                                    <input type="hidden" name="nombre" value="<?php echo encriptar($producto['nombre']); ?>">
                                    <button class="btn btn-primary" name="btnAgregarCarrito" value="Agregar al carrito" type="submit">Agregar al carrito</button>
                                </form>
                                <?php
                            } else {
                                echo '<div class="alert alert-info">Inicia Sesión para comprar este producto</div>';
                            }
                            ?>
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
                            if (empty($miValoracion) && empty($valoraciones)) {
                                echo "<p>Aún no hay opiniones para este producto.</p>";
                            } else {
                                if (!empty($miValoracion)) {
                                    echo "<div>";
                                    echo "<span class='text-warning'>";
                                    $nota = $miValoracion["puntuacion"];
                                    for ($i = 0; $i < $nota; $i++) {
                                        echo "&#9733;";
                                    }
                                    echo "</span>";
                                    if ($miValoracion["email_cliente"] == $_SESSION["email"]) {
                                        echo "<button id='btnEliminar' class='btn btn-sm btn-danger pull-right btn-valoracion'>Eliminar</button>";
                                        echo "<button class='btn btn-sm btn-warning pull-right btn-valoracion' onclick='mostrarEditable()'>Editar</button>";
                                    }
                                    echo "<br>";
                                    echo '<p>' . $miValoracion['descripcion'] . '</p>';
                                    echo '<small>Por: ' . $miValoracion['email_cliente'] . '</small>';
                                    echo "<br>";
                                    echo '<small>Fecha: ' . $miValoracion['fecha'] . '</small>';
                                    echo "</div>";
                                    echo "<hr>";
                                }
                                foreach ($valoraciones as $v) {
                                    echo "<div>";
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
                                    echo "</div>";
                                    echo "<hr>";
                                }
                            }
                            if (isset($_SESSION["email"]) && empty($miValoracion) && compradoPorMi($_SESSION["email"], $idProducto)) {
                                mostrarValorar();
                            }
                            ?>
                        </div>
                    </div>
                    <!-- fin /.card Opiniones-->
                </div>
                <!-- /.col-lg-9 -->
            <?php }
            ?>
        </div>
    </main>
    <!-- /.container -->
    <?php
    include '../html/footer.html';
    ?>
    <form id='formEliminarValoracion' method='GET'>
        <button id="eliminarSubmit" type="submit" name="eliminarValoracion" hidden></button>
    </form>
</body>
</html>